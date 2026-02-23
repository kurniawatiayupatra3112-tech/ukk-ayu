<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) {
    header('Location: /bahan-ajar-ukk/app/auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

$error = '';

// ambil kategori
$stmt = $pdo->query("
    SELECT 
        b.*, 
        k.nama_kategori
    FROM barang b
    LEFT JOIN kategori k 
        ON b.id_kategori = k.id
    ORDER BY b.id DESC
");

// simpan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_barang  = trim($_POST['nama_barang'] ?? '');
    $id_kategori  = (int) ($_POST['id_kategori'] ?? 0);
    $satuan       = trim($_POST['satuan'] ?? '');
    $stok         = (int) ($_POST['stok'] ?? 0);
    $stok_minimal = (int) ($_POST['stok_minimal'] ?? 0);
    $harga        = (float) ($_POST['harga'] ?? 0);

    if ($nama_barang === '') {
        $error = 'Nama barang wajib diisi!';
    } elseif ($id_kategori <= 0) {
        $error = 'Kategori wajib dipilih!';
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO barang
            (nama_barang, id_kategori, satuan, stok, stok_minimal, harga, status)
            VALUES (?, ?, ?, ?, ?, ?, 'aktif')
        ");

        $stmt->execute([
            $nama_barang,
            $id_kategori,
            $satuan,
            $stok,
            $stok_minimal,
            $harga
        ]);

        header('Location: index.php');
        exit;
    }
}

$page_title = 'Tambah Barang';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h3>Tambah Barang</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" required>

        <label>Kategori</label>
        <select name="id_kategori" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategoriList as $k): ?>
                <option value="<?= $k['id'] ?>">
                    <?= $k['nama_kategori'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Satuan</label>
        <input type="text" name="satuan" class="form-control">

        <label>Stok</label>
        <input type="number" name="stok" class="form-control">

        <label>Stok Minimal</label>
        <input type="number" name="stok_minimal" class="form-control">

        <label>Harga</label>
        <input type="number" name="harga" class="form-control">

        <br>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
