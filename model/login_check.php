<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    $_SESSION['message'] = "<div class='alert alert-danger'>You need to login first.</div>";
    header("Location: login.php");
    exit();
}
