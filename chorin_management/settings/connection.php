<?php

// Declare constants for database connection parameters
define('DB_SERVER', 'localhost'); // Change this to your database server hostname
define('DB_USERNAME', 'root'); // Change this to your database username
define('DB_PASSWORD', ''); // Change this to your database password
define('DB_NAME', 'ChorIn'); // Change this to your database name

// Attempt to establish a connection to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, display error message and terminate script
    die("Connection failed: " . $conn->connect_error);
}



?>


