<?php
require_once('login.php.inc');
//hi 
//updated case
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
       $request = $_POST;
    if(isset($data["type"])){  
    echo "type received";
   
    $loginDB = new loginDB();
    $connDB = $loginDB->getConnection();
    switch ($data['type']){
  	case "login":
		$type = "login";
		//get the username value and the password value
		echo ("this is case login.");
		$success = $loginDB->validateLogin($data['username'], $data['password']);
		if ($success) { ["status"=>"success","message"=>"login.successful"]; }
		else {["status"=>"fail","message"=>"login failed"];}
	break;    
	case "registration":
			$type = "registration";
			echo ("this is case registration.");
$success = $loginDB->registerUser($data['username'], $data['password']);
		if ($success) { ["status"=>"success","message"=>"registration successful"]; }
		else {["status"=>"fail","message"=>"registration failed"];}
		
        break;
        case "new_rating":
			$type = "new_rating";
			echo ("this is case new rating.");
$success = $loginDB->addRatings($data['username'], $data['rvalue']);
		if ($success) { ["status"=>"success","message"=>"rating integer added successfully"]; }
		else {["status"=>"fail","message"=>"rating interger failed"];}
		
        break;
       case "new_Recipe":
			$type = "new_recipe";
			echo ("this is case new rating.");
$success = $loginDB->writeRecipe($data['username'], $data['rvalue']);
		if ($success) { ["status"=>"success","message"=>"rating integer added successfully"]; }
		else {["status"=>"fail","message"=>"rating interger failed"];}
		
        break;
    
    }
    }
    	$queue->ack($message->getDeliveryTag());
});

}
 
    
   

?>

