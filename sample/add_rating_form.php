<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    /*
    if(!isset($_SESSION['successful_login']))
        {
            header("Location: login.php");
            exit(0);
        }    */
        
        
//insert code here to connect to the database & grab the API 


?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add Rating</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body>
        <script>

        function HandleAddRatingFormResponse(response)
        {
            var text = JSON.parse(response);
        //    document.getElementById("textResponse").innerHTML = response+"<p>";    
            document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
            console.log("response:", text);
            if(text.status === "error") 
	    {
		document.getElementById("textResponse").innerHTML = "error: " + text.message+"</p>";
	    }
	    if(text.status === "success" || text === 1 || text === "1") 
	    {
		//document.getElementById("textResponse").innerHTML = "status: " + text.status+"</p>";
		document.getElementById("textResponse").innerHTML = "****Rating is successfully added****";
	    }
	    
        }

        function SendAddRatingFormRequest(rating_value, username, drinkid)
        {
            var request = new XMLHttpRequest();
            request.open("POST","rating_handling_communication.php",true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.onreadystatechange= function ()
            {
                
                if ((this.readyState == 4)&&(this.status == 200))
                {
                    HandleAddRatingFormResponse(this.responseText);
                    console.log("SendAddRatingFormrequest function ready state done.");
                }        
            }
            request.send("type=new_rating&username="+username+"&drinkid="+drinkid+"&review="+rating_value);
            console.log("type new_rating sent HERE");
        }


        function getRatingInfo()
        {
            
            const rating_system_text_input = document.getElementById("rating_system");
            const rating_system_input_value = rating_system_text_input.value;
            
            const username_text_input = document.getElementById("username");
            const username_input_value = username_text_input.value;
            
            const drinkid_text_input = document.getElementById("drinkid");
            const drinkid_input_value = drinkid_text_input.value;
            
	
            
            console.log("Rating Value: ", rating_system_input_value);
            console.log("Username Value: ", username_input_value);
            console.log("DrinkID Value: ", drinkid_input_value);
            
            SendAddRatingFormRequest(rating_system_input_value, username_input_value, drinkid_input_value);
            console.log("sendAddRatingFormrequest done");
        }

        </script>
    </body>
    
    
    <?php include 'navigationbar.php'; ?>
    
    <body>
    <div class="container">
        <div class="glass-card">
            <h1> Add Rating</h1>
            <form id="add_rating_form">
                <div class="input-group">
                    <label>User@<?php echo htmlspecialchars($_SESSION['username_profile']); ?></label>
                    <input type="hidden" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username_profile']); ?>" />
                    
                    <label for="drinkid">DrinkID: </label>
                    <input type="text" id="drinkid" name="drinkid" rows="4" cols="50" required />
        
        
                    <label for="rating_system"> Review: </label>
                    <input type="text" id="rating_system" name="review" rows="4" cols="50" required />
               
                </div>
                
                    
                
                <button type="button" onclick="getRatingInfo()" class="btn">Submit Rating</button>
                
            </form>
            
            	<div id="textResponse">
            	
            	</div>
		    <div class="login-link"> <!--DON'T FORGET TO ADD CSS FOR THIS LINK, MUST ADD CSS LINES FOR THIS LINK -->
		    <a href="homecatalog.php">View Drink Catalog</a>
		    </div>
        </div>
    </body>
    
</html>

