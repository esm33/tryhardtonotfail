<?php
require_once('login.php.inc');

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
       $request = $_POST;
    if(isset($payload['type'])){  
    $loginDB = new loginDB();
    $connDB = $loginDB->getConnection();
    switch ($payload['type']){
    
    
    
    

	case "login":
		$type = "login";
		//get the username value and the password value
		$usr = $request["uname"];
		$pwd = $request["pword"];


	$queryAllCategories = 'SELECT username,password FROM users
                        WHERE username = :usr';
$statement2 = $db->prepare($queryAllCategories);
$statement2 ->bindValue(':emailAddress', $emailAddress);
$statement2->execute();
	$managers = $statement2->fetch();

        if ( $statement2->execute()) {
        echo "fetching your data is sucessful!" ; }
        else {
        echo "something went wrong please try again!";}

		//if statements to check for specific credentials 
		if($usr == $managers['username'] && $pwd == $managers['password'])
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
    
  
    }
    
    }
    

        $queue->ack($message->getDeliveryTag());
    });
    
   } 

?>

