<?php
$isEdit = !empty($pengembalian);
$orders = $orders ?? [];
$preselectedOrderId = $preselectedOrderId ?? 0;
$pageTitle = $pageTitle ?? ($isEdit ? 'Edit Pengembalian' : 'Catat Pengembalian Jas');
$activeNav = 'pengembalian';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="page-subtitle"><?= $isEdit ? 'Perbarui data catatan pengembalian jas' : 'Konfirmasi penerimaan kembali jas yang disewa oleh pelanggan' ?></p>
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
        <strong><i class="bi bi-arrow-return-left"></i> Formulir <?= $isEdit ? 'Ubah Pengembalian' : 'Penerimaan Pengembalian' ?></strong>
    </div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($formAction ?? ($isEdit ? 'index.php?action=pengembalian_update' : 'index.php?action=pengembalian_store')) ?>" method="POST">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_pengembalian" value="<?= (int)$pengembalian['id_pengembalian'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">
                    Pilih Transaksi Order <span class="required">*</span>
                </label>
                <select name="id_order" class="form-control" required <?= $isEdit ? 'disabled' : '' ?>>
                    <option value="">-- Pilih Transaksi Order --</option>
                    <?php foreach ($orders as $ord): ?>
                        <?php 
                        $isCurrent = ($isEdit && ($pengembalian['id_order'] == $ord['id_order']));
                        $selected = $isCurrent || (!$isEdit && ($preselectedOrderId == $ord['id_order'])); 
                        $alreadyReturned = !empty($ord['id_pengembalian_exist']) && !$isCurrent;
                        ?>
                        <option value="<?= (int)$ord['id_order'] ?>" 
                                data-batas="<?= htmlspecialchars($ord['tanggal_kembali'] ?? '') ?>"
                                <?= $selected ? 'selected' : '' ?>
                                <?= $alreadyReturned ? 'disabled style="color:#94a3b8;"' : '' ?>>
                            Order #<?= (int)$ord['id_order'] ?> &bull; <?= htmlspecialchars($ord['nama_customer'] ?? 'Cust') ?> &bull; <?= htmlspecialchars($ord['Nama_jas'] ?? 'Jas') ?> (Batas: <?= htmlspecialchars($ord['tanggal_kembali']) ?>) <?= $alreadyReturned ? '[Sudah Dikembalikan]' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id_order" value="<?= (int)$pengembalian['id_order'] ?>">
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">
                        Tanggal Dikembalikan <span class="required">*</span>
                    </label>
                    <input type="date" name="tanggal_dikembalikan" class="form-control" 
                           value="<?= htmlspecialchars($pengembalian['tanggal_dikembalikan'] ?? date('Y-m-d')) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Kondisi Jas Saat Kembali <span class="required">*</span>
                    </label>
                    <select name="kondisi_jas" class="form-control">
                        <?php 
                        $kondisiOptions = ['baik' => 'Baik / Normal', 'kotor' => 'Kotor (Perlu Laundry Khusus)', 'rusak' => 'Rusak / Sobek', 'hilang' => 'Hilang'];
                        $currentKondisi = strtolower($pengembalian['kondisi_jas'] ?? 'baik');
                        foreach ($kondisiOptions as $val => $label): 
                        ?>
                            <option value="<?= $val ?>" <?= ($currentKondisi === $val) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Biaya Denda / Tambahan (Rp) <span class="required">*</span>
                </label>
                <input type="text" name="denda" class="form-control" 
                       value="<?= htmlspecialchars((string)($pengembalian['denda'] ?? '0')) ?>" 
                       inputmode="numeric"
                       oninput="this.value = this.value.replace(/\D/g, '')" 
                       pattern="[0-9]+" title="Hanya angka yang diperbolehkan"
                       placeholder="0 jika tepat waktu dan kondisi baik" required>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Pemeriksaan</label>
                <textarea name="catatan" class="form-control" rows="3" 
                          placeholder="Catatan kondisi jas atau alasan denda (opsional)"><?= htmlspecialchars($pengembalian['catatan'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Catat Pengembalian' ?>
                </button>
                <a href="index.php?action=pengembalian" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderSelect = document.querySelector('select[name="id_order"]');
    const tglKembaliInput = document.querySelector('input[name="tanggal_dikembalikan"]');
    const kondisiSelect = document.querySelector('select[name="kondisi_jas"]');
    const dendaInput = document.querySelector('input[name="denda"]');

    function hitungDendaOtomatis() {
        if (!orderSelect || !tglKembaliInput || !dendaInput) return;
        const opt = orderSelect.selectedOptions[0];
        if (!opt || !opt.dataset.batas) return;

        const batasStr = opt.dataset.batas;
        const tglKembaliStr = tglKembaliInput.value;
        if (!batasStr || !tglKembaliStr) return;

        const dBatas = new Date(batasStr);
        const dKembali = new Date(tglKembaliStr);
        let dendaTerlambat = 0;

        if (dKembali > dBatas) {
            const diffDays = Math.floor((dKembali - dBatas) / (1000 * 60 * 60 * 24));
            if (diffDays > 0) {
                dendaTerlambat = diffDays * 50000;
            }
        }

        let dendaKondisi = 0;
        const kondisi = kondisiSelect ? kondisiSelect.value : 'baik';
        if (kondisi === 'kotor') dendaKondisi = 35000;
        else if (kondisi === 'rusak') dendaKondisi = 100000;
        else if (kondisi === 'hilang') dendaKondisi = 350000;

        dendaInput.value = dendaTerlambat + dendaKondisi;
    }

    if (orderSelect) orderSelect.addEventListener('change', hitungDendaOtomatis);
    if (tglKembaliInput) tglKembaliInput.addEventListener('change', hitungDendaOtomatis);
    if (kondisiSelect) kondisiSelect.addEventListener('change', hitungDendaOtomatis);
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
