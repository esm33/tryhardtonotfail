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
		<title>Discussion Forum</title>
		<link rel="stylesheet" href="./style.css">
	</head>
	<?php include 'navigationbar.php';>
	<body>
	<main>
	<div class="container">
		<div class="glass-card">
			<h1> Add Commentary</h1>
			<form action="" method="post" id="add_comment_form">
				<label>Comment</label>
				<input type="text" name="user_comment" required><br>
	
				<label></label>
				<input type="submit" value="Add Recipe"><br>
			</form>
			<p><a href="homecatalog.php">View Drink Catalog</a></p>
		</div>
	</main>
	
	<section>
		<?php if count($comments) == 0: ?>
			<div class="glass-card">
			<h2>There are no comments yet.</h2>
			</div>
		<?php else: ?>
		<div class="glass-card">
			<?php foreach ($comments as $comment): ?>
				<p><?php echo $comment->getComment(); ?></p>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</section>
	</body>
</html>
