<?php
$isEdit    = !empty($item);
$pageTitle = $pageTitle ?? ($isEdit ? 'Edit Detail Transaksi' : 'Tambah Detail Transaksi');
$activeNav = 'detail_transaksi';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="page-subtitle"><?= $isEdit ? 'Perbarui rincian item transaksi sewa jas' : 'Tambahkan rincian item sewa pada transaksi order' ?></p>
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
        <strong><i class="bi bi-receipt-cutoff"></i> Formulir <?= $isEdit ? 'Edit' : 'Tambah' ?> Detail Transaksi</strong>
    </div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($formAction ?? ($isEdit ? 'index.php?action=detail_transaksi_update' : 'index.php?action=detail_transaksi_store')) ?>"
              method="POST" id="formDetail">

            <?php if ($isEdit): ?>
                <input type="hidden" name="id_detail" value="<?= (int)$item['id_detail'] ?>">
            <?php endif; ?>

            <!-- Pilih Order -->
            <div class="form-group">
                <label class="form-label">Order <span class="required">*</span></label>
                <select name="id_order" class="form-control" id="selectOrder" required>
                    <option value="">-- Pilih Nomor Order --</option>
                    <?php foreach ($orders as $ord): ?>
                        <option value="<?= (int)$ord['id_order'] ?>"
                            <?= ((int)($item['id_order'] ?? $preOrder ?? 0) === (int)$ord['id_order']) ? 'selected' : '' ?>>
                            Order #<?= (int)$ord['id_order'] ?> — <?= htmlspecialchars($ord['nama_customer']) ?>
                            (<?= date('d M Y', strtotime($ord['tanggal_sewa'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pilih Jas (Referensi) -->
            <div class="form-group">
                <label class="form-label">Jas dari Katalog <small style="color:var(--text-muted);">(opsional, untuk auto-isi)</small></label>
                <select name="id_jas" class="form-control" id="selectJas">
                    <option value="">-- Pilih Jas (Opsional) --</option>
                    <?php foreach ($dataJas as $j): ?>
                        <option value="<?= (int)$j['id_jas'] ?>"
                                data-nama="<?= htmlspecialchars($j['Nama_jas']) ?>"
                                data-ukuran="<?= htmlspecialchars($j['Ukuran_jas']) ?>"
                                data-warna="<?= htmlspecialchars($j['Warna_jas']) ?>"
                                data-harga="<?= (float)$j['Harga_sewa'] ?>"
                            <?= ((int)($item['id_jas'] ?? 0) === (int)$j['id_jas']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j['Nama_jas']) ?>
                            — <?= htmlspecialchars($j['Ukuran_jas']) ?>
                            (<?= htmlspecialchars($j['Warna_jas']) ?>)
                            | Rp <?= number_format((float)$j['Harga_sewa'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                <!-- Nama Jas -->
                <div class="form-group">
                    <label class="form-label">Nama Jas <span class="required">*</span></label>
                    <input type="text" name="nama_jas" id="inputNamaJas" class="form-control"
                           value="<?= htmlspecialchars($item['nama_jas'] ?? '') ?>"
                           placeholder="Nama jas yang disewa" required>
                </div>

                <!-- Ukuran -->
                <div class="form-group">
                    <label class="form-label">Ukuran <span class="required">*</span></label>
                    <input type="text" name="ukuran" id="inputUkuran" class="form-control"
                           value="<?= htmlspecialchars($item['ukuran'] ?? '') ?>"
                           placeholder="S / M / L / XL / XXL" required>
                </div>

                <!-- Warna -->
                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <input type="text" name="warna" id="inputWarna" class="form-control"
                           value="<?= htmlspecialchars($item['warna'] ?? '') ?>"
                           placeholder="Hitam / Navy / Abu-abu">
                </div>

                <!-- Harga Sewa / Hari -->
                <div class="form-group">
                    <label class="form-label">Harga Sewa / Hari (Rp) <span class="required">*</span></label>
                    <input type="number" name="harga_sewa" id="inputHarga" class="form-control"
                           value="<?= (float)($item['harga_sewa'] ?? 0) ?>"
                           min="0" step="1000" placeholder="0"
                           oninput="hitungSubtotal()" required>
                </div>

                <!-- Lama Sewa -->
                <div class="form-group">
                    <label class="form-label">Lama Sewa (Hari) <span class="required">*</span></label>
                    <input type="number" name="lama_sewa" id="inputLama" class="form-control"
                           value="<?= (int)($item['lama_sewa'] ?? 1) ?>"
                           min="1" step="1" placeholder="1"
                           oninput="hitungSubtotal()" required>
                </div>

                <!-- Subtotal (readonly) -->
                <div class="form-group">
                    <label class="form-label">Subtotal (Rp)</label>
                    <input type="number" name="subtotal" id="inputSubtotal" class="form-control"
                           value="<?= (float)($item['subtotal'] ?? 0) ?>"
                           min="0" step="1000" placeholder="Otomatis dihitung"
                           style="background:#f8fafc; font-weight:700; color:var(--primary);" readonly>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="2"
                          placeholder="Misal: ukuran disesuaikan, kondisi khusus, dll."><?= htmlspecialchars($item['keterangan'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Detail' ?>
                </button>
                <a href="index.php?action=detail_transaksi" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<script>
// Auto-isi form saat jas dipilih dari dropdown
document.getElementById('selectJas').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;
    document.getElementById('inputNamaJas').value = opt.dataset.nama || '';
    document.getElementById('inputUkuran').value  = opt.dataset.ukuran || '';
    document.getElementById('inputWarna').value   = opt.dataset.warna  || '';
    document.getElementById('inputHarga').value   = opt.dataset.harga  || '';
    hitungSubtotal();
});

function hitungSubtotal() {
    const harga = parseFloat(document.getElementById('inputHarga').value) || 0;
    const lama  = parseInt(document.getElementById('inputLama').value)   || 1;
    document.getElementById('inputSubtotal').value = harga * lama;
}

// Hitung subtotal saat halaman dimuat
hitungSubtotal();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

