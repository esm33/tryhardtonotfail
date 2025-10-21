<?php

	if(!isset($_SESSION))
	{
		session_start();
	}

	if(!isset($_SESSION['successful_login']))
	{
		header("Location: login.html");
		exit(0);
	}

?>
<html>
<head>
	<title>User Profile</title>
	<link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container">
	<div class="glass-card">
	<h1>Profile Page</h1> 
		<div id="logged-in-page">
		<p>Welcome <?php ?>! </p>
		<div class="logout-link"><a href="./logout.php"> Logout HERE. </a></div>
	</div>
	</div>
</div>

</body>
</html>
