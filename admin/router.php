<?php
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

$pages = [
    'dashboard' => ['admin', 'waiter', 'kasir', 'owner'],
    'meja' => ['admin'],
    'menu' => ['admin', 'waiter'],
    'pesanan' => ['waiter'],
    'transaksi' => ['kasir'],
    'laporan' => ['waiter', 'kasir', 'owner'],
];

if (array_key_exists($page, $pages)) {
    checkRole($pages[$page]);

    $filePath = "admin/{$page}/{$action}.php";

    if (file_exists($filePath)) {
        include $filePath;
    } else {
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
};
