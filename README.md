# Laravel Service Provider for php-amqplib to use with RabbitMQ without Laravel queues.

It is developed for versions greater than Laravel 5.7

### How to install

#### Option 1 
Use ```composer require maksimdimitrov/mdim-amqp-laravel```

#### Option 2
Add to your composer.json file in "require" section:

```"maksimdimitrov/mdim-amqp-laravel": "dev-master"```

and in "repositories" section:

```{ "type": "vcs", "url": "https://github.com/maksimdimitrov/mdim-amqp-laravel.git" }```

Next: ```composer install``` 


Next step is to run ```php artisan vendor:publish --tag=mdim_rabbitmq-config``` to publish the config file in config/mdim_rabbitmq.php
Check the available commands ```mdim_rabbitmq:publish_hello``` and ```mdim_rabbitmq:start_consumer 'App\SomeConsumers\SomeJob'``` with ```php artisan```

### The config in config/mdim_rabbitmq.php
You can have many connections:  default, some-other-rabbitmq-service, etc...
```
return [
    'connections' => [
        'default' => [
            
            /** optional, it will default to AMQPStreamConnection */
            'connectionClass' => PhpAmqpLib\Connection\AMQPStreamConnection::class,

            'connectionConfig' => [
                [
                    'host' => 'rabbitmq',
                    'port' => '5672',
                    'user' => 'guest',
                    'password' => 'guest',
                ],
            ],

            'connectionOptions' => [],    
        ],
        
        'some-other-rabbitmq-service' => [

            'connectionConfig' => [
                [
                    'host' => 'localhost',
                    'port' => '5672',
                    'user' => 'guest',
                    'password' => 'guest',
                ],
            ],   
        ],
    ],
];
```

### How to use 
You need to create a new job class which will act as consumer and hold the callback function when a message is received.
The class has to extend ```MdimAMQPLaravel\ReceiveJob``` and have function handle() implemented, which is the callback function.
You can use the dependency container to inject services.
You also need to define getConnectionName(), getQueueName() and getConsumerTag(), check AMQP docs for property descriptions https://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.consume
getConnectionName() should return name of the connection from config/mdim_rabbitmq.php e.g. default or some-other-rabbitmq-service

All the static methods in ```MdimAMQPLaravel\ReceiveJob``` you can rewrite for your consumer needs.

There are two examples ready to run.
Check the job class ```MdimAMQPLaravel\Examples\HelloJob``` which will consume messages after you start it.
You can push messages with example command class ```MdimAMQPLaravel\Examples\HelloCommand```

Start this example with a command in the console 
```php artisan mdim_rabbitmq:start_consumer 'MdimAMQPLaravel\Examples\HelloJob'```

Open another console and run a command 
```php artisan mdim_rabbitmq:publish_hello```

In the first console you should see the received message now.

After you write your job work class with example name ```SomeNamespace\SomeClass``` you can start it with 

```php artisan mdim_rabbitmq:start_consumer 'SomeNamespace\SomeClass'```

Publishing messages can be done without commands, but also from your controller like this:

```

app('mdim_rabbitmq-publisher')
    ->connection('default') // default is the connection name from config file, e.g. default or some-other-rabbitmq-service
    ->queue('some-queue-name', false, true, false, false)
    ->publish('Hello message', ['delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT], '', 'some-queue-name')
    ->close();
            
```
An example consumer class can be:

```

namespace SomeNamespace;
use MdimAMQPLaravel\ReceiveJob;

class SomeClass extends ReceiveJob
{
    public function handle()
    {
        echo 'message -> ' . $this->msg->body . PHP_EOL;
        $this->msg->delivery_info['channel']->basic_ack($this->msg->delivery_info['delivery_tag']);
    }
    
    public static function getConnectionName()
    {
        return 'default'; // default is the connection name from config file, e.g. default or some-other-rabbitmq-service
    }
    
    public static function getQueueName()
    {
        return 'some-queue-name';
    }
    
    public static function getConsumerTag() 
    {
        return '';
    }
}

```

