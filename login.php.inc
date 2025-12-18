<?php

class loginDB
{
private $logindb;

public function __construct()
{
	$this->logindb = new mysqli("localhost",'thtnf',"Tryhardtonotfail123!","IT490");

	if ($this->logindb->connect_errno != 0)
	{
		echo "Error connecting to database: ".$this->logindb->connect_error.PHP_EOL;
		exit(1);
	}
	echo "correctly connected to database".PHP_EOL;
}

public function getConnection()
{
	return $this->logindb;
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
        $hashed_password = password_hash($pw, PASSWORD_DEFAULT);
		if ($row["password"] == $pw && password_verify($pw, $hashed_password))
		{
			echo "passwords match for $username".PHP_EOL;
			return 1;// password match
		}
		echo "passwords did not match for $username".PHP_EOL;
	}
	return 0;//no users matched username
}
public function registerUser($username, $password){
	//echo $un;
	//echo $pw;
	//echo $logindb;
	$conn = $this->GetConnection();
	$query = 'Insert into users (username, password) values (?, ?)';
	$statement = $conn->prepare($query);
	$statement->bind_param("ss", $username, $password);
	
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	//$statement = $this->logindb->prepare($query);
	//$response = logindb->query($statement);
	
	if($statement->execute())
	{
		echo "Your registration was successful!";
		return 1;
	}
	else {
		"Something went wrong...Please try again!";
		return 0;
		}
	$statement->close();
	echo "your registration is sucessful!";
	}

public function writeRecipe ($cdrink_name, $cdrink_type, $cdrink_ingredients, $cdrink_instructions){

	$customconn = $this->GetConnection();
	$query = 'Insert into customdrink (cdrink_name, cdrink_ingredients, cdrink_instructions, $cdrink_type) values (?, ?, ?, ?)';
	$statement = $customconn->prepare($query);
	$statement->bind_param("sss", $cdrink_name, $cdrink_ingredients, $cdrink_instructions, $cdrink_type);
	
	$cdn = $this->logindb->real_escape_string($cdrink_name);
	$cdi = $this->logindb->real_escape_string($cdrink_ingredients);
	$cdin = $this->logindb->real_escape_string($cdrink_instructions);
	$cdt = $this->logindb->real_escpae_string($cdrink_type);
	
	if($statement->execute())
	{
		echo "You've added a custom drink!";
		return 1;
	}
	else {
		"Something went wrong...Please try again!";
		return 0;
		}
	$statement->close();
	echo "The custom drink was logged.";
	}


public function addRatings ($id, $ratingvalue) {

	$ratingconn = $this->GetConnection();
	$query = 'Insert into ratings (id, ratingvalue) values (?, ?)';
	$statement = $ratingconn->prepare($query);
	$statement->bind_param("is", $id, $ratingvalue);
	
	$rv = $this->logindb->real_escape_string($ratingvalue);
	
	if($statement->execute())
	{
		echo "Thanks for your review!";
		return 1;
	}
	else {
		"Something went wrong...Please try again!";
		return 0;
		}
	$statement->close();
	echo "The rating was logged.";
	}
}



?>
