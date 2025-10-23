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
$message = "";
$toastClass = "";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$checkEmailStmt = $connection->prepare("SELECT email FROM users WHERE email = ?");
    	$checkEmailStmt->bind_param("s", $email);
    	$checkEmailStmt->execute();
    	$checkEmailStmt->store_result();
    	
	if ($checkEmailStmt->num_rows > 0) {
        $message = "Email ID already exists";
        $toastClass = "#007bff"; //blue reset color
    } else {
        $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#28a745"; //green success color
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "#dc3545"; // red error color
        }

        $stmt->close();
    }
     $checkEmailStmt->close();
     $connection->close();
}
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

