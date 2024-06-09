<?php
//session is started only once
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define constants only if they are not already defined
if (!defined('LOCALHOST')) {
    define('LOCALHOST', 'localhost');
}
if (!defined('USERNAME')) {
    define('USERNAME', 'root');
}
if (!defined('PASSWORD')) {
    define('PASSWORD', '');
}
if (!defined('DATABASE_NAME')) {
    define('DATABASE_NAME', 'test');
}

// Database connection
$conn = mysqli_connect(LOCALHOST, USERNAME, PASSWORD) or die(mysqli_error($conn));  //DATABASE CONNECTION
$db = mysqli_select_db($conn, DATABASE_NAME) or die(mysqli_error($conn));   //SELECTING DATABASE
?>
