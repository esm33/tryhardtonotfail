<?php

session_start();

/*
//pings this if can't connect to the db
if($conn -> errno != 0)
{echo "failed to connect to database: ". $conn->error . PHP_EOL;
exit(0);
//display this if it can connect to the db
echo "successfully connected to the database";

 */
{


<?php
// communication.php
// Handles sending registration/login messages to RabbitMQ and waits for DB VM response

header('Content-Type: application/json');

// Basic POST validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo json_encode(["status" => "error", "message" => "No POST data received"]);
    exit;
}

$type  = $_POST['type']  ?? null;
$uname = $_POST['uname'] ?? null;
$pword = $_POST['pword'] ?? null;

if (!$type || !$uname || !$pword) {
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
    // Connect to RabbitMQ
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
    $exchange->declareExchange();

    // Create or declare a response queue (unique per client)
    $callbackQueue = new AMQPQueue($channel);
    $callbackQueue->setName($RABBITMQ['queue'] . '_response');
    $callbackQueue->declareQueue(); // no bind needed

    $corrId = uniqid();

    $message = [
        "type"  => $type,
        "uname" => $uname,
        "pword" => $pword
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

    echo json_encode($response);

    $conn->disconnect();

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
}
?>















$request = $_POST;
$response = "unsupported request type, politely FUCK OFF";

switch ($request["type"])
{
	case "login":
		//get the username value and the password value
		$usr = $request["uname"];
		$pwd = $request["pword"];

		//if statements to check for specific credentials 
		if($usr == "kehoed" && $pwd == "12345")
		{
			//if the username value and password value math
			//then set the response message to success
			$response = "success";
			$_SESSION['successful_login'] = true;
			$_SESSION['username_profile'] = $usr;
		}
		else 
		{
			//else, set the response message to fail
			$response = "fail";
		}
	break;

	case "registration":








		$un = $request["uname"];
		$pw = $request["pword"];

{
        $un = $this->registerdb->real_escape_string($username);
        $pw = $this->registerdb->real_escape_string($password);
        $statement = "Insert into users  (username, password) values ( $un, $pw)"
        $reponse = $this->registerdb->query($statement);
        if ( $response->execute();){
        echo "your registration is sucessful!"
        }
        else {
        echo "something went wrong please try again!"}
}
}



		}
	break;

}
echo json_encode($response);
exit(0);

?>
