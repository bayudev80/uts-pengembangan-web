<?php
$pageTitle = 'Detail Transaksi #' . (int)($detail['id_detail'] ?? 0);
$activeNav = 'detail_transaksi';
require_once __DIR__ . '/../layout/header.php';

$st = strtolower($detail['status_order'] ?? 'diproses');
$badgeCls = match($st) {
    'disewa'       => 'badge-disewa',
    'selesai'      => 'badge-selesai',
    'dikembalikan' => 'badge-dikembalikan',
    default        => 'badge-diproses',
};
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Transaksi #<?= (int)$detail['id_detail'] ?></h1>
        <p class="page-subtitle">Rincian item sewa untuk Order
            <a href="index.php?action=order_detail&id=<?= (int)$detail['id_order'] ?>"
               style="color:var(--primary);">#<?= (int)$detail['id_order'] ?></a>
        </p>
    </div>
    <div class="btn-group">
        <a href="index.php?action=detail_transaksi_edit&id=<?= (int)$detail['id_detail'] ?>" class="btn btn-secondary">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="index.php?action=detail_transaksi_delete&id=<?= (int)$detail['id_detail'] ?>"
           onclick="return confirm('Hapus detail transaksi ini?');"
           class="btn btn-danger">
            <i class="bi bi-trash"></i> Hapus
        </a>
    </div>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= ($flash['type'] ?? '') === 'error' ? 'error' : 'success' ?>">
        <i class="bi bi-<?= ($flash['type'] ?? '') === 'error' ? 'exclamation-triangle-fill' : 'check-circle-fill' ?>"></i>
        <span><?= htmlspecialchars($flash['message']) ?></span>
    </div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

    <!-- Informasi Item Jas -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-box-seam" style="color:var(--primary);"></i> Informasi Item Jas</strong>
        </div>
        <div class="card-body">
            <?php if (!empty($detail['foto_jas']) && file_exists('uploads/jas/' . $detail['foto_jas'])): ?>
                <div style="text-align:center; margin-bottom:1rem;">
                    <img src="uploads/jas/<?= htmlspecialchars($detail['foto_jas']) ?>"
                         alt="Foto Jas"
                         style="max-width:120px; border-radius:10px; border:1px solid var(--border-color);">
                </div>
            <?php endif; ?>

            <div class="detail-section">
                <div class="detail-item">
                    <div class="detail-label">Nama Jas (Snapshot)</div>
                    <div class="detail-value"><strong><?= htmlspecialchars($detail['nama_jas']) ?></strong></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Ukuran Disewa</div>
                    <div class="detail-value"><span class="jas-badge-size"><?= htmlspecialchars($detail['ukuran']) ?></span></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Warna</div>
                    <div class="detail-value"><?= htmlspecialchars($detail['warna'] ?? '-') ?></div>
                </div>
                <?php if (!empty($detail['kategori_jas'])): ?>
                <div class="detail-item">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value"><?= htmlspecialchars($detail['kategori_jas']) ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($detail['keterangan'])): ?>
                <div class="detail-item">
                    <div class="detail-label">Keterangan</div>
                    <div class="detail-value"><?= nl2br(htmlspecialchars($detail['keterangan'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Rincian Harga & Pembayaran -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-calculator" style="color:var(--primary);"></i> Rincian Harga & Pembayaran</strong>
        </div>
        <div class="card-body">
            <div class="detail-section">
                <div class="detail-item">
                    <div class="detail-label">Harga Sewa / Hari</div>
                    <div class="detail-value" style="font-weight:700; color:var(--primary);">
                        Rp <?= number_format((float)$detail['harga_sewa'], 0, ',', '.') ?>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Lama Sewa</div>
                    <div class="detail-value"><strong><?= (int)$detail['lama_sewa'] ?> Hari</strong></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tanggal Sewa</div>
                    <div class="detail-value">
                        <?= date('d M Y', strtotime($detail['tanggal_sewa'] ?? 'now')) ?>
                        &rarr; <?= date('d M Y', strtotime($detail['tanggal_kembali'] ?? 'now')) ?>
                    </div>
                </div>
                <div class="detail-item" style="border-top:2px solid var(--primary); padding-top:0.75rem; margin-top:0.5rem;">
                    <div class="detail-label" style="font-size:1rem; font-weight:700;">Subtotal Item</div>
                    <div class="detail-value" style="font-size:1.3rem; font-weight:800; color:var(--primary);">
                        Rp <?= number_format((float)$detail['subtotal'], 0, ',', '.') ?>
                    </div>
                </div>
                <div class="detail-item" style="margin-top:0.5rem;">
                    <div class="detail-label">Total Order Keseluruhan</div>
                    <div class="detail-value" style="font-weight:600;">
                        Rp <?= number_format((float)($detail['total_harga'] ?? 0), 0, ',', '.') ?>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value">
                        <?php
                        $mp = $detail['metode_pembayaran'] ?? '-';
                        $mpLabel = match($mp) {
                            'transfer' => '<i class="bi bi-bank"></i> Transfer Bank',
                            'qris'     => '<i class="bi bi-qr-code-scan"></i> QRIS',
                            'toko'     => '<i class="bi bi-shop"></i> Langsung ke Toko',
                            default    => htmlspecialchars($mp),
                        };
                        echo $mpLabel;
                        ?>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status Order</div>
                    <div class="detail-value"><span class="badge <?= $badgeCls ?>"><?= ucfirst($st) ?></span></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status Pembayaran</div>
                    <div class="detail-value">
                        <?php
                        $sp = $detail['status_pembayaran'] ?? '-';
                        $spLabel = match($sp) {
                            'lunas'                => '<span class="badge badge-selesai">Lunas</span>',
                            'dp_dibayar'           => '<span class="badge badge-disewa">DP Dibayar</span>',
                            'menunggu_konfirmasi'  => '<span class="badge badge-diproses">Menunggu Konfirmasi</span>',
                            'belum_bayar'          => '<span class="badge" style="background:#fef2f2;color:#dc2626;">Belum Bayar</span>',
                            default                => htmlspecialchars($sp),
                        };
                        echo $spLabel;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pelanggan & Referensi Order -->
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <strong><i class="bi bi-person-badge" style="color:var(--primary);"></i> Data Pelanggan & Referensi Order</strong>
        </div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div class="detail-section">
                    <div class="detail-section-title"><i class="bi bi-person"></i> Pelanggan</div>
                    <div class="detail-item">
                        <div class="detail-label">Nama Customer</div>
                        <div class="detail-value"><strong><?= htmlspecialchars($detail['nama_customer'] ?? '-') ?></strong></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nomor HP</div>
                        <div class="detail-value"><?= htmlspecialchars($detail['no_hp_customer'] ?? '-') ?></div>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-section-title"><i class="bi bi-link-45deg"></i> Referensi</div>
                    <div class="detail-item">
                        <div class="detail-label">ID Order</div>
                        <div class="detail-value">
                            <a href="index.php?action=order_detail&id=<?= (int)$detail['id_order'] ?>"
                               style="color:var(--primary); font-weight:700; text-decoration:none;">
                                <i class="bi bi-receipt"></i> Buka Order #<?= (int)$detail['id_order'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">ID Detail</div>
                        <div class="detail-value"><code>#<?= (int)$detail['id_detail'] ?></code></div>
                    </div>
                    <?php if (!empty($detail['id_jas'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">ID Jas Referensi</div>
                        <div class="detail-value"><code>tb_jas #<?= (int)$detail['id_jas'] ?></code></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

