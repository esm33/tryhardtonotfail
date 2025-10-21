
<?php>

//pings this if can't connect to the db
if ($conn -> errno != 0)
{echo "failed to connect to database: ". $conn->error . PHP_EOL; 
exit (0);}
//display this if it can connect to the db
echo "successfully connected to the database";

if (!isset($_POST))
{
	$msg = "NO POST MESSAGE SET, POLITELY FUCK OFF";
	echo json_encode($msg);
	exit(0);
		
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
			$response = "login, yeah we can do that";
		}
		else 
		{
			//else, set the response message to fail
			$response = "login failed, yeah we can't do that";
		}

		//update this to pull user info from the db




	break;
}
echo json_encode($response);
exit(0);

?>
