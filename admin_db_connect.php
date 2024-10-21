<?php
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";  // Leave it blank if no password is set for MySQL
$dbname = "login";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
