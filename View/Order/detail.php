<?php
$order = $order ?? [];
$pageTitle = 'Detail Order #' . (int)($order['id_order'] ?? 0);
$activeNav = 'order';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Rincian Order #<?= (int)($order['id_order'] ?? 0) ?></h1>
        <p class="page-subtitle">Informasi lengkap transaksi sewa jas dan data pelanggan</p>
    </div>
    <div class="btn-group">
        <?php $status = strtolower($order['status'] ?? 'diproses'); ?>
        <?php if ($status === 'diproses'): ?>
            <a href="index.php?action=order_status&id=<?= (int)$order['id_order'] ?>&status=disewa&redirect=detail" 
               onclick="return confirm('Verifikasi pesanan #<?= (int)$order['id_order'] ?> dan ubah status menjadi Disewa?');" 
               class="btn btn-primary" style="background: #0d9488; border-color: #0d9488;">
                <i class="bi bi-check2-circle"></i> Verifikasi Pesanan
            </a>
        <?php endif; ?>
        <a href="index.php?action=order_edit&id=<?= (int)$order['id_order'] ?>" class="btn btn-secondary">
            <i class="bi bi-pencil-square"></i> Edit Order
        </a>
        <a href="index.php?action=order_delete&id=<?= (int)$order['id_order'] ?>" 
           onclick="return confirm('Apakah Anda yakin ingin menghapus order #<?= (int)$order['id_order'] ?>?');" 
           class="btn btn-danger">
            <i class="bi bi-trash"></i> Hapus
        </a>
        <a href="index.php?action=order" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= ($flash['type'] ?? 'info') === 'error' ? 'error' : 'success' ?>">
        <i class="bi bi-<?= ($flash['type'] ?? 'info') === 'error' ? 'exclamation-triangle-fill' : 'check-circle-fill' ?>"></i>
        <span><?= htmlspecialchars($flash['message']) ?></span>
    </div>
<?php endif; ?>

