<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

//  database connection details
$localhost = 'localhost';
$username = 'root';
$password = ''; 
$databaseName = 'test';

// Enable MySQLi exception handling

try {

  $conn = new mysqli($localhost, $username, $password, $databaseName);

} catch (mysqli_sql_exception $e) {
  // Log the error with more details
  error_log("Database connection failed: " . $e->getMessage() . 
            "\n (Error Code: " . $e->getCode() . ")");

  echo "We are experiencing technical difficulties. Please try again later.";
  
  // Exit the file to prevent further execution
  exit();
}
?>

