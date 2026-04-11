<?php
/**
 * Data Barang - List dengan Search, Pagination, dan Export
 * Soft Blue Theme
 */

$page_title = 'Data Barang';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/koneksi.php';

$stmt = $pdo->query("
    SELECT 
        b.*,
        k.nama_kategori
    FROM barang b
    LEFT JOIN kategori k ON b.id_kategori = k.id
    ORDER BY b.id DESC
");
$data_barang = $stmt->fetchAll();

// Hitung total nilai
$total_nilai = 0;
foreach ($data_barang as $b) {
    $total_nilai += $b['stok'] * $b['harga'];
}
?>

<!-- 💙 Soft Blue Theme CSS -->
<style>
/* ===== BACKGROUND - SOFT BLUE ===== */
body {
    background: #eff6ff !important; /* Soft Sky Blue */
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
}

.card-body {
    padding: 20px;
}

/* ===== STAT CARD ===== */
.stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.08), 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.12), 0 4px 16px rgba(0, 0, 0, 0.05);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
}

.stat-icon.primary {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
}

.stat-icon.success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: white;
}

.stat-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}

/* ===== TABLE STYLING ===== */
.table {
    margin-bottom: 0;
}

.table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e2e8f0;
    color: #334155;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    padding: 14px 16px;
}

.table tbody td {
    border-color: #f1f5f9;
    color: #334155;
    font-size: 14px;
    padding: 14px 16px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: rgba(240, 249, 255, 0.6);
}

/* ===== BADGES ===== */
.badge.bg-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

/* ===== BUTTONS ===== */
.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
    transform: translateY(-2px);
}

.btn-outline-primary {
    border-color: #93c5fd;
    color: #2563eb;
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 500;
    font-size: 13px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-outline-primary:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

.btn-outline-danger {
    border-color: #fca5a5;
    color: #dc2626;
    border-radius: 10px 0 0 10px;
    padding: 10px 14px;
    font-weight: 500;
    font-size: 13px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-outline-danger:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
}

.btn-outline-success {
    border-color: #86efac;
    color: #16a34a;
    border-radius: 0 10px 10px 0;
    padding: 10px 14px;
    font-weight: 500;
    font-size: 13px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-left: none;
}

.btn-outline-success:hover {
    background: #22c55e;
    border-color: #22c55e;
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    font-size: 12px;
    padding: 6px 14px;
    transition: all 0.2s ease;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    font-size: 12px;
    padding: 6px 14px;
    transition: all 0.2s ease;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-1px);
}

/* ===== DATATABLES OVERRIDES ===== */
.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 14px;
    color: #334155;
    transition: all 0.2s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #60a5fa;
    box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.15);
    outline: none;
}

.dataTables_wrapper .dataTables_length select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 14px;
    color: #334155;
    background: #ffffff;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    margin: 0 3px;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff !important;
    color: #334155 !important;
    transition: all 0.2s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #eff6ff !important;
    border-color: #93c5fd !important;
    color: #2563eb !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    border-color: #3b82f6 !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.dataTables_wrapper .dataTables_info {
    color: #64748b;
    font-size: 13px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 16px;
        padding: 16px 20px;
    }
    
    .page-header h1 {
        font-size: 20px;
    }
    
    .stat-card {
        padding: 16px;
    }
    
    .card-body {
        padding: 16px;
    }
}
</style>

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>📦 Data Barang</h1>
        <p>Kelola semua data barang di gudang</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="btn-group">
            <a href="export_pdf.php" class="btn btn-outline-danger" target="_blank" title="Export PDF">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                PDF
            </a>
            <a href="export_excel.php" class="btn btn-outline-success" title="Export Excel">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                </svg>
                Excel
            </a>
        </div>
        <a href="tambah.php" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Barang
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
            <div class="stat-value"><?= count($data_barang) ?></div>
            <div class="stat-label">Jenis Barang</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <div class="stat-value"><?= number_format(array_sum(array_column($data_barang, 'stok'))) ?></div>
            <div class="stat-label">Total Stok</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="stat-value" style="font-size: 18px;">Rp <?= number_format($total_nilai, 2, ',', '.') ?></div>
            <div class="stat-label">Nilai Inventaris</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelBarang" class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th width="12%">Stok</th>
                        <th width="18%">Harga</th>
                        <th width="15%">Terakhir Update</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_barang as $barang): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($barang['nama_barang']) ?></strong></td>
                        <td><?= htmlspecialchars($barang['nama_kategori'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($barang['satuan']) ?></td>
                        <td>
                            <?php
                                $stok = (int)$barang['stok'];
                                if ($stok > 10) {
                                    $badge = 'success';
                                } elseif ($stok > 0) {
                                    $badge = 'warning';
                                } else {
                                    $badge = 'danger';
                                }
                            ?>
                            <span class="badge bg-<?= $badge ?>">
                                <?= $stok ?>
                            </span>
                        </td>
                        <td>Rp <?= number_format($barang['harga'], 2, ',', '.') ?></td>
                        <td>
                            <small class="text-muted">
                                <?= date('d/m/Y H:i', strtotime($barang['updated_at'])) ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="edit.php?id=<?= $barang['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <button onclick="hapusBarang(<?= $barang['id'] ?>, '<?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES) ?>')" class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<!-- DataTables Init -->
<script>
$(document).ready(function() {
    $('#tabelBarang').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            search: "🔍 Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                first: "⏮ Pertama",
                last: "Terakhir ⏭",
                next: "Selanjutnya →",
                previous: "← Sebelumnya"
            }
        },
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        searching: true,
        paging: true,
        info: true
    });
});

function hapusBarang(id, nama) {
    Swal.fire({
        title: 'Hapus Barang?',
        html: `Yakin ingin menghapus <strong>${nama}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4',
            confirmButton: 'rounded-3',
            cancelButton: 'rounded-3'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus.php?id=' + id;
        }
    });
}
</script>