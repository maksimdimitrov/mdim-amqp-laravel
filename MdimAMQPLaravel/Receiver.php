<?php

namespace MdimAMQPLaravel;

class Receiver 
{
    use Connection;
    use Queue;
    use Qos;
    use Close;
    
    protected $connection;
    protected $channel;

    public function consume(
        $queue = '',
        $consumer_tag = '',
        $no_local = false,
        $no_ack = false,
        $exclusive = false,
        $nowait = false,
        $callback = null,
        $ticket = null,
        $arguments = array()
    ) 
    {
        $this->channel->basic_consume($queue, $consumer_tag, $no_local, $no_ack, $exclusive, $nowait, $callback, $ticket, $arguments);
        
        return $this;
    }
    
    public function listen()
    {
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
        
        return $this;
    }
}
