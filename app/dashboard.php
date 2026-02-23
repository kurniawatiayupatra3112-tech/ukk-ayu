<?php
/**
 * Dashboard - Blue & Pink Theme ✨
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

<!-- Custom CSS untuk Theme Biru + Pink -->
<style>
:root {
    --blue-primary: #3b82f6;
    --blue-light: #dbeafe;
    --blue-dark: #1d4ed8;
    --pink-primary: #ec4899;
    --pink-light: #fce7f3;
    --pink-dark: #db2777;
    --gradient-blue-pink: linear-gradient(135deg, #3b82f6 0%, #ec4899 100%);
}

/* Page Header Gradient */
.page-header h1 {
    background: var(--gradient-blue-pink);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
}

.page-header p {
    color: #64748b;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-blue-pink);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.15);
    border-color: var(--blue-primary);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.stat-icon.primary {
    background: var(--blue-light);
    color: var(--blue-primary);
}

.stat-icon.success {
    background: var(--pink-light);
    color: var(--pink-primary);
}

.stat-icon.warning {
    background: linear-gradient(135deg, var(--blue-light), var(--pink-light));
    color: var(--pink-primary);
}

.stat-icon.info {
    background: var(--blue-light);
    color: var(--blue-primary);
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

/* Cards */
.card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    color: #334155;
    padding: 1rem 1.25rem;
}

/* Badges */
.badge.bg-blue {
    background: var(--blue-primary) !important;
}

.badge.bg-pink {
    background: var(--pink-primary) !important;
}

.text-blue {
    color: var(--blue-primary) !important;
}

.text-pink {
    color: var(--pink-primary) !important;
}

/* Buttons */
.btn-outline-primary {
    --bs-btn-color: var(--blue-primary);
    --bs-btn-border-color: var(--blue-primary);
    --bs-btn-hover-color: white;
    --bs-btn-hover-bg: var(--blue-primary);
    --bs-btn-hover-border-color: var(--blue-primary);
}

.btn-blue {
    background: var(--blue-primary);
    border-color: var(--blue-primary);
    color: white;
}

.btn-blue:hover {
    background: var(--blue-dark);
    border-color: var(--blue-dark);
    color: white;
}

.btn-pink {
    background: var(--pink-primary);
    border-color: var(--pink-primary);
    color: white;
}

.btn-pink:hover {
    background: var(--pink-dark);
    border-color: var(--pink-dark);
    color: white;
}

/* Quick Actions */
.btn-outline-primary.w-100 {
    border-radius: 10px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary.w-100:hover {
    background: var(--gradient-blue-pink);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

.btn-outline-primary.w-100:hover svg {
    stroke: white;
}

/* List Group */
.list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    border-left-color: var(--pink-primary);
    background: var(--pink-light);
}

/* Chart Container */
#chartTransaksi {
    max-height: 280px;
}

/* Animations */
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

.stat-card {
    animation: fadeInUp 0.4s ease forwards;
    opacity: 0;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; opacity: 1; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Responsive */
@media (max-width: 768px) {
    .stat-value {
        font-size: 1.5rem;
    }
    .page-header h1 {
        font-size: 1.5rem;
    }
}
</style>

<div class="page-header">
    <h1>✨ Dashboard</h1>
    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>! 👋</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <div class="stat-value"><?= number_format($total_stok) ?></div>
            <div class="stat-label">Total Stok</div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                            📭 Belum ada transaksi
                        </div>
                    <?php else: ?>
                        <?php foreach ($transaksi_terbaru as $t): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge <?= $t['jenis_transaksi'] === 'masuk' ? 'bg-blue' : 'bg-pink' ?> me-2">
                                            <?= $t['jenis_transaksi'] === 'masuk' ? '⬇️ Masuk' : '⬆️ Keluar' ?>
                                        </span>
                                        <strong><?= htmlspecialchars($t['nama_barang']) ?></strong>
                                    </div>
                                    <span class="<?= $t['jenis_transaksi'] === 'masuk' ? 'text-blue' : 'text-pink' ?> fw-bold">
                                        <?= $t['jenis_transaksi'] === 'masuk' ? '+' : '-' ?><?= number_format($t['jumlah']) ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    📅 <?= date('d/m/Y H:i', strtotime($t['tanggal_transaksi'])) ?>
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
                        <a href="<?= $base_url ?>/app/transaksi/masuk.php" class="btn btn-blue w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                            </svg>
                            Barang Masuk
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $base_url ?>/app/transaksi/keluar.php" class="btn btn-pink w-100">
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
                    label: '⬇️ Barang Masuk',
                    data: masukData,
                    backgroundColor: '#3b82f6', // Blue 500
                    borderRadius: 6,
                    borderSkipped: false
                },
                {
                    label: '⬆️ Barang Keluar',
                    data: keluarData,
                    backgroundColor: '#ec4899', // Pink 500
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
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' item';
                        }
                    }
                }
            },
            scales: {
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#e2e8f0', borderDash: [5, 5] },
                    ticks: { font: { size: 11 } }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>