<?php
/**
 * Tambah Barang - Form Input Modern dengan Auto-Fill Satuan
 * Theme: Blue & Pink ✨
 * ✅ FIX: Query kategori diperbaiki
 */

$page_title = 'Tambah Barang';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/koneksi.php';

// ✅ QUERY DIPERBAIKI - Ambil dari tabel 'kategori', bukan kolom 'kategori' di tabel barang
// ⚠️ Sesuaikan nama tabel 'kategori' jika di database Anda 'tb_kategori'
$stmt = $pdo->query("SELECT id, nama_kategori FROM kategori WHERE nama_kategori IS NOT NULL AND nama_kategori != '' ORDER BY nama_kategori ASC");
$kategori_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Custom CSS untuk Form Modern (Sama seperti sebelumnya) -->
<style>
    :root {
        --blue-primary: #3b82f6;
        --blue-hover: #2563eb;
        --pink-primary: #ec4899;
        --pink-hover: #db2777;
        --gray-light: #f8fafc;
        --gray-border: #e2e8f0;
    }
    
    .form-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid var(--gray-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        border-color: var(--blue-primary);
    }
    
    .form-section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--blue-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--pink-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    
    .form-label .icon {
        width: 16px;
        height: 16px;
        color: var(--pink-primary);
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid var(--gray-border);
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--blue-primary);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        outline: none;
    }
    
    .form-control:invalid:not(:placeholder-shown) {
        border-color: var(--pink-primary);
    }
    
    .form-text {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.35rem;
    }
    
    #satuan-hint {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        background: var(--gray-light);
        display: inline-block;
        margin-top: 0.4rem;
        transition: all 0.3s ease;
    }
    
    #satuan-hint.text-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-hover) 100%);
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
    }
    
    .btn-secondary {
        background: white;
        border: 2px solid var(--gray-border);
        color: #475569;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        border-color: var(--pink-primary);
        color: var(--pink-primary);
        transform: translateY(-2px);
    }
    
    .required {
        color: var(--pink-primary);
        font-weight: 600;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .form-section {
            padding: 1.25rem;
        }
        .btn-group-mobile {
            flex-direction: column;
        }
        .btn-group-mobile .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Animasi masuk form */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-section {
        animation: fadeInUp 0.4s ease forwards;
        opacity: 0;
    }
    
    .form-section:nth-child(1) { animation-delay: 0.1s; opacity: 1; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
</style>

<div class="page-header">
    <h1 style="background: linear-gradient(135deg, var(--blue-primary) 0%, var(--pink-primary) 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
        ✨ Tambah Barang
    </h1>
    <p class="text-muted">Lengkapi form berikut untuk menambahkan barang baru ke gudang</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="proses_tambah.php" method="POST" id="formTambahBarang" class="needs-validation" novalidate>
            
            <!-- 🔹 Section 1: Informasi Dasar -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    Informasi Dasar
                </div>
                
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        Nama Barang <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                           placeholder="Contoh: Laptop ASUS VivoBook 14" required>
                    <div class="form-text">Masukkan nama lengkap barang sesuai identitas</div>
                </div>

                <div class="form-row">
                    <div class="mb-3">
                        <label for="id_kategori" class="form-label">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            </svg>
                            Kategori <span class="required">*</span>
                        </label>
                        <!-- ✅ FIX: name="id_kategori" sesuai kolom database -->
                        <select class="form-select" id="id_kategori" name="id_kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori_list as $kat): ?>
                                <!-- ✅ FIX: value=id, tampilan=nama_kategori -->
                                <option value="<?= htmlspecialchars($kat['id']) ?>">
                                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="lainnya" style="color: var(--pink-primary); font-weight: 500;">
                                ✨ + Tambah Kategori Lain
                            </option>
                        </select>
                        <div class="form-text">Pilih kategori untuk saran satuan otomatis</div>
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            Satuan <span class="required">*</span>
                        </label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="">-- Pilih atau otomatis --</option>
                            <option value="pcs">📦 pcs (pieces)</option>
                            <option value="unit">🔹 unit</option>
                            <option value="box">📦 box / dus</option>
                            <option value="pack">📋 pack</option>
                            <option value="set">🎁 set</option>
                            <option value="lusin">1️⃣2️⃣ lusin</option>
                            <option value="kg">⚖️ kg</option>
                            <option value="gram">⚖️ gram</option>
                            <option value="liter">💧 liter</option>
                            <option value="ml">💧 ml</option>
                            <option value="meter">📏 meter</option>
                            <option value="cm">📏 cm</option>
                            <option value="roll">🧻 roll</option>
                            <option value="botol">🍼 botol</option>
                            <option value="kaleng">🥫 kaleng</option>
                            <option value="lembar">📄 lembar</option>
                            <option value="buah">🍎 buah</option>
                        </select>
                        <small class="text-muted" id="satuan-hint">💡 Pilih kategori untuk saran satuan</small>
                    </div>
                </div>
            </div>
            
            <!-- 🔹 Section 2: Stok & Harga -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Stok & Harga
                </div>
                
                <div class="form-row">
                    <div class="mb-3">
                        <label for="stok" class="form-label">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Stok Awal
                        </label>
                        <input type="number" class="form-control" id="stok" name="stok" 
                               value="0" min="0" placeholder="0">
                        <div class="form-text">Jumlah barang yang tersedia saat ini</div>
                    </div>

                    <div class="mb-3">
                        <label for="stok_minimal" class="form-label">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            Stok Minimal
                        </label>
                        <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" 
                               value="5" min="0" placeholder="5">
                        <div class="form-text">⚠️ Peringatan jika stok ≤ nilai ini</div>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Harga (Rp) <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control" id="harga" name="harga" 
                               value="0" min="0" placeholder="0" required>
                        <div class="form-text">Harga per <?= '<span id="satuan-display">satuan</span>' ?></div>
                    </div>
                </div>
            </div>
            
            <!-- 🔹 Section 3: Keterangan Tambahan -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Keterangan
                </div>
                
                <div class="mb-3">
                    <label for="keterangan" class="form-label">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.875.5.17.238.292.512.364.803.073.29.112.592.112.902 0 .31-.039.612-.112.902a2.003 2.003 0 01-.364.803c-.205.29-.525.474-.875.5a21.075 21.075 0 01-3.423.379c-1.584.233-2.707 1.626-2.707 3.228V6.741c0-1.602 1.123-2.995 2.707-3.228A48.394 48.394 0 0112 3c2.392 0 4.744.175 7.043.513C20.623 3.746 21.747 5.14 21.747 6.741v12.75c0 1.602-1.123 2.995-2.707 3.228A48.394 48.394 0 0112 21c-1.153-.086-2.294-.213-3.423-.379-1.584-.233-2.707-1.626-2.707-3.228V6.741z" />
                        </svg>
                        Catatan Tambahan
                    </label>
                    <textarea class="form-control" id="keterangan" name="keterangan" 
                              rows="3" placeholder="Contoh: Barang fragile, simpan di rak atas..."></textarea>
                    <div class="form-text">Informasi tambahan untuk memudahkan pengelolaan</div>
                </div>
            </div>

            <!-- 🔹 Tombol Aksi -->
            <div class="d-flex gap-3 mt-4 pt-3 border-top btn-group-mobile">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    💾 Simpan Barang
                </button>
                <a href="index.php" class="btn btn-secondary flex-grow-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    ❌ Batal
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<!-- JavaScript untuk Auto-Fill & Validasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ FIX: ID selector diubah dari 'kategori' menjadi 'id_kategori'
    const kategoriSelect = document.getElementById('id_kategori');
    const satuanSelect = document.getElementById('satuan');
    const satuanHint = document.getElementById('satuan-hint');
    const satuanDisplay = document.getElementById('satuan-display');
    
    // Mapping kategori (by NAME) ke satuan default
    const satuanMap = {
        'Elektronik': 'unit', 'Gadget': 'unit', 'Komputer': 'unit', 'Aksesoris': 'pcs',
        'ATK': 'pcs', 'Alat Tulis': 'pcs', 'Kertas': 'rim', 'Buku': 'buah',
        'Makanan': 'pack', 'Minuman': 'botol', 'Snack': 'pack', 'Bahan Makanan': 'kg',
        'Kebersihan': 'pcs', 'Sabun': 'buah', 'Pembersih': 'liter', 'Tisu': 'pack',
        'Furniture': 'unit', 'Peralatan': 'set', 'Alat': 'buah',
        'Lainnya': 'pcs', 'lainnya': 'pcs'
    };
    
    // Fungsi auto-fill satuan dengan animasi
    function autoFillSatuan(kategoriName) {
        const defaultSatuan = satuanMap[kategoriName] || '';
        
        if (defaultSatuan) {
            // Animasi perubahan
            satuanSelect.style.transform = 'scale(1.02)';
            setTimeout(() => satuanSelect.style.transform = '', 150);
            
            satuanSelect.value = defaultSatuan;
            satuanHint.innerHTML = `✅ <strong>${defaultSatuan}</strong> dipilih otomatis`;
            satuanHint.className = 'text-success fw-medium';
            
            // Update display harga
            if (satuanDisplay) satuanDisplay.textContent = defaultSatuan;
        } else {
            satuanSelect.value = '';
            satuanHint.innerHTML = '💡 Pilih satuan secara manual';
            satuanHint.className = 'text-muted';
            if (satuanDisplay) satuanDisplay.textContent = 'satuan';
        }
    }
    
    // ✅ FIX: Event listener menggunakan selected TEXT, bukan value (ID)
    kategoriSelect.addEventListener('change', (e) => {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const kategoriName = selectedOption.text.trim();
        autoFillSatuan(kategoriName);
    });
    
    // Update display harga saat satuan berubah manual
    satuanSelect.addEventListener('change', (e) => {
        if (satuanDisplay) satuanDisplay.textContent = e.target.value || 'satuan';
    });
    
    // Format harga dengan pemisah ribuan (opsional)
    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('blur', function() {
        if (this.value) {
            const num = parseInt(this.value.replace(/\D/g, ''));
            if (!isNaN(num)) this.value = num;
        }
    });
    
    // Validasi form dengan SweetAlert
    document.getElementById('formTambahBarang').addEventListener('submit', function(e) {
        const fields = [
            { id: 'nama_barang', label: 'Nama Barang' },
            { id: 'id_kategori', label: 'Kategori' }, // ✅ FIX: ID field
            { id: 'satuan', label: 'Satuan' },
            { id: 'harga', label: 'Harga' }
        ];
        
        const missing = fields.filter(f => !document.getElementById(f.id).value.trim());
        
        if (missing.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: '⚠️ Data Belum Lengkap',
                html: `Field berikut wajib diisi:<br><strong>${missing.map(f => f.label).join(', ')}</strong>`,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK, Saya Lengkapi'
            });
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...';
    });
    
    // Animasi form sections
    document.querySelectorAll('.form-section').forEach((section, index) => {
        section.style.animationDelay = `${index * 0.15}s`;
        section.style.opacity = '1';
    });
});
</script>