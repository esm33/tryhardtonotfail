<?php
	if(!isset($_SESSION))
	{
		session_start();
	}
	
//don't forget to readd isset($_SESSION["successful_login"])
		
//insert code here to connect to the database & grab the API 

//query that selects all the drink names from the db and stores them in an array to be accessed for creating dropdown menu

?>

<!DOCTYPE html>
<html>
<head>
	<title>Discussion Forum</title>
	<link rel="stylesheet" href="./style.css">
</head>
<body>
	<?php include 'navigationbar.php'; ?>
	<main>
		<div class="container">
		<div class="glass-card">
			<h1> Add Commentary</h1>
			<form action="" method="post" id="add_comment_form">
				<select class="comment_dropdown_drinks">
					<?php foreach ($recipe as $r):?>
					<option value="<?php echo htmlspecialchars($r); ?>">
					<?php echo htmlspecialchars($r); ?>
					</option>
					<?php endforeach; ?>
				<select>
			
				<label>Comment</label>
				<input type="text" name="user_comment" required><br>
	
				<label></label>
				<input type="submit" value="Add Recipe"><br>
			</form>
			<p><a href="homecatalog.php">View Drink Catalog</a></p>
		</div>
	</main>
	<!--
	<section>
		<?php if (count($comments)) === 0: ?>
			<div class="glass-card">
			<h2>There are no comments yet.</h2>
			</div>
		<?php else: ?>
			<div class="glass-card">
				<?php foreach ($comments as $comment): ?>
				<h1>From: </h1>
				<h2>Drink: </h2>
				<p>Comment:<br><?php echo $comment->getComment(); ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>
	-->
</body>
</html>
