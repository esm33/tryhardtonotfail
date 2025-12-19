<?php
$RABBITMQ = [
    "host" => "100.86.240.90",
    "port" => 5672,
    "user" => "webapp",
    "password" => "password123",
    "vhost" => "/",
    "exchange" => "testExchange",
    "queue" => "testQueue",
    "routing_key" => "testQueue"
];
    $conn = new AMQPConnection([
        'host'     => $RABBITMQ['host'],
        'port'     => $RABBITMQ['port'],
        'login'    => $RABBITMQ['user'],
        'password' => $RABBITMQ['password'],
        'vhost'    => $RABBITMQ['vhost']
    ]);
    $conn->connect();

    $channel  = new AMQPChannel($conn);
    $exchange = new AMQPExchange($channel);
    $exchange->setName($RABBITMQ['exchange']);
    $exchange->setType('direct');
    $exchange->setFlags(AMQP_DURABLE);
    $exchange->declareExchange();

    $callbackQueue = new AMQPQueue($channel);
    $callbackQueue->setName($RABBITMQ['queue'] . '_response');
    $callbackQueue->declareQueue();

    $corrId = uniqid();
$requestData = json_encode(['type' => 'get_recipes']);  //all we need is get recipes for db listner to return based on that type
$exchange->publish($requestData, 'testQueue');
echo "Sent request for recipes\n";

$responseQueue = new AMQPQueue($channel);
$responseQueue->setName('responseQueue');
$responseQueue->setFlags(AMQP_DURABLE);
$responseQueue->declareQueue();
$responseQueue->bind('responseExchange', 'responseQueue');

echo "Waiting for response...\n";

$responseQueue->consume(function (AMQPEnvelope $message, AMQPQueue $queue) {
    $body = $message->getBody();
    echo "Received response: $body\n";
    
    $data = json_decode($body, true);
    
    if ($data['status'] === 'success') {
        return ($data['recipes']);
    }
    
    $queue->ack($message->getDeliveryTag());
    return false; 
}, AMQP_NOPARAM, 5000);

$connection->disconnect();
?>
