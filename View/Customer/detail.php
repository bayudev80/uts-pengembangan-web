<?php
$pageTitle = 'Detail Customer - ' . htmlspecialchars($customer['nama_customer'] ?? '');
$activeNav = 'customer';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Rincian Customer</h1>
        <p class="page-subtitle">Informasi lengkap data pelanggan #<?= (int)$customer['id_customer'] ?></p>
    </div>
    <div class="btn-group">
        <a href="index.php?action=edit&id=<?= (int)$customer['id_customer'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit Data
        </a>
        <a href="index.php?action=delete&id=<?= (int)$customer['id_customer'] ?>" 
           onclick="return confirm('Apakah Anda yakin ingin menghapus data customer ini?');" 
           class="btn btn-danger">
            <i class="bi bi-trash"></i> Hapus
        </a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="avatar" style="width: 48px; height: 48px; font-size: 1.2rem;">
                <?= strtoupper(substr($customer['nama_customer'] ?? 'C', 0, 1)) ?>
            </div>
            <div>
                <strong style="font-size: 1.15rem; color: var(--text-heading);"><?= htmlspecialchars($customer['nama_customer']) ?></strong>
                <div style="font-size: 0.8rem; color: var(--text-muted);">ID Pelanggan: #<?= (int)$customer['id_customer'] ?></div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-section">
            <div class="detail-section-title"><i class="bi bi-info-circle"></i> Informasi Kontak & Identitas</div>
            
            <div class="detail-item">
                <div class="detail-label">Nomor WhatsApp / HP</div>
                <div class="detail-value" style="display: flex; align-items: center; gap: 10px;">
                    <span><i class="bi bi-telephone"></i> <?= htmlspecialchars($customer['no_hp']) ?></span>
                    <?php 
                    $cleanCustPhone = preg_replace('/\D/', '', $customer['no_hp'] ?? '');
                    if (str_starts_with($cleanCustPhone, '0')) {
                        $waCustNumber = '62' . substr($cleanCustPhone, 1);
                    } elseif (str_starts_with($cleanCustPhone, '8')) {
                        $waCustNumber = '62' . $cleanCustPhone;
                    } else {
                        $waCustNumber = $cleanCustPhone;
                    }
                    ?>
                    <?php if (!empty($waCustNumber)): ?>
                        <a href="https://wa.me/<?= $waCustNumber ?>?text=<?= urlencode('Halo kak ' . $customer['nama_customer'] . ', kami dari Rental Jas.') ?>" target="_blank" class="badge" style="background: #25d366; color: white; text-decoration: none; font-size: 0.72rem; padding: 4px 8px; border-radius: 4px;" title="Chat WhatsApp Pelanggan">
                            <i class="bi bi-whatsapp"></i> Chat WA
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Kartu Identitas Jaminan (KTP / SIM / Kartu Pelajar)</div>
                <div class="detail-value">
                    <?php 
                    $idFile = $customer['KTP'] ?? '';
                    $isImg = !empty($idFile) && (strpos($idFile, 'uploads/') !== false || preg_match('/\.(jpg|jpeg|png|webp)$/i', $idFile));
                    ?>
                    <?php if ($isImg): ?>
                        <div style="margin-top: 6px;">
                            <a href="<?= htmlspecialchars($idFile) ?>" target="_blank" title="Klik untuk membuka ukuran penuh">
                                <img src="<?= htmlspecialchars($idFile) ?>" style="max-height: 160px; max-width: 100%; border-radius: 8px; border: 1.5px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.06);" alt="Foto Identitas Jaminan">
                            </a>
                            <div style="margin-top: 6px;">
                                <a href="<?= htmlspecialchars($idFile) ?>" target="_blank" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem;">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka Foto Ukuran Penuh
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <i class="bi bi-card-heading"></i> <code><?= htmlspecialchars($idFile ?: '-') ?></code>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
