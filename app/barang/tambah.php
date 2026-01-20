<?php
/**
 * Tambah Barang
 * INGAT: Proses form SEBELUM include header!
 */

// 1. Mulai session
session_start();
date_default_timezone_set('Asia/Jakarta');

// 2. Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: /bahan-ajar-ukk/app/auth/login.php');
    exit;
}

// 3. Koneksi database
require_once __DIR__ . '/../config/koneksi.php';

// 4. Variabel untuk menyimpan error
$error = '';

// 5. Proses form SEBELUM output HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $stok = (int) ($_POST['stok'] ?? 0);
    $harga = (float) ($_POST['harga'] ?? 0);

    // Validasi
    if (empty($nama_barang)) {
        $error = 'Nama barang tidak boleh kosong!';
    } elseif ($stok < 0) {
        $error = 'Stok tidak boleh negatif!';
    } elseif ($harga < 0) {
        $error = 'Harga tidak boleh negatif!';
    } else {
        // Simpan ke database
        $stmt = $pdo->prepare("INSERT INTO barang (nama_barang, stok, harga) VALUES (?, ?, ?)");

        if ($stmt->execute([$nama_barang, $stok, $harga])) {
            // Sukses! Set flash message dan redirect
            $_SESSION['flash_message'] = 'Barang berhasil ditambahkan!';
            $_SESSION['flash_type'] = 'success';
            header('Location: index.php');
            exit;
        } else {
            $error = 'Gagal menyimpan data!';
        }
    }
}

// 6. BARU setelah proses selesai, include header
$page_title = 'Tambah Barang';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- 7. HTML Form -->
<div class="page-header">
    <h1>Tambah Barang Baru</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <!-- Tampilkan error kalau ada -->
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang *</label>
                        <input type="text" name="nama_barang" class="form-control"
                               value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>"
                               placeholder="Contoh: Laptop ASUS" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control"
                               value="<?= htmlspecialchars($_POST['stok'] ?? '0') ?>"
                               min="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control"
                               value="<?= htmlspecialchars($_POST['harga'] ?? '0') ?>"
                               min="0">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-outline-primary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 8. Include footer -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>