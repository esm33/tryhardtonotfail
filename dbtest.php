<?php
$conn = new mysqli("127.0.0.1", "root", "12345", "login");
if($conn->connect_errno) {
	echo "failed to connect " . $conn->connect_error;
} else {
	echo "connected";
	}
?>
