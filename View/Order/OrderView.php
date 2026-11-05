<?php
$orders = $orders ?? [];
$pageTitle = 'Transaksi Order';
$activeNav = 'order';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Transaksi Order</h1>
        <p class="page-subtitle">Kelola dan pantau seluruh transaksi persewaan jas pelanggan</p>
    </div>
    <a href="index.php?action=order_create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Order Baru
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
            <strong>Riwayat & Daftar Order Aktif</strong>
            <span class="badge" style="background:#e2e8f0; color:#475569;"><?= count($orders) ?> Total Transaksi</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="table-search-input" placeholder="Cari nama, jas, status, ID...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">ID Order</th>
                    <th>Nama Customer</th>
                    <th>Jas yang Disewa</th>
                    <th>Periode Sewa</th>
                    <th>Status</th>
                    <th>Total Biaya</th>
                    <th width="220" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td>
                                <span class="jas-badge-size">#<?= (int)$o['id_order'] ?></span>
                            </td>
                            <td>
                                <strong style="color: var(--text-heading);"><?= htmlspecialchars($o['nama_customer'] ?? '(Pelanggan Tidak Ditemukan)') ?></strong>
                                <?php if (!empty($o['no_hp_customer'])): ?>
                                    <div style="font-size: 0.775rem; color: var(--text-muted);"><i class="bi bi-telephone"></i> <?= htmlspecialchars($o['no_hp_customer']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($o['Nama_jas'])): ?>
                                    <div style="font-weight: 600;"><i class="bi bi-tag-fill" style="color: var(--primary);"></i> <?= htmlspecialchars($o['Nama_jas']) ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        Ukuran Sewa: <strong style="color: var(--primary);"><?= htmlspecialchars(!empty($o['ukuran_sewa']) ? $o['ukuran_sewa'] : ($o['Ukuran_jas'] ?? '-')) ?></strong> &bull; Warna: <?= htmlspecialchars($o['Warna_jas'] ?? '-') ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-style: italic;">- Belum Ditentukan -</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-size: 0.825rem;">
                                    <span><i class="bi bi-calendar3"></i> <?= htmlspecialchars($o['tanggal_sewa']) ?></span>
                                    <div style="color: var(--text-muted);">s/d <?= htmlspecialchars($o['tanggal_kembali']) ?></div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $status = strtolower($o['status'] ?? '');
                                if ($status === 'disewa'):
                                ?>
                                    <span class="badge badge-disewa"><i class="bi bi-clock-history"></i> Disewa</span>
                                <?php elseif ($status === 'selesai' || $status === 'dikembalikan'): ?>
                                    <span class="badge <?= ($status === 'selesai') ? 'badge-selesai' : 'badge-dikembalikan' ?>">
                                        <i class="bi bi-check2-circle"></i> <?= ucfirst($status) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-diproses"><i class="bi bi-hourglass-split"></i> Diproses</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: var(--primary);">
                                    Rp <?= number_format((float)($o['total_harga'] ?? 0), 0, ',', '.') ?>
                                </strong>
                                <div style="margin-top: 2px;">
                                    <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.68rem; text-transform: uppercase;">
                                        <?= htmlspecialchars($o['tipe_pembayaran'] ?? 'lunas') ?> &bull; <?= htmlspecialchars($o['metode_pembayaran'] ?? 'toko') ?>
                                    </span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group" style="justify-content: flex-end;">
                                    <a href="index.php?action=order_detail&id=<?= (int)$o['id_order'] ?>" class="btn btn-outline btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="index.php?action=order_edit&id=<?= (int)$o['id_order'] ?>" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?action=order_delete&id=<?= (int)$o['id_order'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data order #<?= (int)$o['id_order'] ?> ini?');" 
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
                                <div class="empty-state-icon"><i class="bi bi-receipt"></i></div>
                                <div class="empty-state-title">Belum ada transaksi order</div>
                                <p>Klik tombol tambah di atas untuk mencatat pesanan sewa jas pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer table-pagination" data-page-size="5">
        <div class="pagination-info">
            Menampilkan <span class="page-start">1</span> &ndash; <span class="page-end">5</span> dari <span class="page-total"><?= count($orders) ?></span> data
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
