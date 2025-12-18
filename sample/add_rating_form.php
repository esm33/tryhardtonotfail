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
        }

        function SendAddRatingFormRequest(rating_value)
        {
            var request = new XMLHttpRequest();
            request.open("POST","communication.php",true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.onreadystatechange= function ()
            {
                
                if ((this.readyState == 4)&&(this.status == 200))
                {
                    HandleAddRatingFormResponse(this.responseText);
                    console.log("SendAddRatingFormrequest function ready state done.");
                }        
            }
            request.send("type=new_rating&rvalue="+rating_value);
            console.log("type new_rating sent HERE");
        }


        function getRatingInfo()
        {
            
            const rating_system_text_input = document.getElementById("rating_system");
            const rating_system_input_value = rating_system_text_input.value;

            
            console.log("Rating Value: ", rating_system_input_value);
            
            SendAddRatingFormRequest(rating_system_input_value);
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
                    <label for="rating_system"> Rating (1 (lowest) - 5 (highest)): </label>
                    <input type="number" id="rating_system" name="rvalue" min="1" max="5" required />
                </div>
                
                <button type="button" onclick="getRatingInfo()" class="btn">Submit Rating</button>
                
            </form>
            
            <div class="login-link"> <!--DON'T FORGET TO ADD CSS FOR THIS LINK, MUST ADD CSS LINES FOR THIS LINK -->
            <a href="homecatalog.php">View Drink Catalog</a>
            </div>
        </div>
    </body>
    
</html>

