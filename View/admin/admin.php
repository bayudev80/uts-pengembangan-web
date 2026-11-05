<?php
$admins = $admins ?? [];
$pageTitle = 'Kelola Admin';
$activeNav = 'admin';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Admin</h1>
        <p class="page-subtitle">Daftar akun administrator pengelola sistem persewaan</p>
    </div>
    <a href="index.php?action=admin_create" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Tambah Admin Baru
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
            <strong>Daftar Pengguna Administrator</strong>
            <span class="badge" style="background:#e2e8f0; color:#475569;"><?= count($admins) ?> Admin</span>
        </div>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="table-search-input" placeholder="Cari nama admin, email...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama Admin</th>
                    <th>Alamat Email</th>
                    <th width="220" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($admins)): ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td>
                                <span class="jas-badge-size">#<?= (int)$admin['id_admin'] ?></span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar" style="background: #f0fdfa; color: #0d9488; border: 1px solid #ccfbf1;">
                                        <?= strtoupper(substr($admin['nama'] ?? 'A', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <a href="index.php?action=admin_detail&id=<?= (int)$admin['id_admin'] ?>" style="font-weight: 600; color: var(--text-heading); text-decoration: none;">
                                            <?= htmlspecialchars((string)($admin['nama'] ?? '')) ?>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars((string)($admin['email'] ?? '')) ?>" style="color: var(--primary); text-decoration: none;">
                                    <i class="bi bi-envelope"></i> <?= htmlspecialchars((string)($admin['email'] ?? '')) ?>
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group" style="justify-content: flex-end;">
                                    <a href="index.php?action=admin_detail&id=<?= (int)$admin['id_admin'] ?>" class="btn btn-outline btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="index.php?action=admin_edit&id=<?= (int)$admin['id_admin'] ?>" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?action=admin_delete&id=<?= (int)$admin['id_admin'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus admin <?= htmlspecialchars(addslashes($admin['nama'] ?? '')) ?>?');" 
                                       class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-shield-lock"></i></div>
                                <div class="empty-state-title">Belum ada akun admin</div>
                                <p>Klik tombol tambah di atas untuk mendaftarkan akun pengelola baru.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer table-pagination" data-page-size="5">
        <div class="pagination-info">
            Menampilkan <span class="page-start">1</span> &ndash; <span class="page-end">5</span> dari <span class="page-total"><?= count($admins) ?></span> data
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
