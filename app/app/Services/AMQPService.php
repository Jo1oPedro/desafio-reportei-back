<?php

namespace App\Services;

use App\Contracts\Messaging;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPService implements Messaging
{
    private $connection;
    private AbstractChannel|AMQPChannel $channel;

    public function __construct()
    {
        $hostname = config('amqp.hostname');
        $port = config('amqp.port');
        $user = config('amqp.user');
        $pass = config('amqp.pass');
        $this->connection = new AMQPStreamConnection($hostname, $port, $user, $pass);
        $this->channel = $this->connection->channel();
    }

    public function publishMessage(string $message, string $queueName): AMQPService
    {
        $this->channel->queue_declare($queueName, false, true, false, false);
        $rabbitMsg = new AMQPMessage($message);
        $this->channel->basic_publish($rabbitMsg, '', $queueName);
        return $this;
    }

    public function destruct(?string $queueName = null): AMQPService
    {
        $this->channel->close();
        $this->connection->close();
        return $this;
    }
}
