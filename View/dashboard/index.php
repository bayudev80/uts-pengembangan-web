<?php
$pageTitle = 'Dashboard Utama';
$activeNav = 'dashboard';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Rental Jas</h1>
        <p class="page-subtitle">Ringkasan operasional bisnis, ketersediaan stok jas, dan pemantauan transaksi sewa</p>
    </div>
    <div class="btn-group">
        <a href="index.php?action=order_create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Order
        </a>
        <a href="katalog.php" target="_blank" class="btn btn-outline">
            <i class="bi bi-shop"></i> Web Pelanggan
        </a>
    </div>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= ($flash['type'] ?? 'info') === 'error' ? 'error' : 'success' ?>">
        <i class="bi bi-<?= ($flash['type'] ?? 'info') === 'error' ? 'exclamation-triangle-fill' : 'check-circle-fill' ?>"></i>
        <span><?= htmlspecialchars($flash['message']) ?></span>
    </div>
<?php endif; ?>

<!-- 4 Kartu Metrik Statistik Realtime Database Sewajas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #f0fdfa; color: #0d9488;">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Pendapatan</div>
            <div class="stat-value" style="color: #0d9488;">
                Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
            </div>
            <div class="stat-desc">Dari <?= $totalOrder ?> total transaksi order</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Perlu Verifikasi</div>
            <div class="stat-value" style="color: #d97706;">
                <?= $orderDiproses ?> <span style="font-size: 0.9rem; font-weight: 600;">Pesanan</span>
            </div>
            <div class="stat-desc"><?= $orderDisewa ?> pesanan sedang aktif disewa</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Stok Jas Tersedia</div>
            <div class="stat-value" style="color: #2563eb;">
                <?= $totalStokTersedia ?> <span style="font-size: 0.9rem; font-weight: 600;">Pcs</span>
            </div>
            <div class="stat-desc">Dari <?= $totalJasModel ?> varian koleksi jas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fdf2f8; color: #db2777;">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Total Pelanggan</div>
            <div class="stat-value" style="color: #db2777;">
                <?= $totalCustomer ?> <span style="font-size: 0.9rem; font-weight: 600;">Orang</span>
            </div>
            <div class="stat-desc"><?= $totalPengembalian ?> riwayat jas dikembalikan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #f1f5f9; color: #475569;">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div class="stat-info">
            <div class="stat-title">Petugas Admin</div>
            <div class="stat-value" style="color: #475569;">
                <?= $totalAdmin ?> <span style="font-size: 0.9rem; font-weight: 600;">Akun</span>
            </div>
            <div class="stat-desc"><a href="index.php?action=admin" style="color: var(--primary); text-decoration: none;">Kelola tb_admin &rarr;</a></div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
    <!-- Tabel Pesanan Terbaru -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <strong><i class="bi bi-receipt" style="color: var(--primary);"></i> Pesanan Sewa Terbaru</strong>
            </div>
            <a href="index.php?action=order" class="btn btn-outline btn-sm">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="70">ID</th>
                        <th>Pelanggan</th>
                        <th>Model Jas</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th width="80" style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentOrders)): ?>
                        <?php foreach ($recentOrders as $ro): 
                            $st = strtolower($ro['status'] ?? 'diproses');
                            $badgeCls = match($st) {
                                'disewa' => 'badge-disewa',
                                'selesai' => 'badge-selesai',
                                'dikembalikan' => 'badge-dikembalikan',
                                default => 'badge-diproses',
                            };
                        ?>
                            <tr>
                                <td><span class="jas-badge-size">#<?= (int)$ro['id_order'] ?></span></td>
                                <td>
                                    <strong style="color: var(--text-heading);"><?= htmlspecialchars($ro['nama_customer'] ?? '(Customer)') ?></strong>
                                    <?php if (!empty($ro['no_hp_customer'])): ?>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($ro['no_hp_customer']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-weight: 600;"><?= htmlspecialchars($ro['Nama_jas'] ?? '-') ?></span>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">Ukuran: <?= htmlspecialchars($ro['ukuran_sewa'] ?? '-') ?></div>
                                </td>
                                <td><span class="badge <?= $badgeCls ?>"><?= ucfirst($st) ?></span></td>
                                <td><strong style="color: var(--primary);">Rp <?= number_format((float)$ro['total_harga'], 0, ',', '.') ?></strong></td>
                                <td style="text-align: right;">
                                    <a href="index.php?action=order_detail&id=<?= (int)$ro['id_order'] ?>" class="btn btn-outline btn-sm" title="Buka Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state" style="padding: 2rem;">
                                    <p>Belum ada transaksi sewa jas.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Koleksi Jas & Stok Ready -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <strong><i class="bi bi-tag-fill" style="color: var(--primary);"></i> Koleksi Jas</strong>
            </div>
            <a href="index.php?action=jas" class="btn btn-outline btn-sm">
                Kelola <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body" style="padding: 1rem 1.25rem;">
            <?php if (!empty($popularJas)): ?>
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <?php foreach ($popularJas as $pj): 
                        $stokJas = (int)($pj['Stok'] ?? 0);
                    ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.85rem; border-bottom: 1px dashed var(--border-color);">
                            <div>
                                <strong style="color: var(--text-heading); font-size: 0.9rem; display: block;">
                                    <?= htmlspecialchars($pj['Nama_jas']) ?>
                                </strong>
                                <small style="color: var(--text-muted); font-size: 0.75rem;">
                                    <?= htmlspecialchars($pj['kategori'] ?? 'Formal') ?> &bull; <?= htmlspecialchars($pj['Warna_jas'] ?? '') ?>
                                </small>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: var(--primary); font-size: 0.85rem;">
                                    Rp <?= number_format((float)$pj['Harga_sewa'], 0, ',', '.') ?>
                                </div>
                                <?php if ($stokJas > 0): ?>
                                    <span class="badge badge-stok-ready" style="font-size: 0.68rem; padding: 2px 6px;">Stok <?= $stokJas ?></span>
                                <?php else: ?>
                                    <span class="badge badge-stok-empty" style="font-size: 0.68rem; padding: 2px 6px;">Habis</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state" style="padding: 1.5rem;">
                    <p>Katalog jas masih kosong.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

