<?php

return [
    'hostname' => 'mensageria',
    'port' => '5672',
    'user' => env('RABBITMQ_DEFAULT_USER'),
    'pass' => env('RABBITMQ_DEFAULT_PASS')
];
