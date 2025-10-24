<?php

class loginDB
{
private $logindb;

public function __construct()
{
	$this->logindb = new mysqli("127.0.0.1","thtnf","Tryhardtonotfail123!","IT490");

	if ($this->logindb->connect_errno != 0)
	{
		echo "Error connecting to database: ".$this->logindb->connect_error.PHP_EOL;
		exit(1);
	}
	echo "correctly connected to database".PHP_EOL;
}

public function getConnection()
{
	return $this->loginDB ?? $this->db;
}

public function validateLogin($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	$statement = "select * from users where username = '$un'";
	$response = $this->logindb->query($statement);

	while ($row = $response->fetch_assoc())
	{
		echo "checking password for $username".PHP_EOL;
		if ($row["password"] == $pw)
		{
			echo "passwords match for $username".PHP_EOL;
			return 1;// password match
		}
		echo "passwords did not match for $username".PHP_EOL;
	}
	return 0;//no users matched username
}
public function registerUser($username, $password){
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	echo $un;
	echo $pw;
	$query = $logindb->prepare("Insert into users  (username, password) values ( '$un', '$pw')");
	$statement = $this->logindb->prepare($query);
	//$response = logindb->query($statement);
	if($response->execute())
	{
		echo "Your registration was successful!";
	}
	else {
		"Something went wrong...Please try again!";
		}
	$statement->closeCursor();
	echo "Your insert statement status is $response";
	echo "your registration is sucessful!";
	}
}

?>
