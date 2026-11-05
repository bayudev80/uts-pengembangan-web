<?php
$dataPengembalian = $dataPengembalian ?? [];
$pageTitle = 'Data Pengembalian';
$activeNav = 'pengembalian';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Data Pengembalian Jas</h1>
        <p class="page-subtitle">Pencatatan pengembalian jas, kondisi barang, dan perhitungan denda</p>
    </div>
    <a href="index.php?action=pengembalian_create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Catat Pengembalian Baru
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
            <strong>Daftar Riwayat Pengembalian</strong>
            <span class="badge" style="background:#e2e8f0; color:#475569;"><?= count($dataPengembalian) ?> Transaksi</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="table-search-input" placeholder="Cari order, customer, kondisi...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>ID Order</th>
                    <th>Customer & Jas</th>
                    <th>Tanggal Dikembalikan</th>
                    <th>Kondisi Jas</th>
                    <th>Denda</th>
                    <th width="220" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataPengembalian)): ?>
                    <?php foreach ($dataPengembalian as $p): ?>
                        <tr>
                            <td>
                                <span class="jas-badge-size">#<?= (int)$p['id_pengembalian'] ?></span>
                            </td>
                            <td>
                                <a href="index.php?action=order_detail&id=<?= (int)$p['id_order'] ?>" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                                    Order #<?= (int)$p['id_order'] ?>
                                </a>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    Batas: <?= htmlspecialchars($p['batas_kembali'] ?? '-') ?>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-heading);"><?= htmlspecialchars($p['nama_customer'] ?? '(Customer)') ?></strong>
                                <div style="font-size: 0.775rem; color: var(--text-muted);">
                                    <i class="bi bi-tag-fill" style="color: var(--primary);"></i> <?= htmlspecialchars($p['Nama_jas'] ?? '-') ?>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-calendar-check"></i> <?= htmlspecialchars($p['tanggal_dikembalikan']) ?>
                            </td>
                            <td>
                                <?php
                                $kondisi = strtolower($p['kondisi_jas'] ?? 'baik');
                                $kondisiBadge = match($kondisi) {
                                    'baik' => 'badge-selesai',
                                    'kotor' => 'badge-diproses',
                                    'rusak' => 'badge-dikembalikan',
                                    'hilang' => 'badge-stok-empty',
                                    default => 'badge-diproses',
                                };
                                ?>
                                <span class="badge <?= $kondisiBadge ?>"><?= ucfirst(htmlspecialchars($kondisi)) ?></span>
                            </td>
                            <td>
                                <?php $denda = (float)($p['denda'] ?? 0); ?>
                                <?php if ($denda > 0): ?>
                                    <strong style="color: var(--danger);">Rp <?= number_format($denda, 0, ',', '.') ?></strong>
                                <?php else: ?>
                                    <span style="color: var(--success); font-weight: 600;">Rp 0 (Tepat Waktu)</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group" style="justify-content: flex-end;">
                                    <a href="index.php?action=pengembalian_detail&id=<?= (int)$p['id_pengembalian'] ?>" class="btn btn-outline btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="index.php?action=pengembalian_edit&id=<?= (int)$p['id_pengembalian'] ?>" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?action=pengembalian_delete&id=<?= (int)$p['id_pengembalian'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data pengembalian #<?= (int)$p['id_pengembalian'] ?>?');" 
                                       class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-arrow-return-left"></i></div>
                                <div class="empty-state-title">Belum ada catatan pengembalian</div>
                                <p>Catat pengembalian jas yang telah dikembalikan oleh pelanggan.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer table-pagination" data-page-size="5">
        <div class="pagination-info">
            Menampilkan <span class="page-start">1</span> &ndash; <span class="page-end">5</span> dari <span class="page-total"><?= count($dataPengembalian) ?></span> data
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
