<?php

namespace MdimAMQPLaravel;

use PhpAmqpLib\Message\AMQPMessage;

class Publisher 
{    
    use Connection;
    use Queue;
    use Close;
    
    protected $connection;
    protected $channel;

    public function publish(
        $msg_body,
        $msg_properties = [],
        $exchange = '',
        $routing_key = '',
        $mandatory = false,
        $immediate = false,
        $ticket = null
    )
    {
        $msg = new AMQPMessage($msg_body, $msg_properties);
        $this->channel->basic_publish($msg, $exchange, $routing_key, $mandatory, $immediate, $ticket);
        
        return $this;
    }
}
