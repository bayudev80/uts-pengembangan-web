<?php
$pageTitle = 'Data Customer';
$activeNav = 'customer';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Data Customer</h1>
        <p class="page-subtitle">Kelola seluruh data pelanggan penyewa jas</p>
    </div>
    <a href="index.php?action=create" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Tambah Customer Baru
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
            <strong>Daftar Pelanggan</strong>
            <span class="badge" style="background:#e2e8f0; color:#475569;"><?= count($customers ?? []) ?> Total Customer</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="table-search-input" placeholder="Cari nama, HP, KTP...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama Customer</th>
                    <th>Nomor WhatsApp / HP</th>
                    <th>Identitas Jaminan</th>
                    <th width="220" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td>
                                <span class="jas-badge-size">#<?= (int)$c['id_customer'] ?></span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar">
                                        <?= strtoupper(substr($c['nama_customer'] ?? 'C', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <a href="index.php?action=detail&id=<?= (int)$c['id_customer'] ?>" style="font-weight: 600; color: var(--text-heading); text-decoration: none;">
                                            <?= htmlspecialchars($c['nama_customer']) ?>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($c['no_hp'] ?? '-') ?></td>
                            <td>
                                <?php 
                                $idFile = $c['KTP'] ?? ''; 
                                $isImg = !empty($idFile) && (strpos($idFile, 'uploads/') !== false || preg_match('/\.(jpg|jpeg|png|webp)$/i', $idFile));
                                ?>
                                <?php if ($isImg): ?>
                                    <a href="<?= htmlspecialchars($idFile) ?>" target="_blank" class="badge" style="background:#f0fdfa; color:#0d9488; border:1px solid #ccfbf1; text-decoration:none; display:inline-flex; align-items:center; gap:5px; padding:4px 8px;" title="Lihat Foto Identitas Jaminan">
                                        <i class="bi bi-image"></i> Foto Identitas
                                    </a>
                                <?php elseif (!empty($idFile)): ?>
                                    <code><?= htmlspecialchars($idFile) ?></code>
                                <?php else: ?>
                                    <span style="color:var(--text-muted);">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group" style="justify-content: flex-end;">
                                    <a href="index.php?action=detail&id=<?= (int)$c['id_customer'] ?>" class="btn btn-outline btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="index.php?action=edit&id=<?= (int)$c['id_customer'] ?>" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?action=delete&id=<?= (int)$c['id_customer'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data customer <?= htmlspecialchars(addslashes($c['nama_customer'])) ?>?');" 
                                       class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                <div class="empty-state-title">Belum ada data customer</div>
                                <p>Klik tombol tambah di atas untuk mendaftarkan pelanggan pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer table-pagination" data-page-size="5">
        <div class="pagination-info">
            Menampilkan <span class="page-start">1</span> &ndash; <span class="page-end">5</span> dari <span class="page-total"><?= count($customers ?? []) ?></span> data
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
