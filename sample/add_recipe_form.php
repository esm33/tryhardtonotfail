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
        }  
        */

?>
<!DOCTYPE html> 
<html>
    <head>
        <title>Add Recipe</title>
        <link rel="stylesheet" href="./style.css">
        
        
<!-- ================================================================================================================ -->    

    <script type="text/javascript">

        function HandleAddRecipeFormResponse(response)
        {
            var text = JSON.parse(response);
        //    document.getElementById("textResponse").innerHTML = response+"<p>";    
            document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
            console.log("response:", text);
        }

        function SendAddRecipeFormRequest(recipe_name,drink_type,drink_ingredients, drink_instructions)
        {
            var request = new XMLHttpRequest();
            request.open("POST","recipe_handling_communication.php",true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.onreadystatechange= function ()
            {
                
                if ((this.readyState == 4)&&(this.status == 200))
                {
                    HandleAddRecipeFormResponse(this.responseText);
                    console.log("SendAddRecipeFormrequest function ready state done.");
                }        
            }
            //FOCUS HERE
            request.send("type=new_recipe&rname="+recipe_name+"&dtype="+drink_type+"&d_ingredient="+drink_ingredients+"&d_instructions="+drink_instructions);
            console.log("type new_recipe sent HERE");
        }

    function getRecipeInfo()
        {
            const recipe_name_text_input = document.getElementById("recipe_name");
            const recipe_name_input_value = recipe_name_text_input.value;

            const drink_type_text_input = document.getElementById("drink_type");
            const drink_type_input_value = drink_type_text_input.value;

        
            const drink_ingredient_text_input = document.getElementById("drink_ingredient");
            const drink_ingredient_input_value = drink_ingredient_text_input.value;
            
                

            const drink_instructions_text_input = document.getElementById("drink_instructions");
            const drink_instructions_input_value = drink_instructions_text_input.value;


            console.log("Recipe Name: ", recipe_name_input_value);
            console.log("Drink Type: ", drink_type_input_value);
            console.log("Drink Ingredient: ", drink_ingredient_input_value);
            console.log("Drink Instructions: ", drink_instructions_input_value);

            SendAddRecipeFormRequest(recipe_name_input_value, drink_type_input_value, drink_ingredient_input_value, drink_instructions_input_value);
            console.log("sendAddRecipeFormrequest done");
            }
    
        
<!-- ================================================================================================================ -->
        </script>
    </head>
    <body>
    
    <?php include 'navigationbar.php'; ?>
    
    <div class="container">
        <div class="glass-card">
            <h1> Add Recipe</h1>
            <form id="add_recipe_form">
                <div class="input-group">
                    <label for="recipe_name">Recipe Name: </label>
                    <input type="text" id="recipe_name" name="rname" required />
                </div>
                
                <div class="input-group">
                    <label for="drink_type">Drink Type</label>
                    <input type="text" id="drink_type" name="dtype" value="Alcoholic or Non-Alcoholic" required/><br>
                </div>
                
                <div class="input-group">
                    <label for="drink_ingredient">Ingredient</label>
                    <input type="text" id="drink_ingredient" name="d_ingredient" required/><br>
                </div>
                    
            
                <div class="input-group">
                    <label for="drink_instructions">Instructions</label>
                    <input type="text" id="drink_instructions" name="d_instructions" required/><br>
                </div>
                    
                <button type="button" onclick="getRecipeInfo()" class="btn">Submit New Recipe</button>
                
            </form>
            
                <div class="login-link"> <!--DON'T FORGET TO ADD CSS FOR THIS LINK, MUST ADD CSS LINES FOR THIS LINK -->
                <a href="homecatalog.php">View Drink Catalog</a>
                </div>
        </div>
    </div>
    </body>
    
    
</html>

