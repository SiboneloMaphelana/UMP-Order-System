<?php
include_once '../../connection/connection.php';
include_once("Food.php"); 

$foodItems = new Food($conn);

// Check if the search term is set
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $results = $foodItems->searchFoodItems($searchTerm);
    echo json_encode($results);
} else {
    $results = [];
}
?>