<?php

$search_term = $_POST['search'] ?? null;
if (!$search_term) {
    echo json_encode(["status" => "error", "message" => "No search term provided"]);
    exit;
}

$RABBITMQ = [
    "host" => "100.86.240.90",
    "port" => 5672,
    "user" => "webapp",
    "password" => "password123",
    "vhost" => "/",
    "exchange" => "testExchange",
    "routing_key" => "dmzQueue", 
    "response_queue" => "frontend_api_response" 
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

    $channel  = new AMQPChannel($conn);
    $exchange = new AMQPExchange($channel);
    $exchange->setName($RABBITMQ['exchange']);
    $exchange->setType('direct');
    $exchange->setFlags(AMQP_DURABLE);
    $exchange->declareExchange(); 
    $callbackQueue = new AMQPQueue($channel);
    $callbackQueue->setName($RABBITMQ['response_queue']);
    $callbackQueue->setFlags(AMQP_AUTODELETE); 
    $callbackQueue->declareQueue();

    $corrId = uniqid();

    $requestPayload = [
        "type" => "search_cocktail",
        "query" => $search_term
    ];
    $exchange->publish(
        json_encode($requestPayload),
        $RABBITMQ['routing_key'],
        AMQP_NOPARAM,
        [
            'reply_to'        => $callbackQueue->getName(),
            'correlation_id'  => $corrId
        ]
    );

    $response = null;
    $start    = time();
    $timeout  = 10;
    try {
        $callbackQueue->consume(function (AMQPEnvelope $msg, AMQPQueue $queue) use (&$response, $corrId) {
            if ($msg->getCorrelationId() === $corrId) {
                $body = $msg->getBody();
                $response = json_decode($body, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $response = ["raw_response" => $body];
                }

                $queue->ack($msg->getDeliveryTag());
                return false;
            }
         
            $queue->ack($msg->getDeliveryTag()); 
            return true; 
        });
    } catch (AMQPQueueException $e) {

    }

    $conn->disconnect();
    
    //post resposne to site? 
    if ($response) {
        echo json_encode($response);
    } else {
        echo json_encode(["status" => "error", "message" => "API Request Timeout"]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
}
?>
