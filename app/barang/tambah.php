<?php
/**
 * Tambah Barang - 1 FILE LENGKAP (FIX KATEGORI)
 */

session_start();
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) {
    header('Location: /bahan-ajar-ukk/app/auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

$error = '';

// AMBIL DATA KATEGORI
$stmtKategori = $pdo->query("SELECT id, nama_kategori FROM kategori");
$kategoriList = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

// PROSES SIMPAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_barang   = trim($_POST['nama_barang'] ?? '');
    $id_kategori   = (int) ($_POST['id_kategori'] ?? 0); // ✅ FIX
    $satuan        = trim($_POST['satuan'] ?? '-');
    $stok          = (int) ($_POST['stok'] ?? 0);
    $stok_minimal  = (int) ($_POST['stok_minimal'] ?? 0);
    $harga         = (float) ($_POST['harga'] ?? 0);
    $status        = 'aktif';

    if ($nama_barang === '') {
        $error = 'Nama barang wajib diisi!';
    } elseif ($id_kategori <= 0) { // ✅ FIX
        $error = 'Kategori wajib dipilih!';
    } elseif ($stok < 0 || $stok_minimal < 0) {
        $error = 'Stok tidak boleh negatif!';
    } elseif ($harga < 0) {
        $error = 'Harga tidak boleh negatif!';
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO barang
            (nama_barang, id_kategori, satuan, stok, stok_minimal, harga, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt->execute([
            $nama_barang,
            $id_kategori, // ✅ FIX
            $satuan,
            $stok,
            $stok_minimal,
            $harga,
            $status
        ])) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Gagal menyimpan data!';
        }
    }
}

$page_title = 'Tambah Barang';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h1>Tambah Barang</h1>
    <p>Tambahkan barang baru ke gudang</p>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control"
                               value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategoriList as $k): ?>
                                <option value="<?= $k['id'] ?>"
                                    <?= (($_POST['id_kategori'] ?? '') == $k['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control"
                               value="<?= htmlspecialchars($_POST['satuan'] ?? '') ?>"
                               placeholder="Contoh: pcs, unit, box">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control"
                               value="<?= htmlspecialchars($_POST['stok'] ?? 0) ?>" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Minimal</label>
                        <input type="number" name="stok_minimal" class="form-control"
                               value="<?= htmlspecialchars($_POST['stok_minimal'] ?? 0) ?>" min="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control"
                               value="<?= htmlspecialchars($_POST['harga'] ?? 0) ?>" min="0">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Simpan
                        </button>
                        <a href="index.php" class="btn btn-outline-primary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
