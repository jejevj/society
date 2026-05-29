<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;
    protected $queue = 'DCATtoSDI';

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            '151.243.222.251', // host RabbitMQ
            5672,        // port
            'admin',     // username
            'secret'      // password
        );
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function publish(array $data)
    {
        $msg = new AMQPMessage(json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        $this->channel->basic_publish($msg, '', $this->queue);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
