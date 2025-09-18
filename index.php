<?php
session_start();
include 'config/auth.php';
include 'includes/functions.php';
isLogin();

include 'config/koneksi.php';
include 'includes/header.php';
include 'admin/router.php';

?>
<link rel="stylesheet" href="globals.css">
