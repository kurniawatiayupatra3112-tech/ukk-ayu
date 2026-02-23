<?php
/**
 * Header Template - Sidebar Navigation
 */

// ================= SESSION =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

// ================= BASE URL =================
$base_url = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
$base_url = rtrim($base_url, '/\\');
if ($base_url === '\\') {
    $base_url = '';
}

// ================= AUTH CHECK =================
$current_file = basename($_SERVER['PHP_SELF']);
if ($current_file !== 'login.php' && !isset($_SESSION['user_id'])) {
    header('Location: ' . $base_url . '/app/auth/login.php');
    exit;
}

// ================= ACTIVE MENU =================
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Inventaris' ?> | UKK</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= $base_url ?>/app/assets/css/style.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>

<div class="app-container">

<!-- ================= SIDEBAR ================= -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <span>Inventaris</span>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section">
            <span class="nav-section-title">Menu Utama</span>

            <a href="<?= $base_url ?>/app/dashboard.php"
               class="nav-item <?= ($current_page === 'dashboard') ? 'active' : '' ?>">
                <span>Dashboard</span>
            </a>

            <a href="<?= $base_url ?>/app/barang/"
               class="nav-item <?= ($current_dir === 'barang') ? 'active' : '' ?>">
                <span>Data Barang</span>
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-title">Transaksi</span>

            <a href="<?= $base_url ?>/app/transaksi/masuk.php"
               class="nav-item <?= ($current_page === 'masuk') ? 'active' : '' ?>">
                <span>Barang Masuk</span>
            </a>

            <a href="<?= $base_url ?>/app/transaksi/keluar.php"
               class="nav-item <?= ($current_page === 'keluar') ? 'active' : '' ?>">
                <span>Barang Keluar</span>
            </a>

            <a href="<?= $base_url ?>/app/transaksi/riwayat.php"
               class="nav-item <?= ($current_page === 'riwayat') ? 'active' : '' ?>">
                <span>Riwayat</span>
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-title">Laporan</span>

            <a href="<?= $base_url ?>/app/laporan/"
               class="nav-item <?= ($current_dir === 'laporan') ? 'active' : '' ?>">
                <span>Lihat Laporan</span>
            </a>
        </div>

    </nav>

    <div class="sidebar-footer">
    <div class="user-info">
        <div class="user-avatar">
            <!-- ICON USER -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <div class="user-details">
            <span class="user-name">ayu</span>
            <span class="user-role">Administrator</span>
        </div>
    </div>

    <a href="logout.php" class="logout-btn" title="Logout">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V7" />
        </svg>
    </a>
</div>
</aside>

<!-- ================= MAIN CONTENT ================= -->
<main class="main-content">