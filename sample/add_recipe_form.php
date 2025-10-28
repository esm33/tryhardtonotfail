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
		<title>Add Recipe</title>
		<link rel="stylesheet" href="./style.css">
	</head>
	<?php include 'navigationbar.php'; ?>
	
	<div>
	<div class="container">
		<div class="glass-card">
			<h1> Add Recipe</h1>
			<form action="" method="post" id="add_recipe_form">
				<label>Drink Name</label>
				<input type="text" name="drinkname" required><br>
				
				<label>Drink Type</label>
				<input type="text" name="drinktype" required><br>
				
				<label>Ingredient and Quantity</label>
				<input type="text" name="drinkingredient" required><br>
				
				<label></label>
				<input type="submit" value="Add Recipe"><br>
			</form>
			<p><a href="homecatalog.php">View Drink Catalog</a></p>
		</div>
	</div>
	
<body>

</body>
</html>
