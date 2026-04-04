<?php
/**
 * Transaksi Barang Masuk
 * Ingat: Proses form SEBELUM include header!
 */

session_start();
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../config/koneksi.php';

// Ambil daftar barang untuk dropdown
$stmt = $pdo->query("SELECT id, nama_barang, stok FROM barang ORDER BY nama_barang");
$daftar_barang = $stmt->fetchAll();

$error = '';

// Proses form SEBELUM output HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = (int) ($_POST['id_barang'] ?? 0);
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');

    // Validasi
    if ($id_barang <= 0) {
        $error = 'Pilih barang terlebih dahulu!';
    } elseif ($jumlah <= 0) {
        $error = 'Jumlah harus lebih dari 0!';
    } else {
        try {
            // Mulai transaction (untuk keamanan data)
            $pdo->beginTransaction();

            // 1. Insert ke tabel transaksi
            $stmt = $pdo->prepare("INSERT INTO transaksi (id_barang, jenis_transaksi, jumlah, keterangan) VALUES (?, 'masuk', ?, ?)");
            $stmt->execute([$id_barang, $jumlah, $keterangan]);

            // 2. Update stok barang (TAMBAH)
            $stmt = $pdo->prepare("UPDATE barang SET stok = stok + ? WHERE id = ?");
            $stmt->execute([$jumlah, $id_barang]);

            // Commit transaction (simpan permanen)
            $pdo->commit();

            $_SESSION['flash_message'] = "Barang masuk berhasil dicatat!";
            $_SESSION['flash_type'] = 'success';
            header('Location: riwayat.php');
            exit;

        } catch (Exception $e) {
            // Rollback kalau ada error (batalkan semua)
            $pdo->rollback();
            $error = 'Terjadi kesalahan!';
        }
    }
}

// BARU include header
$page_title = 'Barang Masuk';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h1>Barang Masuk</h1>
    <p>Catat barang yang masuk ke gudang</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang *</label>
                        <select name="id_barang" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach ($daftar_barang as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= (isset($_POST['id_barang']) && $_POST['id_barang'] == $b['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($b['nama_barang']) ?> (Stok: <?= $b['stok'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Masuk *</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required
                               value="<?= htmlspecialchars($_POST['jumlah'] ?? '') ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Contoh: Pembelian dari supplier"><?= htmlspecialchars($_POST['keterangan'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="riwayat.php" class="btn btn-outline-primary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <ul class="mb-0 text-muted" style="line-height:1.8;">
                    <li>Pilih barang yang sudah tersedia di daftar.</li>
                    <li>Jumlah harus lebih dari 0 untuk disimpan.</li>
                    <li>Transaksi masuk akan menambah stok otomatis.</li>
                    <li>Gunakan kolom keterangan untuk referensi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>