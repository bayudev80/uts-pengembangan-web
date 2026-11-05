<?php
$details   = $details ?? [];
$pageTitle = 'Detail Transaksi';
$activeNav = 'detail_transaksi';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Transaksi</h1>
        <p class="page-subtitle">Rincian item setiap transaksi sewa jas dari <strong>tb_detail_transaksi</strong></p>
    </div>
    <a href="index.php?action=detail_transaksi_create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Detail
    </a>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= ($flash['type'] ?? 'info') === 'error' ? 'error' : 'success' ?>">
        <i class="bi bi-<?= ($flash['type'] ?? 'info') === 'error' ? 'exclamation-triangle-fill' : 'check-circle-fill' ?>"></i>
        <span><?= htmlspecialchars($flash['message']) ?></span>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-header-title">
            <strong>Daftar Rincian Item Transaksi</strong>
            <span class="badge" style="background:#e2e8f0; color:#475569;"><?= count($details) ?> Baris</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="table-search-input" placeholder="Cari nama jas, customer, ukuran...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="70">ID</th>
                    <th width="80">Order #</th>
                    <th>Customer</th>
                    <th>Nama Jas</th>
                    <th width="80">Ukuran</th>
                    <th width="60">Hari</th>
                    <th>Harga/Hari</th>
                    <th>Subtotal</th>
                    <th width="80">Status</th>
                    <th width="200" style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details)): ?>
                    <?php foreach ($details as $d):
                        $st = strtolower($d['status_order'] ?? 'diproses');
                        $badgeCls = match($st) {
                            'disewa'       => 'badge-disewa',
                            'selesai'      => 'badge-selesai',
                            'dikembalikan' => 'badge-dikembalikan',
                            default        => 'badge-diproses',
                        };
                    ?>
                        <tr>
                            <td><span class="jas-badge-size">#<?= (int)$d['id_detail'] ?></span></td>
                            <td>
                                <a href="index.php?action=order_detail&id=<?= (int)$d['id_order'] ?>"
                                   style="font-weight:600; color:var(--primary); text-decoration:none;">
                                    #<?= (int)$d['id_order'] ?>
                                </a>
                            </td>
                            <td>
                                <span style="font-weight:600; color:var(--text-heading);">
                                    <?= htmlspecialchars($d['nama_customer'] ?? '-') ?>
                                </span>
                                <?php if (!empty($d['no_hp_customer'])): ?>
                                    <div style="font-size:0.75rem; color:var(--text-muted);"><?= htmlspecialchars($d['no_hp_customer']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-weight:600;"><?= htmlspecialchars($d['nama_jas']) ?></span>
                                <?php if (!empty($d['warna'])): ?>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">Warna: <?= htmlspecialchars($d['warna']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="jas-badge-size"><?= htmlspecialchars($d['ukuran']) ?></span>
                            </td>
                            <td style="text-align:center; font-weight:600;">
                                <?= (int)$d['lama_sewa'] ?> hr
                            </td>
                            <td style="color:var(--text-muted);">
                                Rp <?= number_format((float)$d['harga_sewa'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <strong style="color:var(--primary);">
                                    Rp <?= number_format((float)$d['subtotal'], 0, ',', '.') ?>
                                </strong>
                            </td>
                            <td><span class="badge <?= $badgeCls ?>"><?= ucfirst($st) ?></span></td>
                            <td style="text-align:right;">
                                <div class="btn-group" style="justify-content:flex-end;">
                                    <a href="index.php?action=detail_transaksi_detail&id=<?= (int)$d['id_detail'] ?>" class="btn btn-outline btn-sm">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="index.php?action=detail_transaksi_edit&id=<?= (int)$d['id_detail'] ?>" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?action=detail_transaksi_delete&id=<?= (int)$d['id_detail'] ?>"
                                       onclick="return confirm('Hapus detail transaksi #<?= (int)$d['id_detail'] ?>?');"
                                       class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-receipt-cutoff"></i></div>
                                <div class="empty-state-title">Belum ada detail transaksi</div>
                                <p>Klik tombol <strong>Tambah Detail</strong> di atas untuk menambahkan rincian item sewa.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer table-pagination" data-page-size="5">
        <div class="pagination-info">
            Menampilkan <span class="page-start">1</span> &ndash; <span class="page-end">5</span>
            dari <span class="page-total"><?= count($details) ?></span> data
        </div>
        <div class="pagination-controls">
            <button type="button" class="btn-page btn-prev" title="Halaman Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="page-numbers"></div>
            <button type="button" class="btn-page btn-next" title="Halaman Berikutnya">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

