<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_ordering";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM foods WHERE name LIKE ? OR category LIKE ?");
    $searchQuery = "%" . $query . "%";
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();

    // Get the results
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results for '$query':</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<p>Price: $" . $row['price'] . "</p>";
            echo "<p>Category: " . $row['category'] . "</p>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '$query'</p>";
    }

    $stmt->close();
} else {
    echo "<p>Please enter a search query.</p>";
}

$conn->close();
?>
