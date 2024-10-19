<?php
// Database configuration
$host = 'localhost';
$username = 'root'; // Your MySQL username, typically 'root' for local setups
$password = ''; // Your MySQL password, usually empty for 'root' on WAMP
$database = 'photogallery'; // Your actual database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

?>