<?php
/**
 * Transaksi Barang Masuk - Soft Blue Theme
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

            $_SESSION['flash_message'] = "✅ Barang masuk berhasil dicatat!";
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

<!-- 💙 Soft Blue Theme CSS -->
<style>
/* ===== BACKGROUND - SOFT BLUE ===== */
body {
    background: #eff6ff !important;
    min-height: 100vh;
}

/* ===== PAGE HEADER ===== */
.page-header {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    padding: 20px 24px;
    border-radius: 16px;
    margin-bottom: 24px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 2px 12px rgba(59, 130, 246, 0.06);
}

.page-header h1 {
    font-size: 24px;
    font-weight: 700;
    color: #1e3a5f;
    margin-bottom: 4px;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

/* ===== CARD STYLING ===== */
.card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.08), 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: box-shadow 0.2s ease;
    height: 100%;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.12), 0 4px 16px rgba(0, 0, 0, 0.05);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    color: #1e293b;
    border-radius: 16px 16px 0 0 !important;
    padding: 16px 20px;
    font-size: 15px;
}

.card-body {
    padding: 24px;
}

/* ===== FORM STYLING ===== */
.form-label {
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-select,
.form-control,
textarea.form-control {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    color: #1e293b;
    background: #ffffff;
    transition: all 0.2s ease;
}

.form-select:focus,
.form-control:focus,
textarea.form-control:focus {
    border-color: #60a5fa;
    box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.15);
    outline: none;
    background: #ffffff;
}

.form-select::placeholder,
.form-control::placeholder,
textarea.form-control::placeholder {
    color: #94a3b8;
}

/* ===== BUTTONS ===== */
.btn-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    border: none;
    border-radius: 10px;
    padding: 10px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-success:hover {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.35);
    transform: translateY(-2px);
}

.btn-outline-primary {
    border-color: #93c5fd;
    color: #2563eb;
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-outline-primary:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    transform: translateY(-2px);
}

/* ===== ALERT STYLING ===== */
.alert {
    border: none;
    border-radius: 12px;
    border-left: 4px solid #ef4444;
    background: rgba(254, 226, 226, 0.95);
    color: #991b1b;
    font-size: 14px;
    font-weight: 500;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.alert::before {
    content: '⚠️';
    font-size: 16px;
    flex-shrink: 0;
}

.alert-success {
    border-left-color: #22c55e;
    background: rgba(220, 252, 231, 0.95);
    color: #166534;
}

.alert-success::before {
    content: '✅';
}

/* ===== INFO LIST ===== */
.card-body ul li {
    position: relative;
    padding-left: 20px;
}

.card-body ul li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: #60a5fa;
    font-weight: bold;
    font-size: 18px;
    top: -2px;
}

/* ===== HELPER TEXT ===== */
.form-text {
    color: #64748b;
    font-size: 13px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header {
        padding: 16px 20px;
    }
    
    .page-header h1 {
        font-size: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .btn-success,
    .btn-outline-primary {
        width: 100%;
        justify-content: center;
        margin-bottom: 8px;
    }
}
</style>

<div class="page-header">
    <h1>📥 Barang Masuk</h1>
    <p>Catat barang yang masuk ke gudang</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Form Transaksi Masuk</div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="id_barang" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach ($daftar_barang as $b): ?>
                                <option value="<?= $b['id'] ?>" 
                                    <?= (isset($_POST['id_barang']) && $_POST['id_barang'] == $b['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($b['nama_barang']) ?> 
                                    <span class="text-muted">(Stok: <?= $b['stok'] ?>)</span>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text">Pilih barang dari daftar yang tersedia</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" min="1" required
                               value="<?= htmlspecialchars($_POST['jumlah'] ?? '') ?>"
                               placeholder="Masukkan jumlah barang">
                        <small class="form-text">Jumlah harus lebih dari 0</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                                  placeholder="Contoh: Pembelian dari Supplier ABC, No. Invoice: INV-001"><?= htmlspecialchars($_POST['keterangan'] ?? '') ?></textarea>
                        <small class="form-text">Opsional: tambahkan referensi atau catatan penting</small>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Simpan Transaksi
                        </button>
                        <a href="riwayat.php" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">📋 Panduan & Catatan</div>
            <div class="card-body">
                <ul class="mb-0 text-muted" style="line-height: 2;">
                    <li>Pilih barang yang sudah terdaftar di sistem.</li>
                    <li>Jumlah masuk <strong>harus lebih dari 0</strong>.</li>
                    <li>Stok barang akan <strong>bertambah otomatis</strong> setelah disimpan.</li>
                    <li>Gunakan kolom keterangan untuk nomor invoice, nama supplier, atau catatan lain.</li>
                    <li>Data transaksi dapat dilihat di menu <strong>Riwayat Transaksi</strong>.</li>
                </ul>
                
                <div class="mt-4 p-3 rounded-3" style="background: #f0f9ff; border: 1px solid #bae6fd;">
                    <small class="text-muted d-block mb-1">💡 Tips:</small>
                    <small class="text-secondary">
                        Gunakan keterangan yang jelas agar mudah dilacak saat audit atau laporan bulanan.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>