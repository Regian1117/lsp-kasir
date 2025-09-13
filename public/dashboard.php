<?php
include '../includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

?>

ini dasbordod