<?php
$pageTitle = 'Detail Admin - ' . htmlspecialchars($admin['nama'] ?? '');
$activeNav = 'admin';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Administrator</h1>
        <p class="page-subtitle">Informasi profil akun administrator #<?= (int)$admin['id_admin'] ?></p>
    </div>
    <div class="btn-group">
        <a href="index.php?action=admin_edit&id=<?= (int)$admin['id_admin'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit Admin
        </a>
        <a href="index.php?action=admin_delete&id=<?= (int)$admin['id_admin'] ?>" 
           onclick="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?');" 
           class="btn btn-danger">
            <i class="bi bi-trash"></i> Hapus
        </a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="avatar" style="width: 48px; height: 48px; font-size: 1.2rem; background: #f0fdfa; color: #0d9488; border: 1px solid #ccfbf1;">
                <?= strtoupper(substr($admin['nama'] ?? 'A', 0, 1)) ?>
            </div>
            <div>
                <strong style="font-size: 1.15rem; color: var(--text-heading);"><?= htmlspecialchars($admin['nama'] ?? '') ?></strong>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Administrator Sistem #<?= (int)$admin['id_admin'] ?></div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-section">
            <div class="detail-section-title"><i class="bi bi-person-badge"></i> Informasi Akun</div>
            
            <div class="detail-item">
                <div class="detail-label">Alamat Email Resmi</div>
                <div class="detail-value"><i class="bi bi-envelope"></i> <?= htmlspecialchars($admin['email'] ?? '-') ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Peran Sistem</div>
                <div class="detail-value">
                    <span class="badge" style="background:#dbeafe; color:#1e40af;"><i class="bi bi-shield-check"></i> Super Admin</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
