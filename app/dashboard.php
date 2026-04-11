<?php
/**
 * Dashboard - Blue+Pink Soft Theme
 * Clean design with Heroicons
 */

$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/koneksi.php';

// Statistik
$stmt = $pdo->query("SELECT COUNT(*) as total FROM barang");
$total_barang = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(stok) as total FROM barang");
$total_stok = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()");
$transaksi_hari_ini = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM barang WHERE stok < 10");
$stok_rendah = $stmt->fetch()['total'];

// Chart data
$stmt = $pdo->query("
    SELECT 
        DATE(tanggal_transaksi) as tanggal,
        SUM(CASE WHEN jenis_transaksi = 'masuk' THEN jumlah ELSE 0 END) as masuk,
        SUM(CASE WHEN jenis_transaksi = 'keluar' THEN jumlah ELSE 0 END) as keluar
    FROM transaksi 
    WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(tanggal_transaksi)
    ORDER BY tanggal ASC
");
$chart_data = $stmt->fetchAll();

// Transaksi Terbaru
$stmt = $pdo->query("
    SELECT t.*, b.nama_barang 
    FROM transaksi t 
    JOIN barang b ON t.id_barang = b.id 
    ORDER BY t.tanggal_transaksi DESC 
    LIMIT 5
");
$transaksi_terbaru = $stmt->fetchAll();
?>

<!-- 💙💖 Blue+Pink Soft Gradient CSS -->
<style>
/* ===== BACKGROUND - BLUE+PINK SOFT GRADIENT ===== */
body {
    background: linear-gradient(135deg, 
        #f0f9ff 0%,      /* Soft Blue */
        #fdf2f8 25%,     /* Soft Pink */
        #f0f9ff 50%,     /* Soft Blue */
        #fce7f3 75%,     /* Soft Pink */
        #f0f9ff 100%     /* Soft Blue */
    );
    background-size: 400% 400%;
    animation: gradientFlow 25s ease infinite;
    min-height: 100vh;
    position: relative;
}

@keyframes gradientFlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Soft Floating Blobs */
body::before {
    content: '';
    position: fixed;
    top: -200px;
    right: -100px;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(236, 183, 216, 0.3) 0%, transparent 70%);
    animation: floatBlob 30s ease-in-out infinite;
    z-index: -1;
    pointer-events: none;
}

body::after {
    content: '';
    position: fixed;
    bottom: -150px;
    left: -50px;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(191, 219, 254, 0.3) 0%, transparent 70%);
    animation: floatBlob 25s ease-in-out infinite reverse;
    z-index: -1;
    pointer-events: none;
}

@keyframes floatBlob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -20px) scale(1.05); }
    66% { transform: translate(-20px, 25px) scale(0.95); }
}

