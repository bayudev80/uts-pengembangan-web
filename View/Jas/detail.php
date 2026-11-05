<?php
$pageTitle = 'Detail Jas - ' . htmlspecialchars($jas['Nama_jas'] ?? '');
$activeNav = 'jas';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Koleksi Jas</h1>
        <p class="page-subtitle">Informasi lengkap spesifikasi dan ketersediaan jas #<?= (int)$jas['id_jas'] ?></p>
    </div>
    <div class="btn-group">
        <a href="index.php?action=jas_edit&id=<?= (int)$jas['id_jas'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit Jas
        </a>
        <a href="index.php?action=jas_delete&id=<?= (int)$jas['id_jas'] ?>" 
           onclick="return confirm('Apakah Anda yakin ingin menghapus jas ini?');" 
           class="btn btn-danger">
            <i class="bi bi-trash"></i> Hapus
        </a>
        <a href="index.php?action=jas" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <strong style="font-size: 1.2rem; color: var(--text-heading);">
                <i class="bi bi-tag-fill" style="color: var(--primary);"></i> <?= htmlspecialchars($jas['Nama_jas']) ?>
            </strong>
            <div style="font-size: 0.8rem; color: var(--text-muted);">ID Jas: #<?= (int)$jas['id_jas'] ?></div>
        </div>
        <div>
            <?php $stok = (int)($jas['Stok'] ?? 0); ?>
            <?php if ($stok > 0): ?>
                <span class="badge badge-stok-ready"><i class="bi bi-check-circle"></i> Stok: <?= $stok ?> Ready</span>
            <?php else: ?>
                <span class="badge badge-stok-empty"><i class="bi bi-x-circle"></i> Stok Habis</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-section">
            <div class="detail-section-title"><i class="bi bi-sliders"></i> Spesifikasi & Tarif</div>
            
            <div class="detail-item">
                <div class="detail-label">Kategori Jas</div>
                <div class="detail-value">
                    <span class="badge" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1;">
                        <i class="fa fa-tag me-1"></i><?= htmlspecialchars($jas['kategori'] ?? 'Formal') ?>
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Pilihan Ukuran Tersedia</div>
                <div class="detail-value">
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        <?php 
                        $dtlSizes = array_filter(array_map('trim', explode(',', (string)($jas['Ukuran_jas'] ?? ''))));
                        if (empty($dtlSizes)) $dtlSizes = [(string)($jas['Ukuran_jas'] ?? '-')];
                        foreach ($dtlSizes as $dsz):
                        ?>
                            <span class="jas-badge-size" style="font-size: 0.85rem; padding: 3px 8px;"><?= htmlspecialchars($dsz) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Warna</div>
                <div class="detail-value"><?= htmlspecialchars($jas['Warna_jas'] ?? '-') ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Biaya Sewa</div>
                <div class="detail-value" style="color: var(--primary); font-size: 1.15rem;">
                    Rp <?= number_format((float)($jas['Harga_sewa'] ?? 0), 0, ',', '.') ?> <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: normal;">/ hari</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Total Stok Tersedia</div>
                <div class="detail-value"><?= $stok ?> Unit</div>
            </div>

            <?php if (!empty($jas['foto'])): ?>
                <div class="detail-item">
                    <div class="detail-label">Foto / Gambar</div>
                    <div class="detail-value">
                        <img src="<?= htmlspecialchars(getFotoJas($jas['foto'])) ?>" alt="<?= htmlspecialchars($jas['Nama_jas']) ?>" style="max-width: 100%; max-height: 280px; object-fit: cover; border-radius: 8px; margin-top: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
