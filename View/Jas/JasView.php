<?php
$dataJas = $dataJas ?? [];
$pageTitle = 'Katalog Jas';
$activeNav = 'jas';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Katalog Jas</h1>
        <p class="page-subtitle">Daftar koleksi jas, ukuran, tarif sewa, dan ketersediaan stok</p>
    </div>
    <a href="index.php?action=jas_create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Jas Baru
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
            <strong>Daftar Koleksi Jas</strong>
            <span class="badge" style="background:#e2e8f0; color:#475569;"><?= count($dataJas) ?> Koleksi</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="table-search-input" placeholder="Cari model, ukuran, warna...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="70">No</th>
                    <th>Nama Jas</th>
                    <th>Kategori</th>
                    <th>Ukuran</th>
                    <th>Warna</th>
                    <th>Tarif Sewa</th>
                    <th>Status Stok</th>
                    <th width="220" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataJas)): ?>
                    <?php foreach ($dataJas as $i => $j): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <a href="index.php?action=jas_detail&id=<?= (int)$j['id_jas'] ?>" style="font-weight: 600; color: var(--text-heading); text-decoration: none;">
                                    <i class="bi bi-tag-fill" style="color: var(--primary);"></i> <?= htmlspecialchars((string)($j['Nama_jas'] ?? '')) ?>
                                </a>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #<?= (int)$j['id_jas'] ?></div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1;">
                                    <i class="fa fa-tag me-1"></i><?= htmlspecialchars((string)($j['kategori'] ?? 'Formal')) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    <?php 
                                    $tblSizes = array_filter(array_map('trim', explode(',', (string)($j['Ukuran_jas'] ?? ''))));
                                    if (empty($tblSizes)) $tblSizes = [(string)($j['Ukuran_jas'] ?? '-')];
                                    foreach ($tblSizes as $tsz):
                                    ?>
                                        <span class="jas-badge-size"><?= htmlspecialchars($tsz) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars((string)($j['Warna_jas'] ?? '-')) ?></td>
                            <td>
                                <strong style="color: var(--primary);">
                                    Rp <?= number_format((float)($j['Harga_sewa'] ?? 0), 0, ',', '.') ?>
                                </strong>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">/hari</span>
                            </td>
                            <td>
                                <?php $stok = (int)($j['Stok'] ?? 0); ?>
                                <?php if ($stok > 0): ?>
                                    <span class="badge badge-stok-ready"><i class="bi bi-check-circle"></i> Tersedia (<?= $stok ?>)</span>
                                <?php else: ?>
                                    <span class="badge badge-stok-empty"><i class="bi bi-x-circle"></i> Habis (0)</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group" style="justify-content: flex-end;">
                                    <a href="index.php?action=jas_detail&id=<?= (int)$j['id_jas'] ?>" class="btn btn-outline btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="index.php?action=jas_edit&id=<?= (int)$j['id_jas'] ?>" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?action=jas_delete&id=<?= (int)$j['id_jas'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus jas <?= htmlspecialchars(addslashes($j['Nama_jas'] ?? '')) ?>?');" 
                                       class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-box-seam"></i></div>
                                <div class="empty-state-title">Katalog jas masih kosong</div>
                                <p>Silakan tambahkan jas baru untuk mulai menyewakan.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer table-pagination" data-page-size="5">
        <div class="pagination-info">
            Menampilkan <span class="page-start">1</span> &ndash; <span class="page-end">5</span> dari <span class="page-total"><?= count($dataJas) ?></span> data
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
