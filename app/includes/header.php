<?php
/**
 * Header Template - Sidebar Navigation
 */

// Mulai session (untuk menyimpan data login)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Cek apakah sudah login
$current_file = basename($_SERVER['PHP_SELF']);
if ($current_file !== 'login.php' && !isset($_SESSION['user_id'])) {
    // Kalau belum login dan bukan halaman login, tendang ke login
    header('Location: ' . $base_url . '/app/auth/login.php');
    exit;
}

// Base URL untuk link.
// Jika hosting berada di dalam folder 'app' (seperti /app/auth/login.php),
// kita naik dua tingkat untuk mendapatkan root aplikasi.
// Contoh:
//   /app/auth/login.php    -> base_url = ''
//   /ukk-ayu/app/auth/login.php -> base_url = '/ukk-ayu'
$base_url = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
$base_url = rtrim($base_url, '/\\');
if ($base_url === '\\') {
    $base_url = '';
}

// Mengetahui halaman aktif (untuk highlight menu)
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Inventaris' ?> | UKK</title>

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/app/assets/css/style.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>
     <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <span>Inventaris</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Menu Utama</span>
                    
                    <a href="<?= $base_url ?>/app/dashboard.php" class="nav-item <?= ($current_page === 'dashboard') ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="<?= $base_url ?>/app/barang/" class="nav-item <?= ($current_dir === 'barang') ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <span>Data Barang</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Transaksi</span>
                    
                    <a href="<?= $base_url ?>/app/transaksi/masuk.php" class="nav-item <?= ($current_page === 'masuk') ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                        </svg>
                        <span>Barang Masuk</span>
                    </a>
                    
                    <a href="<?= $base_url ?>/app/transaksi/keluar.php" class="nav-item <?= ($current_page === 'keluar') ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                        </svg>
                        <span>Barang Keluar</span>
                    </a>
                    
                    <a href="<?= $base_url ?>/app/transaksi/riwayat.php" class="nav-item <?= ($current_page === 'riwayat') ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Riwayat</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Laporan</span>
                    
                    <a href="<?= $base_url ?>/app/laporan/" class="nav-item <?= ($current_dir === 'laporan' && $current_page === 'index') ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        <span>Lihat Laporan</span>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
                <a href="<?= $base_url ?>/app/auth/logout.php" class="logout-btn" title="Logout" onclick="return confirm('Yakin ingin logout?')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
