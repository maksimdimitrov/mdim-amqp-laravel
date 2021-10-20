<?php

namespace MdimAMQPLaravel;

use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;

class ReceiveCommand extends Command
{
    protected $signature = Package::PREFIX . 'rabbitmq:start_consumer {jobClass}';
    protected $description = 'Rabbitmq consume command';

    public function handle()
    {
        $class = $this->argument('jobClass');
        
        if (!is_subclass_of($class, ReceiveJob::class))
        {
            throw new \Exception('job class ' . $class . ' should extend ReceiveJob');
        }

        if (!method_exists($class, 'handle'))
        {
            throw new \Exception('job class ' . $class . ' missing function handle()');
        }

        app(Package::PREFIX . 'rabbitmq-receiver')
            ->connection('default')
            ->queue(
                $class::getQueueName(), 
                $class::getQueuePassiveValue(), 
                $class::getQueueDurableValue(), 
                $class::getQueueExclusiveValue(), 
                $class::getQueueAutoDeleteValue(),
                $class::getQueueNowaitValue(),
                $class::getQueueArgumentsValue(),
                $class::getQueueTicketValue()    
            )
            ->qos($class::getQosPrefetchSizeValue(), $class::getQosPrefetchCountValue(), $class::getQosAglobalValue())
            ->consume(
                $class::getQueueName(), 
                $class::getConsumerTag(), 
                $class::getConsumeNoLocalValue(), 
                $class::getConsumeNoAckValue(), 
                $class::getConsumeExclusiveValue(), 
                $class::getConsumeNowaitValue(), 
                function(AMQPMessage $msg) use ($class) {
                    $inst = new $class($msg);
                    dispatch($inst);
                },
                $class::getConsumeTicketValue(),  
                $class::getConsumeArgumentsValue(),
            )
            ->listen()
            ->close();
    }
}
