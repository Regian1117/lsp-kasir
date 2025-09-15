<?php

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
};

include 'config/koneksi.php';

?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.1);
        }

        .logo {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .menu {
            position: relative;
            border-radius: 10000px;
            font-weight: 500;
            color: #64748b;
            overflow: hidden;
            display: flex;
            align-items: center;
        }


        .menu:hover {
            color: #3b82f6;
            background-color: #3b82f610;
        }


        .logout-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .menu-container {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 8px;
        }
    </style>
</head>

<nav class="navbar mt-3 flex items-center justify-between px-8 py-2 rounded-full mx-auto max-w-7xl no-print">
    <!-- logo -->
    <div class="logo text-xl">
        Kasir
    </div>

    <!-- menu tengah -->
    <ul class="gap-2 flex items-center">
        <li><a href="?" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">Beranda</a></li>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="?page=meja" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-table text-sm"></i>
                    Entri Meja
                </a></li>
            <li><a href="?page=menu" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-utensils text-sm"></i>
                    Entri Menu
                </a></li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'waiter'): ?>
            <li><a href="?page=menu" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-utensils text-sm"></i>
                    Entri Menu
                </a></li>
            <li><a href="?page=pesanan" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-clipboard-list text-sm"></i>
                    Entri Pesanan
                </a></li>
            <li><a href="?page=laporan" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-chart-bar text-sm"></i>
                    Laporan
                </a></li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'kasir'): ?>
            <li><a href="?page=transaksi" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-cash-register text-sm"></i>
                    Entri Transaksi
                </a></li>
            <li><a href="?page=laporan" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-chart-bar text-sm"></i>
                    Laporan
                </a></li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'owner'): ?>
            <li><a href="?page=laporan" class="menu flex items-center gap-2 px-4 py-2 rounded-xl">
                    <i class="fas fa-chart-bar text-sm"></i>
                    Laporan
                </a></li>
        <?php endif; ?>
    </ul>

    <!-- user -->
    <form method="post">
        <button type="submit" name="logout" class="logout-btn flex items-center gap-2 px-4 py-2 rounded-full text-white font-medium">
            <i class="fas fa-sign-out-alt text-sm"></i>
            Log Out
        </button>
    </form>
</nav>

<script src="https://kit.fontawesome.com/54328c2d50.js" crossorigin="anonymous"></script>