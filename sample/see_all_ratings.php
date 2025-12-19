<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    
    
    if(!isset($_SESSION['successful_login']))
        {
            header("Location: login.php");
            exit;
        }
  
  	
?>
<!DOCTYPE html>
<html>
<script>
function HandleSeeAllRatingsResponse(response)
{
    console.log("response console log:", response);
    var text = JSON.parse(response);
//    document.getElementById("textResponse").innerHTML = response+"<p>";    
  //  document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
    if(text.status === "error") 
    {
        document.getElementById("textResponse").innerHTML = "error: " + text.message+"</p>";
    }
    if(text.status === "success")
	{
	document.getElementById("textResponse").innerHTML = ""; //to clear error messages or previous responses when you do another search  
	//go through drinks array and print out drink
	//to see all variables: https://www.thecocktaildb.com/api/json/v1/1/search.php?s=margarita
	for(var i=0; i < text.ratings.length; i++)
	{
		//now we look at the overall ratings array and get each position in the array 
		//username, drinkid, rating
		//parse out bad language
		
		document.getElementById("textResponse").innerHTML += "<div class='glass-card'><h2>" + text.ratings[i].username +"</h2><p><strong>DrinkID: </strong>"+ text.ratings[i].drinkid +"</p><p><strong>Rating: </strong>" + text.ratings[i].rating+"</div><br>";
		
		//<div class="glass-card">
		//<h2>text.ratings[i].username</h2>
		//<p><strong>DrinkID: </strong>text.ratings[i].drinkid </p>
		//<p><strong>Rating: </strong>text.ratings[i].rating </p>
		//</div>
		
	}
	
    }
    else
    {
    	document.getElementById("textResponse").innerHTML = "****no ratings available****<p>";
    }

}

function SendSeeAllRatingsRequest()
{
    var request = new XMLHttpRequest();
    request.open("POST","./see_all_ratings_communication.php",true);
    request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    request.onreadystatechange= function ()
    {
        if ((this.readyState == 4)&&(this.status == 200))
        {
            HandleSearchResponse(this.responseText);
        }        
    }
    request.send("type=list_ratings");
}

function getSeeAllRatingsInfo()
{
    //alert("search button clicked!");
 //   const search_text_input = document.getElementById("search-bar");
  //  const search_input_value = search_text_input.value;
    
  //  console.log("Search: ", search_input_value);
  //returns recipes array
    SendSeeAllRatingsRequest();
}



</script>
    <head>
        <title>Recipe Catalog</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <?php include 'navigationbar.php'; ?>
    
    <div class="container">
        <div class="glass-card">
        	<h1> All Ratings</h1>
    	</div>
    
	     <br>
	     <br>
        <div class="glass-card scrollable-container">
	    <div id="textResponse">
			****Reviews here****
	    </div>
        </div>

    </div>
<body>



</body>
</html>

