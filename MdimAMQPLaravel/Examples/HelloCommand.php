<?php

namespace MdimAMQPLaravel\Examples;

use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use MdimAMQPLaravel\Package;

class HelloCommand extends Command 
{

    protected $signature = Package::PREFIX . 'rabbitmq:publish_hello';
    protected $description = 'Example rabbitmq publish command';

    public function handle() 
    {
        $number = rand(11111, 99999);
        app(Package::PREFIX . 'rabbitmq-publisher')
            ->connection('default')
            ->queue(HelloJob::getQueueName(), false, true, false, false)
            ->publish(
                'Hello, your number is ' . $number, 
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT], 
                '', 
                HelloJob::getQueueName())
            ->close();
        echo 'Send message number ' . $number . PHP_EOL;
        
        return Command::SUCCESS;
    }

}
