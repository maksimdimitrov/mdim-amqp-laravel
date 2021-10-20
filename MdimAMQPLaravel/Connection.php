<?php

namespace MdimAMQPLaravel;

use Illuminate\Support\Arr;

trait Connection 
{
    public function connection($name)
    {
        $config = config(Package::PREFIX . 'rabbitmq');
        $connectionConfig = $config['connections'][$name];
        if (!$connectionConfig)
        {
            throw new \Exception('missing configuration');
        }
        
        $class = Arr::get($connectionConfig, 'connectionClass', AMQPStreamConnection::class);
        $hosts = $connectionConfig['connectionConfig'];
        $options = isset($connectionConfig['connectionOptions']) ? $connectionConfig['connectionOptions']: [];
        Arr::shuffle($hosts);
        
        $this->connection = $class::create_connection($hosts, $options);
        $this->channel = $this->connection->channel();
        
        return $this;
    }
}
