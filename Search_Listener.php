<?php
//this is the DMZ file
$RABBITMQ = [
    "host" => "100.86.240.90",
    "port" => 5672,
    "user" => "webapp",
    "password" => "password123",
    "vhost" => "/",
    "exchange" => "testExchange",
    "queue" => "dmzQueue"
];
echo "Connecting to RabbitMQ at {$RABBITMQ['host']}...\n";

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
    $exchange->setType('direct');
    $exchange ->setFlags(AMQP_DURABLE);	
    $exchange->declareExchange();

    $queue = new AMQPQueue($channel);
    $queue->setName($RABBITMQ['queue']);
    $queue->setFlags(AMQP_DURABLE);
    $queue->declareQueue();
    $queue->bind($RABBITMQ['exchange'], $RABBITMQ['queue']);

    echo " Listening for messages on '{$RABBITMQ['queue']}'\n";

    $queue->consume(function (AMQPEnvelope $envelope, AMQPQueue $queue) {
        
        $msgBody = $envelope->getBody();
        $correlationId = $envelope->getCorrelationId();
        $replyTo = $envelope->getReplyTo();

        echo " request = $msgBody\n";

        $payload = json_decode($msgBody, true);
        $searchTerm = $payload['query'] ?? 'margarita'; // Default if missing

      
        $apiUrl = "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=" . urlencode($searchTerm);
        
        echo "Calling External API: $apiUrl\n";

        $apiResult = file_get_contents($apiUrl);

        if ($apiResult === false) {
            $apiResult = json_encode(["status" => "error", "message" => "Failed to reach CocktailDB"]);
        }

   
        
        $channel = $queue->getChannel();
        $replyExchange = new AMQPExchange($channel);
        $replyExchange->setName('');

        $replyExchange->publish(
            $apiResult,
            $replyTo, 
            AMQP_NOPARAM,
            ['correlation_id' => $correlationId]
        );

        echo " Sent response back to '$replyTo'\n";
   
        $queue->ack($envelope->getDeliveryTag());
    });

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
