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
  
  	//require_once('../APIcalls.php');
  	//$drinks = getRandom(); //this will set the received array to a variable array now called drinks
  	
  	
?>
<!DOCTYPE html>
<html>
<script>
function HandleSearchResponse(response)
{
    console.log("response:", response);
    var text = JSON.parse(response);
//    document.getElementById("textResponse").innerHTML = response+"<p>";    
  //  document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
    if(text.status === "error") 
    {
        document.getElementById("textResponse").innerHTML = "error: " + text.message+"</p>";
    }
    else if(text.drinks !=null)
	{
    	
	document.getElementById("textResponse").innerHTML = response+"<p>";   
	//go through drinks array and print out drink
	//to see all variables: https://www.thecocktaildb.com/api/json/v1/1/search.php?s=margarita
	for(var i=0; i < text.drinks.length; i++)
	{
	//in the overall drinks array, get the strDrink value of the drink[i]
		document.getElementById("textResponse").innerHTML += "<p>" + text.drinks[i].strDrink+"</p>";
		//idDrink, strDrink, strAlcoholic, strInstructions
		//parse out bad language
	}
	
    }
    else
    {
    	document.getElementById("textResponse").innerHTML = "nothing<p>";
    }

}

function SendSearchRequest(search_query)
{
    var request = new XMLHttpRequest();
    request.open("POST","./api_search.php",true);
    request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    request.onreadystatechange= function ()
    {
        if ((this.readyState == 4)&&(this.status == 200))
        {
            HandleSearchResponse(this.responseText);
        }        
    }
    request.send("search="+search_query);
}

function getSearchInfo()
{
    alert("search button clicked!");
    const search_text_input = document.getElementById("search-bar");
    const search_input_value = search_text_input.value;
    
    console.log("Search: ", search_input_value);
    SendSearchRequest(search_input_value);
}



</script>
    <head>
        <title>Recipe Catalog</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <?php include 'navigationbar.php'; ?>
    
    <div class="container">
        <div class="glass-card">
        <h1> Recipe List Catalog</h1>
      

    <form>
        <label for="search-input">Search</label>
        <input type="search" id="search-bar" name="s" placeholder="Find your next drink">
        <br>
        <br>
        <button type="button" onclick="getSearchInfo()" class="btn"> Enter </button>
        
        <div id="textResponse">
        
        </div>
    </form>
    
      </div>
    </div>
<body>
	

</body>
</html>

