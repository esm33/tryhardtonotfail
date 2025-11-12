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
    <title><?php echo htmlspecialchars($_SESSION['recipe']); ?> Page</title>
    
    <link rel="stylesheet" href="./style.css">
</head>
<?php include 'navigationbar.php' ?>
<body>
<!-- =====================================================================================-->
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


<!-- =====================================================================================-->
<div class="container">
    <div class="glass-card">
    
        <h1><?php echo htmlspecialchars($_SESSION['recipe']); ?>  Page</h1> 
        <p>Visual: <?php echo htmlspecialchars($_SESSION['image']); ?> </p>
        <p>Drink Name: <?php echo htmlspecialchars($_SESSION['recipe']); ?> </p>
        <p>Drink Type: <?php echo htmlspecialchars($_SESSION['type']); ?> </p>
        <p>Ingredients: <?php echo htmlspecialchars($_SESSION['ingredients']); ?> </p>
        <p>Quantity: <?php echo htmlspecialchars($_SESSION['quantity']); ?> </p>
        <p>Instructions: <?php echo htmlspecialchars($_SESSION['instructions']); ?>
        
    </div>
    
    <div class="login-link"> <!--DON'T FORGET TO ADD CSS FOR THIS LINK, MUST ADD CSS LINES FOR THIS LINK -->
        <a href="add_rating.php">Leave a rating here.</a>
    </div>
    
</div>

</body>
</html>

