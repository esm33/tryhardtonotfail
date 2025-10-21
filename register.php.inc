<?php

class registerDB{
private $registerdb;

public funstion __construct()
{
$this->registerdb = new mysqli("127.0.0.1","root","12345","IT490");
if ($this->registerdb->connect_errno != 0)
{
	echo "Error conneceting to database: ".$this->registerdb->connect_error.PHP_EOL;
	exit(1);
}
 echo "correctly connected to database".PHP_EOL;
}
public function registerUser($username, $password){
	$un = $this->registerdb->real_escape_string($username);
	$pw = $this->registerdb->real_escape_string($password);
	$statement = "Insert into users  (username, password) values ( $un, $pw)";
	$reponse = $this->registerdb->query($statement);
	if ( $response->execute();){
	echo "your registration is sucessful!"
	}
	else {
	echo "something went wrong please try again!"}
}
}


?>