/* ===== CARD STYLING ===== */
.card, .stat-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(147, 51, 234, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover, .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(147, 51, 234, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    font-weight: 600;
    color: #1e293b;
    border-radius: 16px 16px 0 0 !important;
}

/* ===== STAT CARD ===== */
.stat-card {
    padding: 20px;
    text-align: center;
    border-radius: 16px;
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
    background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
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

/* ===== PAGE HEADER ===== */
.page-header {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 20px 24px;
    border-radius: 16px;
    margin-bottom: 24px;
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 2px 12px rgba(147, 51, 234, 0.06);
}

.page-header h1 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

/* ===== BUTTONS ===== */
.btn-outline-primary {
    border-color: #c084fc;
    color: #7c3aed;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 16px rgba(147, 51, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
    border: none;
    box-shadow: 0 4px 16px rgba(147, 51, 234, 0.2);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #9333ea 0%, #4f46e5 100%);
    box-shadow: 0 6px 24px rgba(147, 51, 234, 0.35);
}

/* ===== LIST GROUP ===== */
.list-group-item {
    border-color: #f1f5f9;
    background: transparent;
    transition: background 0.2s ease;
}

.list-group-item:hover {
    background: rgba(248, 250, 252, 0.8);
}

/* ===== BADGES ===== */
.badge.bg-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    body::before,
    body::after {
        display: none;
    }
    .page-header {
        padding: 16px 20px;
    }
    .stat-card {
        padding: 16px;
    }
}

/* ===== REDUCED MOTION ===== */
@media (prefers-reduced-motion: reduce) {
    body,
    body::before,
    body::after,
    .card,
    .stat-card {
        animation: none !important;
        transition: none !important;
    }
}
</style>

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>! 👋</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
            <div class="stat-value"><?= number_format($total_barang) ?></div>
            <div class="stat-label">Total Barang</div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <div class="stat-value"><?= number_format($total_stok) ?></div>
            <div class="stat-label">Total Stok</div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
            </div>
            <div class="stat-value"><?= number_format($transaksi_hari_ini) ?></div>
            <div class="stat-label">Transaksi Hari Ini</div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="stat-value"><?= number_format($stok_rendah) ?></div>
            <div class="stat-label">Stok Rendah</div>
        </div>
    </div>
</div>

<!-- Charts & Recent -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">📊 Grafik Transaksi 7 Hari Terakhir</div>
            <div class="card-body">
                <canvas id="chartTransaksi" height="280"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>🕐 Transaksi Terbaru</span>
                <a href="<?= $base_url ?>/app/transaksi/riwayat.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($transaksi_terbaru)): ?>
                        <div class="list-group-item text-center text-muted py-4">
                            Belum ada transaksi
                        </div>
                    <?php else: ?>
                        <?php foreach ($transaksi_terbaru as $t): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge <?= $t['jenis_transaksi'] === 'masuk' ? 'bg-success' : 'bg-danger' ?> me-2">
                                            <?= $t['jenis_transaksi'] === 'masuk' ? '📥 Masuk' : '📤 Keluar' ?>
                                        </span>
                                        <strong><?= htmlspecialchars($t['nama_barang']) ?></strong>
                                    </div>
                                    <span class="<?= $t['jenis_transaksi'] === 'masuk' ? 'text-success' : 'text-danger' ?> fw-bold">
                                        <?= $t['jenis_transaksi'] === 'masuk' ? '+' : '-' ?><?= number_format($t['jumlah']) ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($t['tanggal_transaksi'])) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">⚡ Aksi Cepat</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="<?= $base_url ?>/app/barang/tambah.php" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Barang
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $base_url ?>/app/transaksi/masuk.php" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                            </svg>
                            Barang Masuk
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $base_url ?>/app/transaksi/keluar.php" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                            </svg>
                            Barang Keluar
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $base_url ?>/app/laporan/" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Simpan data chart untuk digunakan setelah footer loaded
$chart_json = json_encode($chart_data);
?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- Chart Script - HARUS setelah footer karena Chart.js dimuat di footer -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = <?= $chart_json ?>;
    
    // Jika tidak ada data, tampilkan pesan
    if (chartData.length === 0) {
        document.getElementById('chartTransaksi').parentElement.innerHTML = 
            '<div class="text-center text-muted py-5">📭 Belum ada data transaksi 7 hari terakhir</div>';
        return;
    }
    
    const labels = chartData.map(d => {
        const date = new Date(d.tanggal);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    const masukData = chartData.map(d => parseInt(d.masuk));
    const keluarData = chartData.map(d => parseInt(d.keluar));
    
    const ctx = document.getElementById('chartTransaksi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '📥 Barang Masuk',
                    data: masukData,
                    backgroundColor: '#22c55e',
                    borderRadius: 6,
                    borderSkipped: false
                },
                {
                    label: '📤 Barang Keluar',
                    data: keluarData,
                    backgroundColor: '#ef4444',
                    borderRadius: 6,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        font: { family: 'Inter', size: 12 },
                        color: '#475569'
                    }
                }
            },
            scales: {
                x: { 
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#e2e8f0' },
                    ticks: { color: '#64748b', font: { size: 11 } }
                }
            }
        }
    });
});
</script>