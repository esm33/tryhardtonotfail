<?php

	if(!isset($_SESSION))
	{
		session_start();
	}

	if(!isset($_SESSION['successful_login']))
	{
		header("Location: login.php");
		exit(0);
	}

?>
<html>
<head>
	<title>User Profile</title>
	<link rel="stylesheet" href="./style.css">
</head>
<?php include 'navigationbar.php' ?>
<body>
<div class="container">
	<div class="glass-card">
	<h1>Profile Page</h1> 
		<div id="logged-in-page">
		<p>Welcome <?php echo htmlspecialchars($_SESSION['username_profile']); ?> </p>
		<p>You have successfully logged into your profile page</p>	
	<div class="logout-link"><a href="./logout.php"> Logout HERE. </a></div>
	</div>
	</div>
</div>

</body>
</html>
