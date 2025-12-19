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

<script>
function HandleRecipeBookResponse(response)
{
    console.log("response console log:", response);
    var text = JSON.parse(response);
    if(text.status === "error") 
    {
        document.getElementById("textResponse").innerHTML = "error: " + text.message+"</p>";
    }
    else if(text.recipes !=null)
	{
    	
	document.getElementById("textResponse").innerHTML = ""; //to clear error messages or previous responses when you do another search  

	for(var i=0; i < text.recipes.length; i++)
	{
	
		document.getElementById("textResponse").innerHTML += "<div class='glass-card'><h2>" + text.recipes[i].rname +"</h2><p><strong>Type: </strong>"+ text.recipes[i].dtype +"</p><p><strong>Ingredients: </strong>"+ text.recipes[i].d_ingredients + "</p><p><strong> Instructions: </strong>" + text.recipes[i].d_instructions+"</div><br>";
		
		//<div class="glass-card">
		//<h2>text.recipes[i].rname</h2>
		//<p><strong>Type: </strong>text.recipes[i].dtype </p>
		//<p><strong>Ingredients: </strong>text.recipes[i].d_ingredients </p>
		//<p><strong>Instructions: </strong>text.recipes[i].d_instructions</p>
		//</div>
		
	}
	
    }
    else
    {
    	document.getElementById("textResponse").innerHTML = "nothing<p>";
    }
}
function SendRecipeBookRequest()
{
	 var request = new XMLHttpRequest();
    request.open("POST","./show_recipe_handling_communication.php",true);
    request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    request.onreadystatechange= function ()
    {
        if ((this.readyState == 4)&&(this.status == 200))
        {
            HandleRecipeBookResponse(this.responseText);
            console.log("ready state working");
        }        
    }
    request.send("type=get_recipes");
}
function getRecipeBookInfo()
{
	SendRecipeBookRequest();
	console.log("calling SendRecipeBookRequest");
}

window.addEventListener('load', function(){
	getRecipeBookInfo();
	console.log("getting recipe book");
});
</script>

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
    <br>
    <br>
    <div class="glass-card scrollable-container">
    	<h1>Community Recipe Book</h1>
    	<div id="textResponse"> 
    		**** No Recipes Here....Yet ****
    	</div>
    </div>
</div> <!--FYI END OF CONTAINER -->

</body>
</html>

