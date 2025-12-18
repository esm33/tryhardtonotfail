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
  
        
//insert code here to connect to the database & grab the API 
?>
<!DOCTYPE html>
<html>
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
        <button type="button"> Enter </button>
    </form>
      </div>
    </div>
<body>

</body>
</html>

