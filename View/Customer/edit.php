<?php
$isEdit = !empty($customer);
$pageTitle = $pageTitle ?? ($isEdit ? 'Edit Customer' : 'Tambah Customer');
$activeNav = 'customer';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="page-subtitle"><?= $isEdit ? 'Perbarui informasi data customer ini' : 'Masukkan informasi pelanggan baru ke dalam sistem' ?></p>
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
        <strong><i class="bi bi-person-lines-fill"></i> Formulir <?= $isEdit ? 'Ubah Data Customer' : 'Customer Baru' ?></strong>
    </div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($formAction ?? ($isEdit ? 'index.php?action=update' : 'index.php?action=store')) ?>" method="POST" enctype="multipart/form-data">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_customer" value="<?= (int)$customer['id_customer'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">
                    Nama Lengkap Customer <span class="required">*</span>
                </label>
                <input type="text" name="nama_customer" class="form-control" 
                       value="<?= htmlspecialchars($customer['nama_customer'] ?? '') ?>" 
                       placeholder="Nama lengkap customer" 
                       oninput="this.value = this.value.replace(/[0-9]/g, '')"
                       pattern="^[^0-9]+$" title="Nama tidak boleh mengandung angka" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Nomor WhatsApp / HP <span class="required">*</span>
                </label>
                <input type="tel" name="no_hp" class="form-control" 
                       value="<?= htmlspecialchars($customer['no_hp'] ?? '') ?>" 
                       placeholder="Nomor WhatsApp / HP" 
                       inputmode="numeric"
                       oninput="this.value = this.value.replace(/\D/g, '')"
                       pattern="[0-9]+" title="Hanya angka yang diperbolehkan" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Foto Kartu Identitas Jaminan (KTP / SIM / Kartu Pelajar)
                </label>
                <?php 
                $currentKtp = $customer['KTP'] ?? '';
                $isImg = !empty($currentKtp) && (strpos($currentKtp, 'uploads/') !== false || preg_match('/\.(jpg|jpeg|png|webp)$/i', $currentKtp));
                ?>
                <?php if ($isImg && file_exists(__DIR__ . '/../../' . $currentKtp)): ?>
                    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 1px solid var(--border-color); padding: 8px 12px; border-radius: 8px;">
                        <img src="<?= htmlspecialchars($currentKtp) ?>" style="height: 60px; max-width: 90px; object-fit: contain; border-radius: 4px; border: 1px solid #cbd5e1;" alt="Foto Identitas">
                        <div>
                            <span class="badge badge-selesai" style="font-size: 0.7rem;"><i class="bi bi-check-circle"></i> Foto Identitas Tersimpan</span>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
                                <a href="<?= htmlspecialchars($currentKtp) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                                    <i class="bi bi-box-arrow-up-right"></i> Lihat Ukuran Penuh
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <input type="file" name="foto_identitas" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                <input type="hidden" name="KTP" value="<?= htmlspecialchars($currentKtp) ?>">
                <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 4px;">
                    Upload foto baru untuk mengganti kartu identitas jaminan (JPG, PNG, WEBP &bull; Maks 5 MB).
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Customer Baru' ?>
                </button>
                <a href="index.php?action=customer" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
