<?php

session_start();

if(isset($_SESSION['successful_login']))
{
	header("Location: profile.php");
}
?>

<html>
<script>

function HandleLoginResponse(response)
{
	console.log("response:", response);
	var text = JSON.parse(response);
//	document.getElementById("textResponse").innerHTML = response+"<p>";	
	document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
	if(json.status === "success")
	{
		window.location.href = "profile.php";
	}

}

function SendLoginRequest(username,password)
{
	var request = new XMLHttpRequest();
	request.open("POST","communication.php",true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange= function ()
	{
		if ((this.readyState == 4)&&(this.status == 200))
		{
			HandleLoginResponse(this.responseText);
		}		
	}
	request.send("type=login&uname="+username+"&pword="+password);
}

function getLoginInfo()
{
	alert("login button clicked!");
	const username_text_input = document.getElementById("username");
	const username_input_value = username_text_input.value;
	
	const password_text_input = document.getElementById("password");
	const password_input_value = password_text_input.value;

	console.log("Username: ", username_input_value);
	console.log("Password: ", password_input_value);

	SendLoginRequest(username_input_value, password_input_value);
}


</script>
<head>
	<link rel="stylesheet" href="./style.css">
</head>

<body>
<div class="container">
	<div class="glass-card">

	<h1>Login Form</h1>
		<form id="loginForm">		
			<div class="input-group">
				<label for="username">Username: </label>
				<input type="text" id="username" name="username" placeholder="Enter your username" required />
			</div>
						
			<div class="input-group">
				<label for="password">Password: </label>
				<input type="password" id="password" name="password" placeholder="Enter your password" required />
			</div>

			<br>
			<button type="button" onclick="getLoginInfo()" class="btn">Login</button>
			
			<div class="register-link">
				Don't have an account? <a href="./registration.html">Sign up</a>
			</div>

			<div id="textResponse">
				
			</div>
		</form>
	</div>
</div>
<!--
<div id="textResponse">
awaiting response
</div>
-->
<script>
//SendLoginRequest("kehoed","12345");
</script>
</body>
</html>
