<?php
// DB Listener for RabbitMQ
$connection = new AMQPConnection([
    'host' => '100.86.240.90',
    'login' => 'webapp',
    'password' => 'password123',
    'vhost' => '/'
]);

if (!$connection->connect()) {
    die("Cannot connect to RabbitMQ server\n");
}

$channel = new AMQPChannel($connection);

// Declare exchange
$exchange = new AMQPExchange($channel);
$exchange->setName('testExchange');
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();

// Declare and bind queue
$queue = new AMQPQueue($channel);
$queue->setName('testQueue');
$queue->setFlags(AMQP_DURABLE);
$queue->declareQueue();
$queue->bind('testExchange', 'db_route');

echo " [x] Waiting for messages on 'testQueue'...\n";

while (true) {
    $queue->consume(function (AMQPEnvelope $message, AMQPQueue $queue) {
        $body = $message->getBody();
        echo " [>] Received raw message: $body\n";

        $data = json_decode($body, true);
        if ($data === null) {
            echo " [!] JSON decode failed or null message\n";
        } else {
            echo " [✓] Decoded message:\n";
            print_r($data);
        }

        $queue->ack($message->getDeliveryTag());
    });
}
?>

