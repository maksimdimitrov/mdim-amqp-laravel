<?php

namespace MdimAMQPLaravel\Examples;

use MdimAMQPLaravel\ReceiveJob;

class HelloJob extends ReceiveJob
{
    public function handle()
    {
        /**
         * implement your callback function when message is received, dependencies can be injected
         */
        
        echo 'message -> ' . $this->msg->body . PHP_EOL;
        $this->msg->delivery_info['channel']->basic_ack($this->msg->delivery_info['delivery_tag']);
    }
    
    public static function getQueueName()
    {
        return 'example.queue.hello';
    }
    
    public static function getConsumerTag() 
    {
        return '';
    }
    
    /** methods rewrite */
    
    public static function getQueueDurableValue()
    {
        return true;
    }
    
    public static function getQueueAutoDeleteValue()
    {
        return false;
    } 
}
