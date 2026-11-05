<?php
$isEdit = !empty($jas);
$pageTitle = $pageTitle ?? ($isEdit ? 'Edit Jas' : 'Tambah Jas Baru');
$activeNav = 'jas';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="page-subtitle"><?= $isEdit ? 'Perbarui informasi jas dan ketersediaan stok' : 'Tambahkan item jas baru ke katalog persewaan' ?></p>
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
        <strong><i class="bi bi-box-seam"></i> Formulir <?= $isEdit ? 'Ubah Data Jas' : 'Jas Baru' ?></strong>
    </div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($formAction ?? ($isEdit ? 'index.php?action=jas_update' : 'index.php?action=jas_store')) ?>" method="POST">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_jas" value="<?= (int)$jas['id_jas'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">
                    Nama Jas / Model <span class="required">*</span>
                </label>
                <input type="text" name="Nama_jas" class="form-control" 
                       value="<?= htmlspecialchars($jas['Nama_jas'] ?? '') ?>" 
                       placeholder="Nama jas / model" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Kategori Jas <span class="required">*</span>
                </label>
                <select name="kategori" class="form-control" required>
                    <?php 
                    $kategoriOptions = ['Formal', 'Tuxedo', 'Pernikahan', 'Wisuda', 'Casual / Blazer', 'Tradisional / Batik'];
                    $currentKategori = $jas['kategori'] ?? 'Formal';
                    foreach ($kategoriOptions as $kat):
                    ?>
                        <option value="<?= $kat ?>" <?= ($currentKategori === $kat) ? 'selected' : '' ?>>
                            <?= $kat ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">
                        Pilihan Ukuran Jas yang Tersedia <span class="required">*</span>
                    </label>
                    <input type="text" name="Ukuran_jas" class="form-control" 
                           value="<?= htmlspecialchars($jas['Ukuran_jas'] ?? 'S, M, L, XL, XXL') ?>" 
                           placeholder="S, M, L, XL, XXL" required>
                    <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 4px;">
                        Pilihan ukuran yang dapat dipilih saat sewa (pisahkan dengan koma: S, M, L, XL, XXL)
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Warna Jas <span class="required">*</span>
                    </label>
                    <input type="text" name="Warna_jas" class="form-control" 
                           value="<?= htmlspecialchars($jas['Warna_jas'] ?? '') ?>" 
                           oninput="this.value = this.value.replace(/[0-9]/g, '')"
                           pattern="^[^0-9]+$" title="Warna tidak boleh mengandung angka"
                           placeholder="Warna jas" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">
                        Harga Sewa per Hari (Rp) <span class="required">*</span>
                    </label>
                    <input type="text" name="Harga_sewa" class="form-control" 
                           value="<?= htmlspecialchars((string)($jas['Harga_sewa'] ?? '0')) ?>" 
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/\D/g, '')" 
                           pattern="[0-9]+" title="Hanya angka yang diperbolehkan"
                           placeholder="Nominal harga sewa" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Stok Tersedia <span class="required">*</span>
                    </label>
                    <input type="text" name="Stok" class="form-control" 
                           value="<?= htmlspecialchars((string)($jas['Stok'] ?? '1')) ?>" 
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/\D/g, '')" 
                           pattern="[0-9]+" title="Hanya angka yang diperbolehkan"
                           placeholder="Jumlah stok" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Link Foto / Gambar (Opsional)</label>
                <input type="text" name="foto" class="form-control" 
                       value="<?= htmlspecialchars($jas['foto'] ?? '') ?>" 
                       placeholder="URL atau nama file foto">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambahkan Jas' ?>
                </button>
                <a href="index.php?action=jas" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
