<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Define database connection details
$localhost = 'localhost';
$username = 'root';
$password = ''; 
$databaseName = 'test';

// Enable MySQLi exception handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  // Create a new mysqli connection object with error handling
  $conn = new mysqli($localhost, $username, $password, $databaseName);

} catch (mysqli_sql_exception $e) {
  // Log the error with more details
  error_log("Database connection failed: " . $e->getMessage() . 
            "\n (Error Code: " . $e->getCode() . ")");

  // Display a user-friendly message without revealing sensitive information
  echo "We are experiencing technical difficulties. Please try again later.";
  
  // Exit the script to prevent further execution
  exit();
}
?>

