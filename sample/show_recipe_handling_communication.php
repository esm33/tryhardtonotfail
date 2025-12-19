<?php
session_start();

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

try {
    $conn = new AMQPConnection([
        'host'     => $RABBITMQ['host'],
        'port'     => $RABBITMQ['port'],
        'login'    => $RABBITMQ['user'],
        'password' => $RABBITMQ['password'],
        'vhost'    => $RABBITMQ['vhost']
    ]);
    
    $conn->connect();
    $channel = new AMQPChannel($conn);

    $exchange = new AMQPExchange($channel);
    $exchange->setName($RABBITMQ['exchange']);
    $exchange->setType(AMQP_EX_TYPE_DIRECT);
    $exchange->setFlags(AMQP_DURABLE);
    $exchange->declareExchange();

    $callbackQueue = new AMQPQueue($channel);
    $callbackQueue->setFlags(AMQP_EXCLUSIVE);
    $callbackQueue->declareQueue();
    $callbackQueueName = $callbackQueue->getName();

    $corrId = uniqid('rpc_');
    $requestData = json_encode(['type' => 'get_recipes']);
    
    $exchange->publish(
        $requestData, 
        $RABBITMQ['routing_key'], 
        AMQP_NOPARAM, 
        [
            'reply_to'       => $callbackQueueName,
            'correlation_id' => $corrId,
            'content_type'   => 'application/json'
        ]
    );

    $response = null;
    $startTime = time();
    $timeout = 5; 

    while (!$response && (time() - $startTime) < $timeout) {
        $message = $callbackQueue->get(AMQP_AUTOACK);
        if ($message && $message->getCorrelationId() == $corrId) {
            $response = $message->getBody();
        }
    }

    $conn->disconnect();

    header('Content-Type: application/json');
    if ($response) {
        echo $response;
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Request timed out or no recipes found"
        ]);
    }

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => "Connection error: " . $e->getMessage()
    ]);
}
?>


