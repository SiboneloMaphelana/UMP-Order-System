<?php
session_start();
if (!isset($_SESSION['id'])) {
    $_SESSION['message'] = "You need to login first";
    $_SESSION["redirect_url"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit();
}
?>