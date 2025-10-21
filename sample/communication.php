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
$this->registerdb = new mysqli("127.0.0.1","root","12345","IT490");
if ($this->registerdb->connect_errno != 0)
{
        echo "Error conneceting to database: ".$this->registerdb->connect_error.PHP_EOL;
        exit(1);
}
 echo "correctly connected to database".PHP_EOL;
}

if (empty($_POST))
{
	$msg = "NO POST MESSAGE SET, POLITELY FUCK OFF";
	echo json_encode($msg);
	exit(0);
}
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
