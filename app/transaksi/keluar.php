<?php  
/**
 * Transaksi Barang Keluar - Soft Blue Theme
 * Proses form SEBELUM include header
 */

session_start();
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../config/koneksi.php';

$stmt = $pdo->query("SELECT id, nama_barang, stok FROM barang ORDER BY nama_barang");
$daftar_barang = $stmt->fetchAll();

$error = '';

// Proses form SEBELUM output HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = (int) ($_POST['id_barang'] ?? 0);
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    
    if ($id_barang <= 0) {
        $error = 'Pilih barang terlebih dahulu!';
    } elseif ($jumlah <= 0) {
        $error = 'Jumlah harus lebih dari 0!';
    } else {
        $stmt = $pdo->prepare("SELECT stok, nama_barang FROM barang WHERE id = ?");
        $stmt->execute([$id_barang]);
        $barang = $stmt->fetch();
        
        if (!$barang) {
            $error = 'Barang tidak ditemukan!';
        } elseif ($barang['stok'] < $jumlah) {
            $error = "Stok tidak cukup! Stok {$barang['nama_barang']} hanya {$barang['stok']}";
        } else {
            try {
                $pdo->beginTransaction();
                
                $stmt = $pdo->prepare("INSERT INTO transaksi (id_barang, jenis_transaksi, jumlah, keterangan) VALUES (?, 'keluar', ?, ?)");
                $stmt->execute([$id_barang, $jumlah, $keterangan]);
                
                $stmt = $pdo->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");
                $stmt->execute([$jumlah, $id_barang]);
                
                $pdo->commit();
                
                $_SESSION['flash_message'] = "✅ Barang keluar berhasil! {$barang['nama_barang']} -{$jumlah}";
                $_SESSION['flash_type'] = 'success';
                header('Location: riwayat.php');
                exit;
                
            } catch (Exception $e) {
                $pdo->rollback();
                $error = 'Terjadi kesalahan: ' . $e->getMessage();
            }
        }
    }
}

// Setelah proses selesai, baru include header
$page_title = 'Barang Keluar';
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
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Card Header - Danger Style for Barang Keluar */
.card-header.danger {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-bottom-color: #fecaca;
    color: #dc2626;
}

.card-header.warning {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-bottom-color: #fcd34d;
    color: #b45309;
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
    display: block;
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
    width: 100%;
}

.form-select:focus,
.form-control:focus,
textarea.form-control:focus {
    border-color: #60a5fa;
    box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.15);
    outline: none;
    background: #ffffff;
}

.form-select:invalid,
.form-control:invalid {
    border-color: #f87171;
}

.form-select::placeholder,
.form-control::placeholder,
textarea.form-control::placeholder {
    color: #94a3b8;
}

.form-control.is-invalid {
    border-color: #ef4444;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

/* ===== STOCK INFO BADGE ===== */
#stokInfo .badge {
    font-size: 13px;
    padding: 6px 12px;
    font-weight: 500;
}

#stokInfo .badge.bg-primary {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%) !important;
}

#stokInfo .badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white;
}

#stokInfo .badge.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

/* ===== BUTTONS ===== */
.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    border-radius: 10px;
    padding: 10px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
    transform: translateY(-2px);
}

.btn-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
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

.alert-danger::before {
    content: '❌';
}

.alert-warning {
    border-left-color: #f59e0b;
    background: rgba(254, 243, 199, 0.95);
    color: #92400e;
}

.alert-warning::before {
    content: '⚡';
}

/* ===== WARNING BOX ===== */
.warning-box {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1px solid #fcd34d;
    border-radius: 12px;
    padding: 16px;
    margin-top: 16px;
}

.warning-box ul {
    margin: 0;
    padding-left: 20px;
}

.warning-box li {
    color: #92400e;
    font-size: 14px;
    line-height: 1.8;
}

.warning-box code {
    background: rgba(251, 191, 36, 0.2);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    color: #b45309;
}

.warning-box .text-danger {
    color: #dc2626 !important;
    font-weight: 600;
}

/* ===== INVALID FEEDBACK ===== */
.invalid-feedback {
    display: block;
    color: #dc2626;
    font-size: 13px;
    margin-top: 6px;
    font-weight: 500;
}

