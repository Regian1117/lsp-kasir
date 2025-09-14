<?php
function isLogin() {
    if(!isset($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
}

function checkRole($allowedRoles = []) {
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: dashboard.php");
        exit();
    }
}