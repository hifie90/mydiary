
<?php
// Database connection details
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$database = '23189636'; 

// Create a connection
$con = mysqli_connect($host, $user, $password, $database);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
$con->set_charset("utf8mb4");
?>