/* ===== HELPER TEXT ===== */
.form-text {
    color: #64748b;
    font-size: 13px;
    margin-top: 6px;
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
    
    .btn-danger,
    .btn-outline-primary {
        width: 100%;
        justify-content: center;
        margin-bottom: 8px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
}
</style>

<div class="page-header">
    <h1>📤 Barang Keluar</h1>
    <p>Catat barang yang keluar dari gudang</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header danger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                </svg>
                Form Transaksi Keluar
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if (empty($daftar_barang)): ?>
                    <div class="alert alert-warning">
                        ⚠️ Belum ada data barang. <a href="../barang/tambah.php" class="fw-bold">Tambah barang</a> terlebih dahulu.
                    </div>
                <?php else: ?>
                    <form method="POST" id="formKeluar">
                        <div class="mb-3">
                            <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                            <select name="id_barang" class="form-select" required id="selectBarang" onchange="updateStokInfo()">
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($daftar_barang as $b): ?>
                                    <option value="<?= $b['id'] ?>" data-stok="<?= $b['stok'] ?>" <?= (isset($_POST['id_barang']) && $_POST['id_barang'] == $b['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($b['nama_barang']) ?> 
                                        <span class="text-muted">(Stok: <?= $b['stok'] ?>)</span>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="stokInfo" class="mt-2"></div>
                            <small class="form-text">Pilih barang yang akan dikeluarkan dari gudang</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" id="inputJumlah"
                                   value="<?= htmlspecialchars($_POST['jumlah'] ?? '') ?>"
                                   min="1" required placeholder="Masukkan jumlah barang" oninput="validateJumlah()">
                            <div id="jumlahWarning" class="invalid-feedback"></div>
                            <small class="form-text">Jumlah tidak boleh melebihi stok tersedia</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                      placeholder="Contoh: Pengiriman ke Customer ABC, No. Surat Jalan: SJ-001"><?= htmlspecialchars($_POST['keterangan'] ?? '') ?></textarea>
                            <small class="form-text">Opsional: tambahkan tujuan, nomor dokumen, atau catatan penting</small>
                        </div>
                        
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-danger" id="btnSubmit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Simpan Transaksi
                            </button>
                            <a href="<?= $base_url ?>/app/dashboard.php" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                ⚠️ Penting!
            </div>
            <div class="card-body">
                <div class="warning-box">
                    <ul class="mb-0">
                        <li>Stok barang akan <strong>berkurang otomatis</strong> setelah transaksi disimpan.</li>
                        <li>Rumus: <code>stok_baru = stok_lama - jumlah_keluar</code></li>
                        <li class="text-danger fw-bold">❌ Stok tidak boleh minus!</li>
                        <li>Pastikan jumlah keluar ≤ stok tersedia.</li>
                        <li>Gunakan keterangan untuk referensi audit.</li>
                    </ul>
                </div>
                
                <div class="mt-4 p-3 rounded-3" style="background: #eff6ff; border: 1px solid #93c5fd;">
                    <small class="text-muted d-block mb-1 fw-bold">💡 Tips:</small>
                    <small class="text-secondary">
                        Selalu cek stok sebelum input transaksi keluar. Gunakan fitur <strong>cari barang</strong> untuk menemukan item dengan cepat.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStokInfo() {
    const select = document.getElementById('selectBarang');
    const option = select.options[select.selectedIndex];
    const stokInfo = document.getElementById('stokInfo');
    
    if (option && option.value) {
        const stok = parseInt(option.dataset.stok);
        const namaBarang = option.textContent.split('(')[0].trim();
        
        if (stok === 0) {
            stokInfo.innerHTML = `<span class="badge bg-danger">⚠️ Stok habis!</span>`;
            document.getElementById('inputJumlah').max = 0;
            document.getElementById('inputJumlah').disabled = true;
            document.getElementById('btnSubmit').disabled = true;
        } else if (stok <= 5) {
            stokInfo.innerHTML = `<span class="badge bg-warning">⚡ Stok menipis: ${stok}</span>`;
            document.getElementById('inputJumlah').max = stok;
            document.getElementById('inputJumlah').disabled = false;
            document.getElementById('btnSubmit').disabled = false;
        } else {
            stokInfo.innerHTML = `<span class="badge bg-primary">✅ Stok tersedia: ${stok}</span>`;
            document.getElementById('inputJumlah').max = stok;
            document.getElementById('inputJumlah').disabled = false;
            document.getElementById('btnSubmit').disabled = false;
        }
        validateJumlah();
    } else {
        stokInfo.innerHTML = '';
        document.getElementById('inputJumlah').removeAttribute('max');
        document.getElementById('inputJumlah').disabled = false;
        document.getElementById('inputJumlah').value = '';
        document.getElementById('btnSubmit').disabled = false;
    }
}

function validateJumlah() {
    const select = document.getElementById('selectBarang');
    const option = select.options[select.selectedIndex];
    const input = document.getElementById('inputJumlah');
    const warning = document.getElementById('jumlahWarning');
    const btn = document.getElementById('btnSubmit');
    
    if (option && option.value && input.value) {
        const stok = parseInt(option.dataset.stok);
        const jumlah = parseInt(input.value) || 0;
        
        if (jumlah <= 0) {
            input.classList.add('is-invalid');
            warning.textContent = 'Jumlah harus lebih dari 0!';
            btn.disabled = true;
        } else if (jumlah > stok) {
            input.classList.add('is-invalid');
            warning.textContent = `❌ Melebihi stok! Maksimal: ${stok}`;
            btn.disabled = true;
        } else if (jumlah > stok * 0.8) {
            input.classList.remove('is-invalid');
            warning.textContent = `⚠️ Hampir menghabiskan stok (tersisa: ${stok - jumlah})`;
            warning.style.color = '#d97706';
            btn.disabled = false;
        } else {
            input.classList.remove('is-invalid');
            warning.textContent = `✅ Valid. Sisa stok setelah ini: ${stok - jumlah}`;
            warning.style.color = '#166534';
            btn.disabled = false;
        }
    } else {
        input.classList.remove('is-invalid');
        warning.textContent = '';
        if (!option || !option.value) {
            btn.disabled = true;
        }
    }
}

// Real-time validation on input
document.getElementById('inputJumlah')?.addEventListener('input', validateJumlah);

window.addEventListener('DOMContentLoaded', function() {
    updateStokInfo();
    validateJumlah();
    
    // Prevent form submit if invalid
    document.getElementById('formKeluar')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSubmit');
        if (btn.disabled) {
            e.preventDefault();
            alert('⚠️ Tidak dapat menyimpan: jumlah melebihi stok atau belum valid!');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>