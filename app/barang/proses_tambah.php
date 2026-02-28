<?php
/**
 * Proses Tambah Barang
 * Menerima data dari form dan menyimpan ke database
 */

session_start();

// ✅ FIX: Path ke koneksi.php - sesuaikan dengan struktur folder Anda
// Jika config ada di root (ukk-ayu/config/):
// ✅ BENAR (naik 1 level saja)
require_once __DIR__ . '/../config/koneksi.php';

// Jika config ada di app/config/, gunakan ini:
// require_once __DIR__ . '/../config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Pastikan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Ambil dan sanitasi input
$nama_barang  = trim($_POST['nama_barang'] ?? '');
$id_kategori  = (int)($_POST['id_kategori'] ?? 0);
$satuan       = trim($_POST['satuan'] ?? '');
$stok         = (int)($_POST['stok'] ?? 0);
$stok_minimal = (int)($_POST['stok_minimal'] ?? 0);
$harga        = (float)($_POST['harga'] ?? 0);
$keterangan   = trim($_POST['keterangan'] ?? '');

// Validasi minimal
if (empty($nama_barang) || empty($id_kategori) || empty($satuan) || $harga <= 0) {
    $_SESSION['error'] = 'Nama barang, kategori, satuan, dan harga wajib diisi!';
    header('Location: tambah.php');
    exit;
}

try {
    // Generate kode_barang otomatis (opsional)
    $kode_barang = 'BRG-' . date('Ymd') . '-' . uniqid();
    
    // Insert ke database
    $stmt = $pdo->prepare("
        INSERT INTO barang (
            kode_barang, nama_barang, id_kategori, satuan, 
            stok, stok_minimal, harga, keterangan, status, kondisi
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'aktif', 'baik')
    ");
    
    $stmt->execute([
        $kode_barang,
        $nama_barang,
        $id_kategori,
        $satuan,
        $stok,
        $stok_minimal,
        $harga,
        $keterangan
    ]);
    
    // Success redirect
    $_SESSION['success'] = 'Barang berhasil ditambahkan!';
    header('Location: index.php');
    exit;
    
} catch (PDOException $e) {
    // Error handling
    error_log($e->getMessage());
    $_SESSION['error'] = 'Gagal menyimpan data: ' . $e->getMessage();
    header('Location: tambah.php');
    exit;
}