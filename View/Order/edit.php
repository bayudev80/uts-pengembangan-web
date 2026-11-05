<?php
$isEdit = !empty($order);
$customers = $customers ?? [];
$dataJas = $dataJas ?? [];
$pageTitle = $pageTitle ?? ($isEdit ? 'Edit Order' : 'Tambah Order Baru');
$activeNav = 'order';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="page-subtitle"><?= $isEdit ? 'Perbarui data transaksi persewaan jas' : 'Catat pesanan sewa baru untuk pelanggan' ?></p>
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
        <strong><i class="bi bi-receipt"></i> Formulir <?= $isEdit ? 'Ubah Data Order' : 'Order Baru' ?></strong>
    </div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($formAction ?? ($isEdit ? 'index.php?action=order_update' : 'index.php?action=order_store')) ?>" method="POST">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_order" value="<?= (int)$order['id_order'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">
                    Pilih Customer <span class="required">*</span>
                </label>
                <select name="id_customer" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= (int)$c['id_customer'] ?>" <?= ($isEdit && ($order['id_customer'] == $c['id_customer'])) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nama_customer']) ?> (ID: #<?= (int)$c['id_customer'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Model Jas</label>
                <select name="id_jas" class="form-control">
                    <option value="">-- Pilih Jas (Opsional / Ditentukan Nanti) --</option>
                    <?php foreach ($dataJas as $j): ?>
                        <option value="<?= (int)$j['id_jas'] ?>" <?= ($isEdit && ($order['id_jas'] == $j['id_jas'])) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j['Nama_jas']) ?> &bull; <?= htmlspecialchars($j['Ukuran_jas'] ?? '') ?> &bull; <?= htmlspecialchars($j['Warna_jas'] ?? '') ?> (Tarif: Rp <?= number_format((float)$j['Harga_sewa'], 0, ',', '.') ?>/hari)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Pilihan Ukuran Jas</label>
                <select name="ukuran_sewa" class="form-control">
                    <?php 
                    $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'Custom'];
                    $currUkuran = $order['ukuran_sewa'] ?? 'M';
                    foreach ($sizes as $s): 
                    ?>
                        <option value="<?= $s ?>" <?= ($currUkuran === $s) ? 'selected' : '' ?>>
                            Ukuran <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">
                        Tanggal Mulai Sewa <span class="required">*</span>
                    </label>
                    <input type="date" name="tanggal_sewa" class="form-control" 
                           value="<?= htmlspecialchars($order['tanggal_sewa'] ?? date('Y-m-d')) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tanggal Pengembalian <span class="required">*</span>
                    </label>
                    <input type="date" name="tanggal_kembali" class="form-control" 
                           value="<?= htmlspecialchars($order['tanggal_kembali'] ?? date('Y-m-d', strtotime('+2 days'))) ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">
                        Status Pesanan <span class="required">*</span>
                    </label>
                    <select name="status" class="form-control">
                        <?php 
                        $statusOptions = ['diproses', 'disewa', 'selesai', 'dikembalikan'];
                        $currentStatus = strtolower($order['status'] ?? 'diproses');
                        foreach ($statusOptions as $st): 
                        ?>
                            <option value="<?= $st ?>" <?= ($currentStatus === $st) ? 'selected' : '' ?>>
                                <?= ucfirst($st) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Total Biaya Sewa (Rp) <span class="required">*</span>
                    </label>
                    <input type="text" name="total_harga" class="form-control" 
                           value="<?= htmlspecialchars((string)($order['total_harga'] ?? '0')) ?>" 
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/\D/g, '')" 
                           pattern="[0-9]+" title="Hanya angka yang diperbolehkan"
                           placeholder="Nominal total biaya sewa" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Skema Pembayaran</label>
                    <select name="tipe_pembayaran" class="form-control">
                        <?php $currTipe = strtolower($order['tipe_pembayaran'] ?? 'lunas'); ?>
                        <option value="lunas" <?= ($currTipe === 'lunas') ? 'selected' : '' ?>>Lunas (100%)</option>
                        <option value="dp" <?= ($currTipe === 'dp') ? 'selected' : '' ?>>DP (50%)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control">
                        <?php $currMetode = strtolower($order['metode_pembayaran'] ?? 'toko'); ?>
                        <option value="toko" <?= ($currMetode === 'toko') ? 'selected' : '' ?>>Bayar di Toko</option>
                        <option value="qris" <?= ($currMetode === 'qris') ? 'selected' : '' ?>>QRIS</option>
                        <option value="transfer" <?= ($currMetode === 'transfer') ? 'selected' : '' ?>>Transfer Bank</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-control">
                        <?php $currSp = strtolower($order['status_pembayaran'] ?? 'menunggu_konfirmasi'); ?>
                        <option value="menunggu_konfirmasi" <?= ($currSp === 'menunggu_konfirmasi') ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                        <option value="dp_dibayar" <?= ($currSp === 'dp_dibayar') ? 'selected' : '' ?>>DP Terbayar</option>
                        <option value="lunas" <?= ($currSp === 'lunas') ? 'selected' : '' ?>>Lunas</option>
                        <option value="belum_bayar" <?= ($currSp === 'belum_bayar') ? 'selected' : '' ?>>Belum Bayar</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan Order' : 'Buat Order Baru' ?>
                </button>
                <a href="index.php?action=order" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
