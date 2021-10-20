<?php

namespace MdimAMQPLaravel;

trait Queue 
{
    public function queue(
        $queue = '',
        $passive = false,
        $durable = false,
        $exclusive = false,
        $auto_delete = true,
        $nowait = false,
        $arguments = [],
        $ticket = null   
    )
    {
        $this->channel->queue_declare($queue, $passive, $durable, $exclusive, $auto_delete, $nowait, $arguments, $ticket);
        
        return $this;
    }
}