<div class="detail-grid">
    <!-- Panel Verifikasi & Kelola Status Transaksi -->
    <div class="card" style="grid-column: 1 / -1; border-top: 4px solid var(--primary);">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <strong><i class="bi bi-patch-check-fill" style="color: var(--primary);"></i> Verifikasi & Pengelolaan Status Transaksi</strong>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Admin dapat memverifikasi pesanan masuk dan memperbarui status sewa langsung di sini</div>
            </div>
            <?php 
            $cleanPhone = preg_replace('/\D/', '', $order['no_hp_customer'] ?? '');
            if (str_starts_with($cleanPhone, '0')) {
                $waNumber = '62' . substr($cleanPhone, 1);
            } elseif (str_starts_with($cleanPhone, '8')) {
                $waNumber = '62' . $cleanPhone;
            } else {
                $waNumber = $cleanPhone;
            }
            $pesanWa = "Halo kak " . ($order['nama_customer'] ?? '') . ", kami dari Rental Jas mengonfirmasi pesanan sewa jas #" . $order['id_order'] . " (" . ($order['Nama_jas'] ?? '') . " - Ukuran " . (!empty($order['ukuran_sewa']) ? $order['ukuran_sewa'] : ($order['Ukuran_jas'] ?? '')) . "). Tanggal sewa: " . ($order['tanggal_sewa'] ?? '') . " s/d " . ($order['tanggal_kembali'] ?? '') . ".";
            ?>
            <?php if (!empty($waNumber)): ?>
                <a href="https://wa.me/<?= $waNumber ?>?text=<?= urlencode($pesanWa) ?>" target="_blank" class="btn btn-sm" style="background: #25d366; color: white; display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
                    <i class="bi bi-whatsapp"></i> Chat WhatsApp Pelanggan
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <!-- Timeline Status -->
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; background: #f8fafc; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; background: <?= ($status === 'diproses') ? 'var(--info-bg)' : '#e2e8f0' ?>; color: <?= ($status === 'diproses') ? 'var(--info-text)' : '#64748b' ?>;">1</span>
                    <div>
                        <strong style="font-size: 0.85rem; display: block;">Menunggu Verifikasi</strong>
                        <small style="color: var(--text-muted);">Status: diproses</small>
                    </div>
                </div>
                <div style="color: #cbd5e1; font-weight: bold;">&rarr;</div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; background: <?= ($status === 'disewa') ? 'var(--warning-bg)' : '#e2e8f0' ?>; color: <?= ($status === 'disewa') ? 'var(--warning-text)' : '#64748b' ?>;">2</span>
                    <div>
                        <strong style="font-size: 0.85rem; display: block;">Terverifikasi & Disewa</strong>
                        <small style="color: var(--text-muted);">Status: disewa</small>
                    </div>
                </div>
                <div style="color: #cbd5e1; font-weight: bold;">&rarr;</div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; background: <?= ($status === 'selesai' || $status === 'dikembalikan') ? 'var(--success-bg)' : '#e2e8f0' ?>; color: <?= ($status === 'selesai' || $status === 'dikembalikan') ? 'var(--success-text)' : '#64748b' ?>;">3</span>
                    <div>
                        <strong style="font-size: 0.85rem; display: block;">Selesai / Dikembalikan</strong>
                        <small style="color: var(--text-muted);">Status: selesai / dikembalikan</small>
                    </div>
                </div>
            </div>

            <?php if ($status === 'diproses'): ?>
                <div style="background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #0d9488; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <strong style="color: #0f766e; font-size: 1rem; display: block;">Pesanan Masuk &ndash; Menunggu Verifikasi Admin</strong>
                            <div style="font-size: 0.85rem; color: #115e59;">Periksa ketersediaan jas fisik atau hubungi pelanggan via WhatsApp, lalu setujui pesanan.</div>
                        </div>
                    </div>
                    <a href="index.php?action=order_status&id=<?= (int)$order['id_order'] ?>&status=disewa&redirect=detail" 
                       onclick="return confirm('Verifikasi dan setujui pesanan #<?= (int)$order['id_order'] ?>?');" 
                       class="btn btn-primary" 
                       style="background: #0d9488; border-color: #0d9488; padding: 9px 18px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-check-circle-fill"></i> Verifikasi & Setujui Sewa
                    </a>
                </div>
            <?php endif; ?>

            <!-- Form Pilih Status Transaksi -->
            <form action="index.php?action=order_status&redirect=detail" method="POST" style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
                <input type="hidden" name="id_order" value="<?= (int)$order['id_order'] ?>">
                <div class="form-group" style="margin-bottom: 0; min-width: 280px; flex-grow: 1;">
                    <label class="form-label" style="font-weight: 600;">Ubah Status Transaksi Manual:</label>
                    <select name="status" class="form-control" style="font-weight: 600;">
                        <option value="diproses" <?= ($status === 'diproses') ? 'selected' : '' ?>>
                            Menunggu Verifikasi (diproses)
                        </option>
                        <option value="disewa" <?= ($status === 'disewa') ? 'selected' : '' ?>>
                            Terverifikasi & Sedang Disewa (disewa)
                        </option>
                        <option value="selesai" <?= ($status === 'selesai') ? 'selected' : '' ?>>
                            Transaksi Selesai (selesai)
                        </option>
                        <option value="dikembalikan" <?= ($status === 'dikembalikan') ? 'selected' : '' ?>>
                            Jas Telah Dikembalikan (dikembalikan)
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary" style="font-weight: 600; padding: 9px 18px;">
                    <i class="bi bi-arrow-repeat"></i> Update Status
                </button>
            </form>
        </div>
    </div>
    <!-- Info Pesanan -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-receipt"></i> Status & Biaya Transaksi</strong>
            <?php
            $status = strtolower($order['status'] ?? 'diproses');
            $badgeClass = match($status) {
                'disewa' => 'badge-disewa',
                'selesai' => 'badge-selesai',
                'dikembalikan' => 'badge-dikembalikan',
                default => 'badge-diproses',
            };
            ?>
            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
        </div>
        <div class="card-body">
            <div class="detail-item">
                <div class="detail-label">Nomor ID Order</div>
                <div class="detail-value">#<?= (int)$order['id_order'] ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Periode Tanggal Sewa</div>
                <div class="detail-value">
                    <i class="bi bi-calendar3"></i> <?= htmlspecialchars($order['tanggal_sewa'] ?? '-') ?> s/d <?= htmlspecialchars($order['tanggal_kembali'] ?? '-') ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Total Biaya Sewa</div>
                <div class="detail-value" style="color: var(--primary); font-size: 1.25rem;">
                    Rp <?= number_format((float)($order['total_harga'] ?? 0), 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Customer -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-person"></i> Data Pelanggan</strong>
            <a href="index.php?action=detail&id=<?= (int)$order['id_customer'] ?>" class="btn btn-outline btn-sm">
                <i class="bi bi-eye"></i> Lihat Profil
            </a>
        </div>
        <div class="card-body">
            <div class="detail-item">
                <div class="detail-label">Nama Pelanggan</div>
                <div class="detail-value"><?= htmlspecialchars($order['nama_customer'] ?? '(Tidak Ditemukan)') ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Nomor WhatsApp / HP</div>
                <div class="detail-value" style="display: flex; align-items: center; gap: 8px;">
                    <span><i class="bi bi-telephone"></i> <?= htmlspecialchars($order['no_hp_customer'] ?? '-') ?></span>
                    <?php if (!empty($waNumber)): ?>
                        <a href="https://wa.me/<?= $waNumber ?>?text=<?= urlencode($pesanWa) ?>" 
                           target="_blank" 
                           class="badge" 
                           style="background: #25d366; color: white; text-decoration: none; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px;"
                           title="Chat WhatsApp Pelanggan">
                            <i class="bi bi-whatsapp"></i> Chat WA
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Kartu Identitas Jaminan (KTP / SIM / Kartu Pelajar)</div>
                <div class="detail-value">
                    <?php 
                    $idFile = $order['ktp_customer'] ?? '';
                    $isImg = !empty($idFile) && (strpos($idFile, 'uploads/') !== false || preg_match('/\.(jpg|jpeg|png|webp)$/i', $idFile));
                    ?>
                    <?php if ($isImg): ?>
                        <div style="margin-top: 6px;">
                            <a href="<?= htmlspecialchars($idFile) ?>" target="_blank" title="Klik untuk membuka ukuran penuh">
                                <img src="<?= htmlspecialchars($idFile) ?>" style="max-height: 140px; max-width: 100%; border-radius: 8px; border: 1.5px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.06);" alt="Foto Identitas">
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

    <!-- Info Pembayaran -->
    <div class="card">
        <div class="card-header">
            <strong><i class="bi bi-wallet2"></i> Informasi Pembayaran</strong>
            <?php
            $statusBayar = strtolower($order['status_pembayaran'] ?? 'menunggu_konfirmasi');
            $badgeBayarClass = match($statusBayar) {
                'lunas' => 'badge-selesai',
                'dp_dibayar' => 'badge-disewa',
                'belum_bayar' => 'badge-stok-empty',
                default => 'badge-diproses',
            };
            $labelBayar = match($statusBayar) {
                'lunas' => 'Lunas',
                'dp_dibayar' => 'DP Terbayar',
                'belum_bayar' => 'Belum Bayar',
                default => 'Menunggu Konfirmasi',
            };
            ?>
            <span class="badge <?= $badgeBayarClass ?>"><?= $labelBayar ?></span>
        </div>
        <div class="card-body">
            <div class="detail-item">
                <div class="detail-label">Skema Pembayaran</div>
                <div class="detail-value">
                    <strong style="text-transform: uppercase;"><?= htmlspecialchars($order['tipe_pembayaran'] ?? 'lunas') ?></strong>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">(<?= (($order['tipe_pembayaran'] ?? '') === 'dp') ? 'Uang Muka 50%' : 'Lunas Penuh 100%' ?>)</span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Metode Pembayaran</div>
                <div class="detail-value">
                    <?php 
                    $metode = strtolower($order['metode_pembayaran'] ?? 'toko');
                    if ($metode === 'qris') {
                        echo '<span class="badge" style="background:#f0fdfa; color:#0d9488; border:1px solid #ccfbf1;"><i class="bi bi-qr-code me-1"></i> QRIS</span>';
                    } elseif ($metode === 'transfer') {
                        echo '<span class="badge" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;"><i class="bi bi-bank me-1"></i> Transfer Bank</span>';
                    } else {
                        echo '<span class="badge" style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;"><i class="bi bi-shop me-1"></i> Bayar di Toko</span>';
                    }
                    ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Nominal Dibayar</div>
                <div class="detail-value" style="color: var(--primary); font-size: 1.15rem; font-weight: 700;">
                    Rp <?= number_format((float)($order['nominal_bayar'] ?? $order['total_harga']), 0, ',', '.') ?>
                </div>
            </div>

            <?php if (($order['tipe_pembayaran'] ?? '') === 'dp'): ?>
                <div class="detail-item">
                    <div class="detail-label">Sisa Tagihan di Toko</div>
                    <div class="detail-value" style="color: #dc2626; font-weight: 700;">
                        Rp <?= number_format(max(0, (float)$order['total_harga'] - (float)$order['nominal_bayar']), 0, ',', '.') ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Ubah Status Pembayaran -->
            <form action="index.php?action=order_payment_status&redirect=detail" method="POST" style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px dashed var(--border-color); display: flex; gap: 8px; align-items: flex-end; flex-wrap: wrap;">
                <input type="hidden" name="id_order" value="<?= (int)$order['id_order'] ?>">
                <div class="form-group" style="margin-bottom: 0; flex-grow: 1; min-width: 170px;">
                    <label class="form-label" style="font-size: 0.775rem;">Status Pembayaran:</label>
                    <select name="status_pembayaran" class="form-control" style="font-size: 0.825rem; padding: 6px 10px;">
                        <option value="menunggu_konfirmasi" <?= ($statusBayar === 'menunggu_konfirmasi') ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                        <option value="dp_dibayar" <?= ($statusBayar === 'dp_dibayar') ? 'selected' : '' ?>>DP Terbayar (50%)</option>
                        <option value="lunas" <?= ($statusBayar === 'lunas') ? 'selected' : '' ?>>Lunas (100%)</option>
                        <option value="belum_bayar" <?= ($statusBayar === 'belum_bayar') ? 'selected' : '' ?>>Belum Bayar</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 7px 12px; font-weight: 600;">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- Info Jas -->
    <div class="card" style="grid-column: 1 / -1;">
        <div class="card-header">
            <strong><i class="bi bi-box-seam"></i> Rincian Jas yang Disewa</strong>
            <?php if (!empty($order['id_jas'])): ?>
                <a href="index.php?action=jas_detail&id=<?= (int)$order['id_jas'] ?>" class="btn btn-outline btn-sm">
                    <i class="bi bi-box-arrow-up-right"></i> Buka Detail Jas
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (!empty($order['id_jas'])): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="detail-item">
                        <div class="detail-label">Model Jas</div>
                        <div class="detail-value"><?= htmlspecialchars($order['Nama_jas'] ?? '-') ?></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Pilihan Ukuran & Warna</div>
                        <div class="detail-value">
                            <span class="badge" style="background: #f0fdfa; color: #0d9488; border: 1px solid #ccfbf1; font-weight: 700;">Ukuran <?= htmlspecialchars(!empty($order['ukuran_sewa']) ? $order['ukuran_sewa'] : ($order['Ukuran_jas'] ?? '-')) ?></span> 
                            &bull; Warna: <?= htmlspecialchars($order['Warna_jas'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tarif Sewa Jas Standar</div>
                        <div class="detail-value">Rp <?= number_format((float)($order['Harga_sewa'] ?? 0), 0, ',', '.') ?> / hari</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state" style="padding: 1.5rem;">
                    <p><em>(Belum ada spesifikasi jas khusus yang dikaitkan dengan pesanan ini)</em></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
