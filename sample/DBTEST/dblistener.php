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
    $request = $_POST;
$response = "unsupported request type, politely FUCK OFF";
    switch {

		case "registration":
			$type = "registration";

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
        break;


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

        if ( $statement2->execute();){
        echo "fetching your data is sucessful!"
        }
        else {
        echo "something went wrong please try again!"}

		//if statements to check for specific credentials 
		if($usr == $managers['username'];&& $pwd == $managers['password'];)
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
}}
?>

