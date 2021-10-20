<?php

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
    ],
];