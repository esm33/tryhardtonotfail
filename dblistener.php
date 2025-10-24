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
		$success = $loginDB->validateLogin($payload['username'], $payload['password']);
		if ($success) { ["status"=>"success","message"=>"login.successful"]; }
		else {["status"=>"fail","message"=>"login failed"];}
	break;    
	case "registration":
			$type = "registration";
			console.log ("this is case registration.");

		$un = $request["uname"];
		$pw = $request["pword"];	
		


        $un = $this->registerdb->real_escape_string($username);
        $pw = $this->registerdb->real_escape_string($password);
        $statement = $connDB->prepare("Insert into users  (username, password) values ( $un, $pw)");
        $reponse = $this->registerdb->query($statement);
        if ( $response->execute()){
        echo "your registration is sucessful!";}
        else {
        echo "something went wrong please try again!";}
        break;
      
    
    }
    }
    	$queue->ack($message->getDeliveryTag());
});

}
 
    
   

?>

