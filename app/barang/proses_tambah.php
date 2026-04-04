<?php
/**
 * Proses Tambah Barang - VERSI FIX FINAL ✅
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Tampilkan error saat development

require_once __DIR__ . '/../config/koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// =============================
// 🔹 AMBIL DATA DARI FORM
// =============================
$nama_barang  = trim($_POST['nama_barang'] ?? '');
$kategori_input = $_POST['id_kategori'] ?? ''; // Simpan dulu sebagai string
$satuan       = trim($_POST['satuan'] ?? '');
$stok         = (int)($_POST['stok'] ?? 0);
$stok_minimal = (int)($_POST['stok_minimal'] ?? 0);
$keterangan   = trim($_POST['keterangan'] ?? '');
$lokasi       = trim($_POST['lokasi'] ?? 'Gudang Utama'); // Default jika tidak diisi
$foto         = null; // Bisa dikembangkan untuk upload file nanti

// =============================
// 🔥 FIX HARGA
// =============================
$harga = $_POST['harga_raw'] ?? 0;
$harga = str_replace('.', '', $harga);
$harga = (int)$harga;

// =============================
// 🔹 HANDLE KATEGORI BARU ✅
// =============================
if ($kategori_input === 'BARU') {
    $nama_kategori_baru = trim($_POST['nama_kategori_baru'] ?? '');
    
    if (empty($nama_kategori_baru)) {
        $_SESSION['error'] = 'Nama kategori baru wajib diisi!';
        $_SESSION['old_input'] = $_POST; // Simpan data form
        header('Location: tambah.php');
        exit;
    }
    
    // Insert kategori baru
    try {
        $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->execute([$nama_kategori_baru]);
        $id_kategori = (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal membuat kategori: ' . $e->getMessage();
        header('Location: tambah.php');
        exit;
    }
} else {
    $id_kategori = (int)$kategori_input;
}

// =============================
// 🔹 VALIDASI
// =============================
if (empty($nama_barang) || $id_kategori <= 0 || empty($satuan) || $harga <= 0) {
    $_SESSION['error'] = 'Nama barang, kategori, satuan, dan harga wajib diisi!';
    $_SESSION['old_input'] = $_POST;
    header('Location: tambah.php');
    exit;
}

if ($harga < 1000) {
    $_SESSION['error'] = 'Harga tidak valid!';
    header('Location: tambah.php');
    exit;
}

// =============================
// 🔹 SIMPAN KE DATABASE ✅
// =============================
try {
    $kode_barang = 'BRG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

    $stmt = $pdo->prepare("
        INSERT INTO barang (
            kode_barang, nama_barang, id_kategori, satuan, 
            stok, stok_minimal, harga, lokasi, keterangan, foto,
            kondisi, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'baik', NOW(), NOW())
    ");

    $stmt->execute([
        $kode_barang,
        $nama_barang,
        $id_kategori,
        $satuan,
        $stok,
        $stok_minimal,
        $harga,
        $lokasi,
        $keterangan,
        $foto
    ]);

    $_SESSION['success'] = '✅ Barang berhasil ditambahkan!';
    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    // Tampilkan error detail saat development
    if (getenv('APP_ENV') !== 'production') {
        echo "<pre>❌ ERROR DATABASE:\n";
        echo "Pesan: " . $e->getMessage() . "\n";
        echo "Query: " . $stmt->queryString . "\n";
        echo "Data: " . print_r([
            $kode_barang, $nama_barang, $id_kategori, $satuan, 
            $stok, $stok_minimal, $harga, $lokasi, $keterangan, $foto
        ], true) . "</pre>";
        exit;
    }
    
    error_log("Barang Insert Error: " . $e->getMessage());
    $_SESSION['error'] = 'Gagal menyimpan data. Silakan coba lagi.';
    header('Location: tambah.php');
    exit;
}