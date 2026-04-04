<?php
/**
 * Riwayat Transaksi dengan Search dan Pagination (Max 10)
 */

$page_title = 'Riwayat Transaksi';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/koneksi.php';

$filter_jenis = $_GET['jenis'] ?? '';
$filter_dari = $_GET['dari'] ?? date('Y-m-01');
$filter_sampai = $_GET['sampai'] ?? date('Y-m-d');

$sql = "SELECT t.*, b.nama_barang 
        FROM transaksi t 
        JOIN barang b ON t.id_barang = b.id 
        WHERE DATE(t.tanggal_transaksi) BETWEEN ? AND ?";
$params = [$filter_dari, $filter_sampai];

if ($filter_jenis) {
    $sql .= " AND t.jenis_transaksi = ?";
    $params[] = $filter_jenis;
}

$sql .= " ORDER BY t.tanggal_transaksi DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data_transaksi = $stmt->fetchAll();

$total_masuk = 0;
$total_keluar = 0;
foreach ($data_transaksi as $t) {
    if ($t['jenis_transaksi'] === 'masuk') {
        $total_masuk += $t['jumlah'];
    } else {
        $total_keluar += $t['jumlah'];
    }
}
?>

<div class="page-header">
    <h1>Riwayat Transaksi</h1>
    <p>Catatan semua transaksi barang</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua</option>
                    <option value="masuk" <?= $filter_jenis === 'masuk' ? 'selected' : '' ?>>Masuk</option>
                    <option value="keluar" <?= $filter_jenis === 'keluar' ? 'selected' : '' ?>>Keluar</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dari</label>
                <input type="date" name="dari" class="form-control" value="<?= $filter_dari ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai</label>
                <input type="date" name="sampai" class="form-control" value="<?= $filter_sampai ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card" style="background:#dcfce7">
            <div class="card-body text-center">
                <h6>Total Masuk</h6>
                <h3 class="text-success">+<?= number_format($total_masuk) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="background:#fee2e2">
            <div class="card-body text-center">
                <h6>Total Keluar</h6>
                <h3 class="text-danger">-<?= number_format($total_keluar) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="background:#eff6ff">
            <div class="card-body text-center">
                <h6>Selisih</h6>
                <h3>
                    <?= number_format($total_masuk - $total_keluar) ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        <table id="tabelTransaksi" class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($data_transaksi as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($t['tanggal_transaksi'])) ?></td>
                    <td><?= htmlspecialchars($t['nama_barang']) ?></td>
                    <td><?= ucfirst($t['jenis_transaksi']) ?></td>
                    <td><?= $t['jumlah'] ?></td>
                    <td><?= htmlspecialchars($t['keterangan'] ?: '-') ?></td>
                    


                    
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


<script>
$(document).ready(function() {
    $('#tabelTransaksi').DataTable();

    $('#modalDetail').on('show.bs.modal', function (event) {
        let btn = $(event.relatedTarget);
        $('#dNama').text(btn.data('nama'));
        $('#dTanggal').text(btn.data('tanggal'));
        $('#dJenis').text(btn.data('jenis'));
        $('#dJumlah').text(btn.data('jumlah'));
        $('#dKeterangan').text(btn.data('keterangan'));
    });
});
</script>
