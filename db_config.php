<?php
$servername = "localhost"; // database server address
$username = "root"; //database username
$password = "5556401"; // database password
$dbname = "mini_project"; // database name
// Create a connection to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
