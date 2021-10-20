<?php

namespace MdimAMQPLaravel;

use Illuminate\Foundation\Bus\Dispatchable;
use PhpAmqpLib\Message\AMQPMessage;

abstract class ReceiveJob 
{
    use Dispatchable;
    
    protected $msg;

    public function __construct(AMQPMessage $msg)
    {
        $this->msg = $msg;
    }
    
    abstract public static function getQueueName();
    abstract public static function getConsumerTag();
    abstract public static function getConnectionName();

    /** default parameters for queue, qos, consume methods. check docs for php-amqplib */
    
    /** queue_declare */

    public static function getQueuePassiveValue()
    {
        return false;
    }
    
    public static function getQueueDurableValue()
    {
        return false;
    }

    public static function getQueueExclusiveValue()
    {
        return false;
    }
    
    public static function getQueueAutoDeleteValue()
    {
        return true;
    }
    
    public static function getQueueNowaitValue()
    {
        return false;
    }
    
    public static function getQueueArgumentsValue()
    {
        return [];
    }
    
    public static function getQueueTicketValue()
    {
        return null;
    }
    
    /** basic_qos */

    public static function getQosPrefetchSizeValue()
    {
        return null;
    }
    
    public static function getQosPrefetchCountValue()
    {
        return 1;
    }
    
    public static function getQosAglobalValue()
    {
        return null;
    }
    
    /** basic_consume */

    public static function getConsumeNoLocalValue()
    {
        return false;
    } 
    
    public static function getConsumeNoAckValue()
    {
        return false;
    } 
    
    public static function getConsumeExclusiveValue()
    {
        return false;
    } 
    
    public static function getConsumeNowaitValue()
    {
        return false;
    } 
    
    public static function getConsumeTicketValue()
    {
        return null;
    } 
    
    public static function getConsumeArgumentsValue()
    {
        return [];
    } 
}
