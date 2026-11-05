<?php
$isEdit = !empty($admin);
$pageTitle = $pageTitle ?? ($isEdit ? 'Edit Admin' : 'Tambah Admin Baru');
$activeNav = 'admin';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="page-subtitle"><?= $isEdit ? 'Perbarui informasi profil administrator' : 'Daftarkan akun administrator baru ke dalam sistem' ?></p>
    </div>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><?= htmlspecialchars($flash['message']) ?></span>
    </div>
<?php endif; ?>

<div class="card form-card">
    <div class="card-header">
        <strong><i class="bi bi-shield-lock"></i> Formulir <?= $isEdit ? 'Ubah Data Admin' : 'Admin Baru' ?></strong>
    </div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($formAction ?? ($isEdit ? 'index.php?action=admin_update' : 'index.php?action=admin_store')) ?>" method="POST">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_admin" value="<?= (int)$admin['id_admin'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">
                    Nama Administrator <span class="required">*</span>
                </label>
                <input type="text" name="nama" class="form-control" 
                       value="<?= htmlspecialchars($admin['nama'] ?? '') ?>" 
                       placeholder="Nama administrator" 
                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                       pattern="^[^0-9]+$" title="Nama tidak boleh mengandung angka" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Alamat Email <span class="required">*</span>
                </label>
                <input type="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($admin['email'] ?? '') ?>" 
                       placeholder="Alamat email administrator" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambahkan Admin' ?>
                </button>
                <a href="index.php?action=admin" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
