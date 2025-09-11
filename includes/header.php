<?php
session_start();
if (!isset($_SESSION)) {
    header("Location: index.php");
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
};

include '../includes/koneksi.php';


?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        aside .menu {
            padding: 10px;
            border-radius: 10px;
        }

        aside .menu:hover {
            background-color: #60A5FA1A;
            color: #60A5FA;
        }
    </style>
</head>

<body class="flex">
    <aside class="flex flex-col w-1/6 h-full border-r-1 p-4">
        <div class="profile">
            <h2 class="text-3xl"><?php echo $_SESSION['username']; ?></h2>
            <h4><?php echo $_SESSION['role']; ?></h4>
        </div>
        <a href="dashboard.php" class="menu">Beranda</a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="meja.php" class="menu">Entri Meja</a>
            <a href="menu.php" class="menu">Entri Menu</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'waiter'): ?>
            <a href="menu.php" class="menu">Entri Menu</a>
            <a href="order.php" class="menu">Entri Order</a>
            <a href="laporan.php" class="menu">Laporan</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'kasir'): ?>
            <a href="transaksi.php" class="menu">Entri Transaksi</a>
            <a href="laporan.php" class="menu">Laporan</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'owner'): ?>
            <a href="laporan.php" class="menu">Laporan</a>
        <?php endif; ?>

        <form method="post" class="mt-4">
            <button type="submit" name="logout" class="bg-red-500 w-full py-1 rounded-md text-white text-center">Log Out</button>
        </form>
    </aside>
    <script src="https://kit.fontawesome.com/54328c2d50.js" crossorigin="anonymous"></script>
</body>