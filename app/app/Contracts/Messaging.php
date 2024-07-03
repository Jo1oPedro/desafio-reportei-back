<?php

namespace App\Contracts;

interface Messaging
{
    public function publishMessage(string $message, string $queueName): Messaging;
    public function destruct(?string $queueName = null): Messaging;
}
