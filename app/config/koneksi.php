<?php
/**
 * File Koneksi Database
 * Menggunakan PDO untuk keamanan
 */

// Pengaturan database
$host = 'localhost';           // Alamat server database
$dbname = 'inventaris_ayupatra'; // Nama database
$username = 'root';            // Username MySQL (default: root)
$password = 'baru123';                // Password MySQL (default: kosong)

try {
    // Membuat koneksi ke database
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // Kalau gagal koneksi, tampilkan pesan error
    die("Koneksi gagal: " . $e->getMessage());
}