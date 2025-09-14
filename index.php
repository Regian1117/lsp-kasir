<?php
session_start();
include 'config/auth.php';
isLogin();

include 'config/koneksi.php';
include 'includes/header.php';
include 'admin/router.php';

?>
