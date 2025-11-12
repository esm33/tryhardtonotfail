<?php

session_start();

// communication.php
// Handles sending registration/login messages to RabbitMQ and waits for DB VM response

header('Content-Type: application/json');

// Basic POST validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo json_encode(["status" => "error", "message" => "No POST data received"]);
    exit;
}

$type  = $_POST['type']  ?? null;
$rname = $_POST['rname'] ?? null;
$dtype = $_POST['dtype'] ?? null;
$d_ingredient = $_POST['d_ingredient'] ?? null;
$d_instructions = $_POST['d_instructions'] ?? null;

if (!$type || !$rname || !$dtype || $d_ingredients || $d_instructions) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// RabbitMQ connection info
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
    // Connect to RabbitMQS
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

    // Create or declare a response queue (unique per client)
    $callbackQueue = new AMQPQueue($channel);
    $callbackQueue->setName($RABBITMQ['queue'] . '_response');
    $callbackQueue->declareQueue(); // no bind needed

    $corrId = uniqid();

    $message = [
        "type"  => $type,
        "rname" => $rname,
        "dtype" => $dtype,
        "d_ingredient" = $d_ingredient,
        "d_instructions" = $d_instructions
    ];

    // Publish the request
    $exchange->publish(
        json_encode($message),
        $RABBITMQ['routing_key'],
        AMQP_NOPARAM,
        [
            'reply_to'        => $callbackQueue->getName(),
            'correlation_id'  => $corrId
        ]
    );

    // Wait for response from DB VM
    $response = null;
    $start    = time();
    $timeout  = 6; // seconds

    while ((time() - $start) < $timeout && !$response) {
        $callbackQueue->consume(function (AMQPEnvelope $msg, AMQPQueue $queue) use (&$response, $corrId) {
            if ($msg->getCorrelationId() === $corrId) {
                $response = json_decode($msg->getBody(), true);
                $queue->ack($msg->getDeliveryTag());
                return false; // stop consuming
            }
            return true; // keep listening
        });
    }

    // Fallback if DB didn’t reply in time
    if (!$response) {
        $response = ["status" => "error", "message" => "No response from DB server (timeout)"];
    }

//if DB returns a successful login
    if($response == 1)
    {
        $_SESSION['successful_login'] = true;
        $_SESSION['username_profile'] = $uname;
    }
//so communication.php is getting the login/regis data via post, sends to RabbitMQ, returns json
    echo json_encode($response);

    $conn->disconnect();

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
}

?>




