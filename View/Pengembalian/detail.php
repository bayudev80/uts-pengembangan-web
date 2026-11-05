<?php
$pengembalian = $pengembalian ?? [];
$pageTitle = 'Detail Pengembalian #' . (int)($pengembalian['id_pengembalian'] ?? 0);
$activeNav = 'pengembalian';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Rincian Pengembalian #<?= (int)($pengembalian['id_pengembalian'] ?? 0) ?></h1>
        <p class="page-subtitle">Informasi pengembalian barang, kondisi jas, dan denda</p>
    </div>
    <div class="btn-group">
        <a href="index.php?action=pengembalian_edit&id=<?= (int)$pengembalian['id_pengembalian'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="index.php?action=pengembalian_delete&id=<?= (int)$pengembalian['id_pengembalian'] ?>" 
           onclick="return confirm('Apakah Anda yakin ingin menghapus data pengembalian ini?');" 
           class="btn btn-danger">
            <i class="bi bi-trash"></i> Hapus
        </a>
    </div>
</div>

<div class="detail-grid">
    <!-- Info Pengembalian -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-arrow-return-left"></i> Data Pengembalian</strong>
            <?php
            $kondisi = strtolower($pengembalian['kondisi_jas'] ?? 'baik');
            $kondisiBadge = match($kondisi) {
                'baik' => 'badge-selesai',
                'kotor' => 'badge-diproses',
                'rusak' => 'badge-dikembalikan',
                'hilang' => 'badge-stok-empty',
                default => 'badge-diproses',
            };
            ?>
            <span class="badge <?= $kondisiBadge ?>">Kondisi: <?= ucfirst(htmlspecialchars($kondisi)) ?></span>
        </div>
        <div class="card-body">
            <div class="detail-item">
                <div class="detail-label">Tanggal Dikembalikan</div>
                <div class="detail-value">
                    <i class="bi bi-calendar-check"></i> <?= htmlspecialchars($pengembalian['tanggal_dikembalikan'] ?? '-') ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Biaya Denda / Penalti</div>
                <div class="detail-value" style="color: <?= (float)($pengembalian['denda'] ?? 0) > 0 ? 'var(--danger)' : 'var(--success)' ?>; font-size: 1.15rem;">
                    Rp <?= number_format((float)($pengembalian['denda'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Catatan Tambahan</div>
                <div class="detail-value">
                    <?= !empty($pengembalian['catatan']) ? nl2br(htmlspecialchars($pengembalian['catatan'])) : '<em>(Tidak ada catatan)</em>' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Order & Customer -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-receipt"></i> Transaksi Order Terkait</strong>
            <a href="index.php?action=order_detail&id=<?= (int)$pengembalian['id_order'] ?>" class="btn btn-outline btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> Buka Order #<?= (int)$pengembalian['id_order'] ?>
            </a>
        </div>
        <div class="card-body">
            <div class="detail-item">
                <div class="detail-label">Nama Pelanggan</div>
                <div class="detail-value"><?= htmlspecialchars($pengembalian['nama_customer'] ?? '(Customer)') ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Model Jas Disewa</div>
                <div class="detail-value">
                    <i class="bi bi-tag-fill" style="color: var(--primary);"></i> <?= htmlspecialchars($pengembalian['Nama_jas'] ?? '-') ?> 
                    (<?= htmlspecialchars($pengembalian['Ukuran_jas'] ?? '-') ?>, <?= htmlspecialchars($pengembalian['Warna_jas'] ?? '-') ?>)
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Masa Sewa Kontrak</div>
                <div class="detail-value">
                    <i class="bi bi-calendar3"></i> <?= htmlspecialchars($pengembalian['tanggal_sewa'] ?? '-') ?> s/d <?= htmlspecialchars($pengembalian['batas_kembali'] ?? '-') ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Biaya Sewa Awal</div>
                <div class="detail-value">Rp <?= number_format((float)($pengembalian['biaya_sewa'] ?? 0), 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
