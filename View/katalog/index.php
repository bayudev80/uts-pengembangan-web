<?php
$pageTitle = 'Katalog Rental & Sewa Jas Premium';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?></title>

  <!-- Google Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    * {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    body {
      background-color: #f8fafc;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .navbar-custom {
      background: #0f172a;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 16px 0;
    }
    .hero-section {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0d9488 100%);
      color: white;
      padding: 60px 0 40px;
      border-radius: 0 0 30px 30px;
      margin-bottom: 40px;
    }
    .product-card {
      background: white;
      border-radius: 18px;
      border: 1px solid #e2e8f0;
      overflow: hidden;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 32px rgba(0,0,0,0.08);
      border-color: #cbd5e1;
    }
    .product-img-wrapper {
      position: relative;
      height: 280px;
      overflow: hidden;
      background: #f1f5f9;
    }
    .product-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }
    .product-card:hover .product-img {
      transform: scale(1.05);
    }
    .stock-badge {
      position: absolute;
      top: 14px;
      right: 14px;
      padding: 6px 14px;
      border-radius: 30px;
      font-size: 12px;
      font-weight: 700;
      backdrop-filter: blur(8px);
      white-space: nowrap;
    }
    .product-body {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    .price-tag {
      color: #0d9488;
      font-size: 20px;
      font-weight: 800;
      white-space: nowrap;
    }
    .btn-rent {
      background: linear-gradient(135deg, #14b8a6, #0d9488);
      color: white;
      border: none;
      font-weight: 600;
      padding: 10px 16px;
      border-radius: 10px;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    .btn-rent:hover:not(:disabled) {
      background: linear-gradient(135deg, #0d9488, #0f766e);
      color: white;
      box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    }
    .modal-header-custom {
      background: linear-gradient(135deg, #0f172a, #134e4a);
      color: white;
      border-bottom: none;
      padding: 20px 24px;
    }
    .size-radio-pill {
      min-width: 48px;
      text-align: center;
      border: 1.5px solid #cbd5e1;
      color: #334155;
      background: #f8fafc;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .size-radio-pill:hover {
      border-color: #0d9488;
      color: #0d9488;
      background: #f0fdfa;
    }
    .btn-check:checked + .size-radio-pill {
      background-color: #0d9488 !important;
      border-color: #0d9488 !important;
      color: #ffffff !important;
      box-shadow: 0 4px 12px rgba(13, 148, 136, 0.35);
    }
    .payment-plan-card, .payment-method-card {
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      color: #1e293b;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .payment-plan-card:hover, .payment-method-card:hover {
      border-color: #0d9488;
      background: #f0fdfa;
    }
    .btn-check:checked + .payment-plan-card,
    .btn-check:checked + .payment-method-card {
      background: #f0fdfa !important;
      border-color: #0d9488 !important;
      color: #0f766e !important;
      box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.25);
    }

    /* Footer Styling */
    .site-footer {
      background-color: #0b1120;
      color: #94a3b8;
      border-top: 1px solid #1e293b;
    }
    .footer-features-bar {
      background-color: #0f172a;
      border-bottom: 1px solid #1e293b;
      padding: 24px 0;
    }
    .feature-icon-box {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: rgba(13, 148, 136, 0.15);
      border: 1px solid rgba(13, 148, 136, 0.3);
      color: #14b8a6;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }
    .footer-main-content {
      background-color: #0b1120;
    }
    .footer-heading {
      color: #ffffff;
      font-weight: 700;
      font-size: 15px;
      margin-bottom: 18px;
      letter-spacing: 0.3px;
      position: relative;
    }
    .footer-heading::after {
      content: '';
      display: block;
      width: 28px;
      height: 2px;
      background: #14b8a6;
      margin-top: 6px;
      border-radius: 2px;
    }
    .footer-link-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .footer-link-list li {
      margin-bottom: 10px;
    }
    .footer-link-list li a {
      color: #94a3b8;
      text-decoration: none;
      font-size: 13.5px;
      transition: all 0.2s ease;
      display: inline-block;
    }
    .footer-link-list li a:hover {
      color: #14b8a6;
      transform: translateX(4px);
    }
    .social-icon-btn {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: #1e293b;
      color: #94a3b8;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 14px;
      transition: all 0.2s ease;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .social-icon-btn:hover {
      background: #14b8a6;
      color: #ffffff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
    }
    .footer-contact-item {
      display: flex;
      align-items: flex-start;
    }
    .status-indicator-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #22c55e;
      display: inline-block;
      box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3);
      animation: pulseGreen 2s infinite;
    }
    @keyframes pulseGreen {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.6; transform: scale(1.2); }
    }
    .payment-pill {
      background: #1e293b;
      color: #e2e8f0;
      font-size: 11.5px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 6px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      display: inline-flex;
      align-items: center;
    }
    .footer-bottom-bar {
      background-color: #070b14;
      border-top: 1px solid #1e293b;
    }
    .hover-white:hover {
      color: #ffffff !important;
    }
    .text-teal {
      color: #14b8a6 !important;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4" href="katalog.php">
      <div class="rounded-3 bg-teal p-2 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="background:#14b8a6; width:36px; height:36px;">
        <i class="fa fa-vest"></i>
      </div>
      <span class="text-nowrap">Rental Jas Modern</span>
    </a>
    
    <div class="ms-auto d-flex align-items-center gap-2">
      <a href="index.php?action=dashboard" class="btn btn-outline-light btn-sm px-3 rounded-pill text-nowrap fw-semibold">
        <i class="fa fa-gauge-high me-1"></i> Panel Admin
      </a>
    </div>
  </div>
</nav>

<!-- Hero Section & Pencarian -->
<div class="hero-section">
  <div class="container text-center">
    <h1 class="display-6 fw-bold mb-2">Koleksi Jas & Tuxedo Pria Terbaik</h1>
    <p class="text-light opacity-75 mb-4 mx-auto" style="max-width: 600px;">
      Tampil percaya diri di setiap momen spesial: pernikahan, wisuda, acara formal, dan pesta dengan koleksi jas premium kami.
    </p>

    <!-- Form Pencarian -->
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">
        <form method="GET" action="index.php" class="d-flex gap-2 bg-white p-2 rounded-pill shadow-lg">
          <input type="hidden" name="action" value="katalog">
          <input type="text" name="cari" class="form-control border-0 rounded-pill ps-3 shadow-none" placeholder="Cari nama jas, warna, atau ukuran..." value="<?= htmlspecialchars($search ?? '') ?>">
          <button type="submit" class="btn btn-dark rounded-pill px-4 fw-semibold text-nowrap">
            <i class="fa fa-search me-1"></i> Cari
          </button>
          <?php if (!empty($search)): ?>
          <a href="index.php?action=katalog" class="btn btn-outline-secondary rounded-pill px-3 text-nowrap">Reset</a>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="container mb-5" id="form-sewa">

  <!-- Flash Message Notifikasi -->
  <?php if (!empty($flash)): 
      $alertType = ($flash['type'] === 'success') ? 'success' : 'danger';
      $alertIcon = ($flash['type'] === 'success') ? 'fa-circle-check' : 'fa-triangle-exclamation';
  ?>
  <div class="alert alert-<?= $alertType ?> alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
      <div class="d-flex align-items-center gap-2">
          <i class="fa-solid <?= $alertIcon ?> fs-5"></i>
          <div><?= $flash['message'] ?? '' ?></div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Katalog Pilihan</h4>
      <small class="text-muted">Pilih jas favorit Anda dan tentukan durasi sewa yang diinginkan</small>
    </div>
  </div>

  <!-- Navigasi Kategori Jas -->
  <div class="d-flex align-items-center gap-2 mb-4 overflow-x-auto pb-1 flex-nowrap flex-md-wrap">
    <a href="index.php?action=katalog<?= !empty($search) ? '&cari=' . urlencode($search) : ''; ?>#form-sewa" 
       class="btn btn-sm rounded-pill px-3 fw-semibold text-nowrap <?= empty($selectedKategori) ? 'text-white' : 'btn-outline-secondary bg-white' ?>" 
       style="<?= empty($selectedKategori) ? 'background:#0d9488;' : ''; ?>">
      <i class="fa fa-layer-group me-1"></i> Semua Koleksi
    </a>
    <?php if (!empty($categories)): ?>
      <?php foreach ($categories as $cat): ?>
        <a href="index.php?action=katalog&kategori=<?= urlencode($cat); ?><?= !empty($search) ? '&cari=' . urlencode($search) : ''; ?>#form-sewa" 
           class="btn btn-sm rounded-pill px-3 fw-semibold text-nowrap <?= ($selectedKategori === $cat) ? 'text-white' : 'btn-outline-secondary bg-white' ?>"
           style="<?= ($selectedKategori === $cat) ? 'background:#0d9488;' : ''; ?>">
          <i class="fa fa-tag me-1"></i> <?= htmlspecialchars($cat); ?>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Daftar Katalog Jas -->
  <div class="row g-4">
    <?php
    if (!empty($jasList)) {
        foreach ($jasList as $j) {
            $is_available = ((int)$j['Stok'] > 0);
            $availableSizes = array_filter(array_map('trim', explode(',', $j['Ukuran_jas'] ?? '')));
            if (empty($availableSizes)) {
                $availableSizes = ['S', 'M', 'L', 'XL', 'XXL'];
            }
    ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="product-card">
        <div class="product-img-wrapper">
          <img src="<?= htmlspecialchars(getFotoJas($j['foto'] ?? '')); ?>" class="product-img" alt="<?= htmlspecialchars($j['Nama_jas']); ?>">
          
          <?php if ($is_available): ?>
            <span class="stock-badge bg-white text-dark shadow-sm">
              <i class="fa fa-check text-success me-1"></i> Stok <?= (int)$j['Stok']; ?>
            </span>
          <?php else: ?>
            <span class="stock-badge bg-danger text-white shadow-sm">
              <i class="fa fa-times me-1"></i> Stok Habis
            </span>
          <?php endif; ?>
        </div>

        <div class="product-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="badge" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; font-size: 0.75rem;">
              <i class="fa fa-tag me-1"></i><?= htmlspecialchars($j['kategori'] ?? 'Formal'); ?>
            </span>
            <span class="small text-muted"><i class="fa fa-palette me-1"></i><?= htmlspecialchars($j['Warna_jas']); ?></span>
          </div>

          <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.05rem;">
            <?= htmlspecialchars($j['Nama_jas']); ?>
          </h5>

          <!-- Pilihan Ukuran Tersedia -->
          <div class="mb-3">
            <div class="small text-muted mb-1" style="font-size: 0.75rem;">Ukuran Tersedia:</div>
            <div class="d-flex flex-wrap gap-1">
              <?php foreach ($availableSizes as $sz): ?>
                <span class="badge bg-light text-dark border fw-semibold" style="font-size: 0.72rem; padding: 4px 7px;"><?= htmlspecialchars($sz); ?></span>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="mt-auto pt-2 border-top d-flex flex-column gap-2">
            <div class="d-flex align-items-baseline justify-content-between">
              <span class="small text-muted text-nowrap">Biaya sewa:</span>
              <span class="price-tag"><?= formatRupiah($j['Harga_sewa']); ?></span>
            </div>

            <button class="btn btn-rent w-100" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modalSewa<?= $j['id_jas']; ?>"
                    <?= !$is_available ? 'disabled' : ''; ?>>
              <i class="fa fa-calendar-check me-1"></i> <?= $is_available ? 'Sewa Sekarang' : 'Stok Kosong' ?>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Booking Sewa -->
    <div class="modal fade" id="modalSewa<?= $j['id_jas']; ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-2xl">
          <div class="modal-header modal-header-custom">
            <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 text-nowrap">
              <i class="fa fa-cart-shopping"></i> Formulir Sewa Jas
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body p-4">
            <div class="row g-4">
              <!-- Info Jas -->
              <div class="col-md-5 border-end">
                <img src="<?= htmlspecialchars(getFotoJas($j['foto'] ?? '')); ?>" class="img-fluid rounded-4 mb-3 shadow-sm w-100 object-fit-cover" style="height: 220px;" alt="<?= htmlspecialchars($j['Nama_jas']); ?>">
                <div class="mb-2">
                  <span class="badge" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; font-size: 0.75rem;">
                    <i class="fa fa-tag me-1"></i><?= htmlspecialchars($j['kategori'] ?? 'Formal'); ?>
                  </span>
                </div>
                <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($j['Nama_jas']); ?></h5>
                <p class="text-muted small mb-2">
                  Warna: <strong><?= htmlspecialchars($j['Warna_jas']); ?></strong>
                </p>
                <div class="h5 fw-bold text-nowrap mb-3" style="color: #0d9488;">
                  <?= formatRupiah($j['Harga_sewa']); ?> <small class="text-muted fs-6 fw-normal">/ 3 hari sewa</small>
                </div>
                <div class="p-3 bg-light rounded-3 small">
                  <i class="fa fa-info-circle text-teal me-1"></i>
                  Sewa standar 3 hari. Keterlambatan dikenakan denda Rp 50.000/hari.
                </div>
              </div>

              <!-- Formulir Penyewa -->
              <div class="col-md-7">
                <form method="POST" action="index.php?action=sewa_proses" enctype="multipart/form-data">
                  <input type="hidden" name="id_jas" value="<?= $j['id_jas']; ?>">

                  <!-- Pilihan Ukuran Jas -->
                  <div class="mb-3">
                    <label class="form-label small fw-semibold d-flex justify-content-between align-items-center mb-2">
                      <span><i class="fa fa-ruler-combined me-1" style="color: #0d9488;"></i> Pilih Ukuran Jas <span class="text-danger">*</span></span>
                      <span class="text-muted" style="font-size: 0.75rem;">Pilih sesuai ukuran Anda</span>
                    </label>
                    <div class="d-flex flex-wrap gap-2">
                      <?php foreach ($availableSizes as $idx => $s): ?>
                        <input type="radio" class="btn-check" name="ukuran_sewa" 
                               id="ukuran_<?= $j['id_jas']; ?>_<?= $idx; ?>" 
                               value="<?= htmlspecialchars($s); ?>" 
                               <?= $idx === 0 ? 'checked' : ''; ?> required>
                        <label class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 fw-bold size-radio-pill" 
                               for="ukuran_<?= $j['id_jas']; ?>_<?= $idx; ?>">
                          <?= htmlspecialchars($s); ?>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_customer" class="form-control" 
                           placeholder="Nama sesuai kartu identitas" 
                           oninput="this.value = this.value.replace(/[0-9]/g, '')"
                           pattern="^[^0-9]+$" title="Nama tidak boleh mengandung angka" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label small fw-semibold">No. WhatsApp / HP <span class="text-danger">*</span></label>
                    <input type="tel" name="no_hp" class="form-control input-numeric-only" 
                           placeholder="Nomor WhatsApp aktif" 
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/\D/g, '')"
                           pattern="[0-9]+" title="Nomor HP hanya boleh berisi angka" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label small fw-semibold d-flex justify-content-between align-items-center mb-1">
                      <span><i class="fa fa-id-card me-1" style="color: #0d9488;"></i> Foto Kartu Identitas Jaminan <span class="text-danger">*</span></span>
                      <span class="badge bg-light text-muted border" style="font-size: 0.7rem; font-weight: 500;">KTP / SIM / Kartu Pelajar</span>
                    </label>
                    <input type="file" name="foto_identitas" class="form-control input-foto-identitas" 
                           accept="image/png, image/jpeg, image/jpg, image/webp" 
                           data-preview="previewIdentitas<?= $j['id_jas']; ?>" required>
                    <div class="form-text text-muted" style="font-size: 0.75rem;">
                      Upload foto KTP, SIM, atau Kartu Pelajar asli sebagai jaminan sewa (Maks. 5 MB).
                    </div>
                    <div id="previewIdentitas<?= $j['id_jas']; ?>" class="mt-2 text-center d-none">
                      <div class="position-relative d-inline-block border rounded-3 p-1 bg-light">
                        <img src="" class="img-preview-target rounded-2 shadow-sm" style="max-height: 120px; max-width: 100%; object-fit: contain;" alt="Preview Identitas">
                        <span class="badge bg-success position-absolute top-0 end-0 m-1" style="font-size: 0.65rem;">
                          <i class="fa fa-check me-1"></i> Foto Siap
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row g-2 mb-3">
                    <div class="col-md-6">
                      <label class="form-label small fw-semibold">Tanggal Mulai Sewa</label>
                      <input type="date" name="tanggal_sewa" class="form-control input-tgl-sewa" value="<?= date('Y-m-d'); ?>" required data-id="<?= $j['id_jas']; ?>" data-harga="<?= $j['Harga_sewa']; ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-semibold">Tanggal Pengembalian</label>
                      <input type="date" name="tanggal_kembali" class="form-control input-tgl-kembali" value="<?= date('Y-m-d', strtotime('+2 days')); ?>" required data-id="<?= $j['id_jas']; ?>" data-harga="<?= $j['Harga_sewa']; ?>">
                    </div>
                  </div>

                  <!-- Pilihan Skema Pembayaran -->
                  <div class="mb-3">
                    <label class="form-label small fw-semibold d-flex justify-content-between align-items-center mb-2">
                      <span><i class="fa fa-money-bill-wave me-1" style="color:#0d9488;"></i> Skema Pembayaran <span class="text-danger">*</span></span>
                      <span class="text-muted" style="font-size: 0.75rem;">Bisa DP atau Lunas</span>
                    </label>
                    <div class="row g-2">
                      <div class="col-6">
                        <input type="radio" class="btn-check input-tipe-bayar" name="tipe_pembayaran" 
                               id="tipe_lunas_<?= $j['id_jas']; ?>" value="lunas" checked 
                               data-id="<?= $j['id_jas']; ?>">
                        <label class="btn btn-outline-secondary w-100 p-2 text-start rounded-3 payment-plan-card" 
                               for="tipe_lunas_<?= $j['id_jas']; ?>">
                          <div class="d-flex justify-content-between align-items-center">
                            <strong class="d-block" style="font-size:0.85rem;">Langsung Lunas</strong>
                            <span class="badge bg-success" style="font-size:0.68rem;">100%</span>
                          </div>
                          <small class="text-muted d-block" style="font-size:0.75rem;">Bayar penuh sekarang</small>
                        </label>
                      </div>
                      <div class="col-6">
                        <input type="radio" class="btn-check input-tipe-bayar" name="tipe_pembayaran" 
                               id="tipe_dp_<?= $j['id_jas']; ?>" value="dp" 
                               data-id="<?= $j['id_jas']; ?>">
                        <label class="btn btn-outline-secondary w-100 p-2 text-start rounded-3 payment-plan-card" 
                               for="tipe_dp_<?= $j['id_jas']; ?>">
                          <div class="d-flex justify-content-between align-items-center">
                            <strong class="d-block" style="font-size:0.85rem;">DP Dulu (50%)</strong>
                            <span class="badge" style="background:#0d9488; font-size:0.68rem;">Uang Muka</span>
                          </div>
                          <small class="text-muted d-block" style="font-size:0.75rem;">Pelunasan saat ambil jas</small>
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Pilihan Metode Pembayaran -->
                  <div class="mb-3">
                    <label class="form-label small fw-semibold d-flex justify-content-between align-items-center mb-2">
                      <span><i class="fa fa-credit-card me-1" style="color:#0d9488;"></i> Metode Pembayaran <span class="text-danger">*</span></span>
                    </label>
                    <div class="d-flex flex-column gap-2">
                      <div>
                        <input type="radio" class="btn-check input-metode-bayar" name="metode_pembayaran" 
                               id="metode_qris_<?= $j['id_jas']; ?>" value="qris" checked 
                               data-id="<?= $j['id_jas']; ?>">
                        <label class="btn btn-outline-secondary w-100 p-2 text-start rounded-3 payment-method-card" 
                               for="metode_qris_<?= $j['id_jas']; ?>">
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                              <i class="fa fa-qrcode fs-5" style="color:#0d9488;"></i>
                              <div>
                                <strong class="d-block" style="font-size:0.85rem;">QRIS (Instan)</strong>
                                <small class="text-muted" style="font-size:0.75rem;">BCA, GoPay, OVO, Dana, ShopeePay</small>
                              </div>
                            </div>
                            <span class="badge bg-light text-dark border" style="font-size:0.7rem;">Scan QR</span>
                          </div>
                        </label>
                      </div>

                      <div>
                        <input type="radio" class="btn-check input-metode-bayar" name="metode_pembayaran" 
                               id="metode_transfer_<?= $j['id_jas']; ?>" value="transfer" 
                               data-id="<?= $j['id_jas']; ?>">
                        <label class="btn btn-outline-secondary w-100 p-2 text-start rounded-3 payment-method-card" 
                               for="metode_transfer_<?= $j['id_jas']; ?>">
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                              <i class="fa fa-building-columns fs-5" style="color:#0d9488;"></i>
                              <div>
                                <strong class="d-block" style="font-size:0.85rem;">Transfer Bank Manual</strong>
                                <small class="text-muted" style="font-size:0.75rem;">BCA / Mandiri</small>
                              </div>
                            </div>
                            <span class="badge bg-light text-dark border" style="font-size:0.7rem;">Transfer</span>
                          </div>
                        </label>
                      </div>

                      <div>
                        <input type="radio" class="btn-check input-metode-bayar" name="metode_pembayaran" 
                               id="metode_toko_<?= $j['id_jas']; ?>" value="toko" 
                               data-id="<?= $j['id_jas']; ?>">
                        <label class="btn btn-outline-secondary w-100 p-2 text-start rounded-3 payment-method-card" 
                               for="metode_toko_<?= $j['id_jas']; ?>">
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                              <i class="fa fa-store fs-5" style="color:#0d9488;"></i>
                              <div>
                                <strong class="d-block" style="font-size:0.85rem;">Bayar Langsung di Toko</strong>
                                <small class="text-muted" style="font-size:0.75rem;">Bayar tunai/debit saat fitting & ambil jas</small>
                              </div>
                            </div>
                            <span class="badge bg-light text-dark border" style="font-size:0.7rem;">Cash Toko</span>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Panduan Rekening & QRIS Dinamis -->
                  <div class="p-3 rounded-3 mb-3 border small" id="guideBox<?= $j['id_jas']; ?>" style="background:#f0fdfa; border-color:#ccfbf1 !important;">
                    <div id="guideQris<?= $j['id_jas']; ?>">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa fa-qrcode" style="color:#0d9488;"></i>
                        <strong style="color:#0f766e;">Instruksi Pembayaran QRIS:</strong>
                      </div>
                      <p class="text-muted mb-0" style="font-size:0.78rem;">
                        Scan QRIS dapat dilakukan dengan m-BCA, GoPay, OVO, Dana, atau ShopeePay pada layar konfirmasi setelah booking.
                      </p>
                    </div>
                    <div id="guideTransfer<?= $j['id_jas']; ?>" style="display:none;">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa fa-building-columns" style="color:#0d9488;"></i>
                        <strong style="color:#0f766e;">Nomor Rekening Rental Jas:</strong>
                      </div>
                      <div class="text-dark mb-0" style="font-size:0.78rem; line-height: 1.5;">
                        <div>&bull; <strong>BCA: 827-091-8822</strong> a.n. Rental Jas Modern</div>
                        <div>&bull; <strong>Mandiri: 137-00-1928374-1</strong> a.n. Rental Jas Modern</div>
                      </div>
                    </div>
                    <div id="guideToko<?= $j['id_jas']; ?>" style="display:none;">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa fa-store" style="color:#0d9488;"></i>
                        <strong style="color:#0f766e;">Bayar di Toko:</strong>
                      </div>
                      <p class="text-muted mb-0" style="font-size:0.78rem;">
                        Silakan datang ke toko kami untuk fitting jas dan bayar langsung di kasir (tunai/debit).
                      </p>
                    </div>
                  </div>

                  <!-- Ringkasan Biaya & Tagihan Sekarang -->
                  <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small class="text-muted">Durasi Sewa:</small>
                      <small class="fw-semibold text-dark"><span id="modalDurasi<?= $j['id_jas']; ?>">3</span> hari (<span id="modalDenda<?= $j['id_jas']; ?>">Tanpa Denda</span>)</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
                      <small class="text-muted">Total Tarif Sewa:</small>
                      <strong class="text-dark" id="modalTotal<?= $j['id_jas']; ?>"><?= formatRupiah($j['Harga_sewa']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-1">
                      <div>
                        <strong class="d-block text-dark" style="font-size:0.85rem;" id="modalLabelBayarSekarang<?= $j['id_jas']; ?>">Bayar Penuh (100%):</strong>
                        <small class="text-muted" style="font-size:0.72rem;" id="modalSubBayarSekarang<?= $j['id_jas']; ?>">Lunas saat ini</small>
                      </div>
                      <span class="fw-bold fs-5 text-nowrap" style="color:#0d9488;" id="modalTagihanSekarang<?= $j['id_jas']; ?>">
                        <?= formatRupiah($j['Harga_sewa']); ?>
                      </span>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-rent w-100 py-2">
                    <i class="fa fa-wallet me-1"></i> Konfirmasi & Bayar Sewa
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
        }
    } else {
    ?>
    <div class="col-12 py-5 text-center text-muted">
      <i class="fa fa-box-open fs-1 mb-3 d-block text-secondary"></i>
      <h5>Tidak ada jas yang sesuai dengan pencarian Anda.</h5>
      <a href="index.php?action=katalog" class="btn btn-outline-dark mt-2 rounded-pill px-4 text-nowrap">Tampilkan Semua Koleksi</a>
    </div>
    <?php } ?>
  </div>

</div>

<?php if (!empty($bookingSuccess)): ?>
<!-- Modal Pop-up Notifikasi Sewa Berhasil -->
<div class="modal fade" id="modalSuccessBooking" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-body p-4 text-center">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3" style="width: 72px; height: 72px; font-size: 34px; background: #0d9488; box-shadow: 0 10px 25px rgba(13, 148, 136, 0.35);">
          <i class="fa fa-check"></i>
        </div>
        
        <h4 class="fw-bold text-dark mb-1">Penyewaan Berhasil!</h4>
        <p class="text-muted small mb-3">
          Terima kasih, <strong><?= htmlspecialchars($bookingSuccess['nama_customer']) ?></strong>! Pesanan sewa Anda telah kami terima.
        </p>

        <!-- Rincian Pesanan di Pop-up -->
        <div class="bg-light p-3 rounded-3 text-start small border mb-3">
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span class="text-muted">Nomor Order:</span>
            <span class="fw-bold text-dark">#ORD-<?= str_pad((string)$bookingSuccess['order_id'], 4, '0', STR_PAD_LEFT) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span class="text-muted">Model Jas:</span>
            <span class="fw-bold text-dark"><?= htmlspecialchars($bookingSuccess['nama_jas']) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span class="text-muted">Ukuran & Warna:</span>
            <span class="fw-semibold text-dark"><?= htmlspecialchars($bookingSuccess['ukuran_jas']) ?> &bull; <?= htmlspecialchars($bookingSuccess['warna_jas']) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span class="text-muted">Jadwal Sewa:</span>
            <span class="fw-semibold text-dark"><?= formatTanggal($bookingSuccess['tanggal_sewa']) ?> &ndash; <?= formatTanggal($bookingSuccess['tanggal_kembali']) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span class="text-muted">Skema & Metode:</span>
            <span class="fw-semibold text-dark text-uppercase"><?= htmlspecialchars($bookingSuccess['tipe_pembayaran'] ?? 'lunas') ?> &bull; <?= htmlspecialchars($bookingSuccess['metode_pembayaran'] ?? 'toko') ?></span>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Total Tarif:</span>
            <span class="fw-semibold text-dark"><?= formatRupiah($bookingSuccess['total_harga']) ?></span>
          </div>
          <div class="d-flex justify-content-between align-items-center pt-1 border-top">
            <span class="fw-bold text-dark">Tagihan Sekarang:</span>
            <span class="fw-bold fs-5" style="color: #0d9488;"><?= formatRupiah($bookingSuccess['nominal_bayar'] ?? $bookingSuccess['total_harga']) ?></span>
          </div>
          <?php if (!empty($bookingSuccess['sisa_bayar']) && $bookingSuccess['sisa_bayar'] > 0): ?>
          <div class="d-flex justify-content-between align-items-center pt-1">
            <span class="text-muted" style="font-size:0.75rem;">Sisa Pelunasan di Toko:</span>
            <span class="text-danger fw-semibold" style="font-size:0.8rem;"><?= formatRupiah($bookingSuccess['sisa_bayar']) ?></span>
          </div>
          <?php endif; ?>
        </div>

        <?php 
        $metodeSukses = strtolower($bookingSuccess['metode_pembayaran'] ?? 'toko');
        if ($metodeSukses === 'transfer'): 
        ?>
        <div class="p-3 bg-light rounded-3 text-start small border mb-3">
          <strong class="d-block text-dark mb-1"><i class="fa fa-building-columns text-teal me-1"></i> Rekening Pembayaran:</strong>
          <div>BCA: <strong>827-091-8822</strong> a.n. Rental Jas Modern</div>
          <div>Mandiri: <strong>137-00-1928374-1</strong> a.n. Rental Jas Modern</div>
          <small class="text-muted d-block mt-1">Harap kirimkan bukti transfer melalui WhatsApp admin.</small>
        </div>
        <?php elseif ($metodeSukses === 'qris'): ?>
        <div class="p-3 bg-light rounded-3 text-start small border mb-3">
          <strong class="d-block text-dark mb-1"><i class="fa fa-qrcode text-teal me-1"></i> Pembayaran QRIS:</strong>
          <div>Scan QRIS kami melalui aplikasi BCA Mobile, GoPay, OVO, Dana, atau ShopeePay. Admin akan mengirimkan barcode QRIS ke nomor WhatsApp Anda.</div>
        </div>
        <?php else: ?>
        <div class="p-3 bg-light rounded-3 text-start small border mb-3">
          <strong class="d-block text-dark mb-1"><i class="fa fa-store text-teal me-1"></i> Bayar di Toko:</strong>
          <div>Silakan lakukan pembayaran langsung secara tunai/debit di kasir toko saat jadwal fitting & pengambilan jas.</div>
        </div>
        <?php endif; ?>

        <div class="alert alert-info py-2 px-3 small rounded-3 d-flex align-items-center gap-2 mb-3 text-start">
          <i class="fa fa-circle-info fs-5 flex-shrink-0 text-teal"></i>
          <div>Admin kami akan segera menghubungi WhatsApp <strong><?= htmlspecialchars($bookingSuccess['no_hp']) ?></strong> untuk konfirmasi pesanan & fitting jas.</div>
        </div>

        <button type="button" class="btn text-white w-100 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal" style="background:#0d9488;">
          <i class="fa fa-check me-1"></i> Selesai
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Footer Lengkap Web Penyewa -->
<footer class="site-footer mt-auto">
  <!-- 1. Highlight Fitur & Nilai Keunggulan Layanan -->
  <div class="footer-features-bar">
    <div class="container">
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <div class="d-flex align-items-center gap-3">
            <div class="feature-icon-box">
              <i class="fa fa-gem"></i>
            </div>
            <div>
              <h6 class="mb-0 fw-bold text-white fs-6">Koleksi Jas Premium</h6>
              <small class="text-secondary" style="font-size: 0.78rem;">Bahan jas mewah, rapi & model modern</small>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="d-flex align-items-center gap-3">
            <div class="feature-icon-box">
              <i class="fa fa-wallet"></i>
            </div>
            <div>
              <h6 class="mb-0 fw-bold text-white fs-6">DP 50% atau Lunas</h6>
              <small class="text-secondary" style="font-size: 0.78rem;">QRIS, Transfer Bank, & Kasir Toko</small>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="d-flex align-items-center gap-3">
            <div class="feature-icon-box">
              <i class="fa fa-ruler-combined"></i>
            </div>
            <div>
              <h6 class="mb-0 fw-bold text-white fs-6">Fitting di Toko</h6>
              <small class="text-secondary" style="font-size: 0.78rem;">Bisa coba langsung & konsultasi ukuran</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Konten Utama Footer 4 Kolom -->
  <div class="footer-main-content py-5">
    <div class="container">
      <div class="row g-4 g-lg-5">
        <!-- Kolom 1: Profil Brand & Social Media -->
        <div class="col-12 col-md-6 col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="background:#14b8a6; width:40px; height:40px;">
              <i class="fa fa-vest fs-5"></i>
            </div>
            <span class="fs-4 fw-bold text-white">Rental Jas Modern</span>
          </div>
          <p class="text-secondary small mb-4" style="line-height: 1.7;">
            Pusat persewaan jas, blazer, dan tuxedo pria terlengkap untuk pernikahan, wisuda, prewedding, pesta, dan rapat formal. Tampil berkelas dan percaya diri dengan busana berkualitas tinggi serta harga sewa bersahabat.
          </p>
          <div>
            <div class="small fw-semibold text-white mb-2">Media Sosial & Komunitas:</div>
            <div class="d-flex gap-2">
              <a href="#" class="social-icon-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
              <a href="https://wa.me/6282146789679" target="_blank" class="social-icon-btn" title="WhatsApp Admin 082146789679"><i class="fab fa-whatsapp"></i></a>
              <a href="#" class="social-icon-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
              <a href="#" class="social-icon-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="social-icon-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>

        <!-- Kolom 2: Navigasi Kategori Jas -->
        <div class="col-6 col-md-3 col-lg-2">
          <h6 class="footer-heading">Koleksi Jas</h6>
          <ul class="footer-link-list">
            <li><a href="index.php?action=katalog#form-sewa"><i class="fa fa-angle-right me-1"></i> Semua Koleksi</a></li>
            <li><a href="index.php?action=katalog&kategori=Pernikahan#form-sewa"><i class="fa fa-angle-right me-1"></i> Jas Pernikahan</a></li>
            <li><a href="index.php?action=katalog&kategori=Wisuda#form-sewa"><i class="fa fa-angle-right me-1"></i> Jas Wisuda</a></li>
            <li><a href="index.php?action=katalog&kategori=Formal#form-sewa"><i class="fa fa-angle-right me-1"></i> Jas Formal & Kerja</a></li>
            <li><a href="index.php?action=katalog&kategori=Casual#form-sewa"><i class="fa fa-angle-right me-1"></i> Blazer Casual</a></li>
            <li><a href="index.php?action=katalog#form-sewa"><i class="fa fa-angle-right me-1"></i> Tuxedo Eksklusif</a></li>
          </ul>
        </div>

        <!-- Kolom 3: Layanan & Informasi Sewa -->
        <div class="col-6 col-md-3 col-lg-3">
          <h6 class="footer-heading">Bantuan Sewa</h6>
          <ul class="footer-link-list">
            <li><a href="javascript:void(0)" onclick="alert('Panduan Ukuran Jas:\n- S: Lingkar Dada 96 cm, Panjang 68 cm\n- M: Lingkar Dada 100 cm, Panjang 70 cm\n- L: Lingkar Dada 104 cm, Panjang 72 cm\n- XL: Lingkar Dada 108 cm, Panjang 74 cm\n- XXL: Lingkar Dada 114 cm, Panjang 76 cm\n\nAnda juga dapat berkunjung langsung ke toko kami untuk fitting.')"><i class="fa fa-angle-right me-1"></i> Panduan Ukuran (Size Chart)</a></li>
            <li><a href="javascript:void(0)" onclick="alert('Syarat & Ketentuan Sewa:\n1. Menunjukkan KTP/SIM asli saat pengambilan jas.\n2. Pembayaran dapat berupa Uang Muka DP 50% atau Lunas 100%.\n3. Jas wajib dikembalikan dalam kondisi baik dan tidak rusak.')"><i class="fa fa-angle-right me-1"></i> Syarat & Ketentuan</a></li>
            <li><a href="javascript:void(0)" onclick="alert('Kebijakan Durasi & Denda:\n- Standar sewa adalah 3 hari (hari ambil, hari acara, hari kembali).\n- Keterlambatan dikenakan denda Rp 50.000 per hari keterlambatan.')"><i class="fa fa-angle-right me-1"></i> Durasi & Denda Keterlambatan</a></li>
            <li><a href="javascript:void(0)" onclick="alert('Skema Pembayaran:\n- Bisa bayar DP 50% terlebih dahulu saat memesan secara online.\n- Sisa 50% dapat dilunasi saat fitting dan pengambilan jas di toko.')"><i class="fa fa-angle-right me-1"></i> Skema Pembayaran DP / Lunas</a></li>
            <li><a href="https://wa.me/6282146789679" target="_blank"><i class="fa fa-angle-right me-1"></i> Chat WhatsApp Admin (082146789679)</a></li>
          </ul>
        </div>

        <!-- Kolom 4: Kontak & Lokasi Toko -->
        <div class="col-12 col-md-6 col-lg-3">
          <h6 class="footer-heading">Kontak & Lokasi Toko</h6>
          <div class="footer-contact-item mb-2">
            <i class="fa fa-location-dot text-teal me-2 mt-1 flex-shrink-0"></i>
            <span class="text-secondary small">Jl. Gatot Subroto No. 88, Denpasar, Bali - Indonesia</span>
          </div>
          <div class="footer-contact-item mb-2">
            <i class="fa fa-clock text-teal me-2 mt-1 flex-shrink-0"></i>
            <div class="small text-secondary">
              <span class="d-block text-white fw-semibold">Jam Operasional Toko:</span>
              <span>Senin - Sabtu: 09:00 - 21:00 WITA</span><br>
              <span>Minggu / Libur: 10:00 - 18:00 WITA</span>
            </div>
          </div>
          <div class="footer-contact-item mb-2">
            <i class="fa fa-phone text-teal me-2 mt-1 flex-shrink-0"></i>
            <span class="text-secondary small">082146789679 (WhatsApp & Telp)</span>
          </div>
          <div class="footer-contact-item mb-3">
            <i class="fa fa-envelope text-teal me-2 mt-1 flex-shrink-0"></i>
            <span class="text-secondary small">cs@rentaljasmodern.com</span>
          </div>
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill small" style="background:#134e4a; color:#5eead4; border: 1px solid #0d9488;">
            <span class="status-indicator-dot"></span>
            <span style="font-size:0.75rem; font-weight:600;">Toko Buka Hari Ini</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Metode Pembayaran Resmi -->
  <div class="footer-payment-bar py-3 border-top border-secondary border-opacity-25">
    <div class="container">
      <div class="row align-items-center g-3 text-center text-md-start">
        <div class="col-md-6">
          <span class="small text-secondary d-block d-md-inline me-2 fw-semibold">Metode Pembayaran:</span>
          <div class="d-inline-flex flex-wrap gap-2 justify-content-center justify-content-md-start align-items-center mt-2 mt-md-0">
            <span class="payment-pill"><i class="fa fa-qrcode text-teal me-1"></i> QRIS</span>
            <span class="payment-pill"><i class="fa fa-building-columns text-teal me-1"></i> Bank BCA</span>
            <span class="payment-pill"><i class="fa fa-building-columns text-teal me-1"></i> Bank Mandiri</span>
            <span class="payment-pill"><i class="fa fa-store text-teal me-1"></i> Kasir Toko</span>
          </div>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <span class="small text-secondary">
            <i class="fa fa-shield-halved text-teal me-1"></i> Transaksi Aman & Terenkripsi &bull; Kualitas Jas Rapi & Siap Pakai
          </span>
        </div>
      </div>
    </div>
  </div>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Sub-Footer (Copyright & Navigasi Bawah) -->
  <div class="footer-bottom-bar py-3">
    <div class="container">
      <div class="row align-items-center g-2 text-center text-md-start">
        <div class="col-md-6">
          <p class="mb-0 small text-secondary">
            &copy; <?= date('Y'); ?> <strong class="text-white">Rental Jas Modern</strong>. Seluruh hak cipta dilindungi undang-undang.
          </p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <ul class="list-inline mb-0 small">
            <li class="list-inline-item"><a href="javascript:void(0)" onclick="alert('Kebijakan Privasi:\nKami berkomitmen menjaga kerahasiaan data pribadi seluruh penyewa. Informasi kontak hanya digunakan untuk proses verifikasi pesanan, fitting, dan pengembalian jas.')" class="text-secondary text-decoration-none hover-white">Kebijakan Privasi</a></li>
            <li class="list-inline-item text-secondary">&bull;</li>
            <li class="list-inline-item"><a href="javascript:void(0)" onclick="alert('Syarat Penggunaan:\nPelanggan bertanggung jawab merawat jas selama masa sewa. Kerusakan permanen atau noda yang tidak dapat dibersihkan akan dikenakan biaya penggantian proporsional.')" class="text-secondary text-decoration-none hover-white">Syarat & Ketentuan</a></li>
            <li class="list-inline-item text-secondary">&bull;</li>
            <li class="list-inline-item"><a href="#" onclick="window.scrollTo({top:0, behavior:'smooth'}); return false;" class="text-teal text-decoration-none fw-semibold"><i class="fa fa-arrow-up me-1"></i> Ke Atas</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Hitung durasi, denda keterlambatan, dan estimasi total serta skema tagihan
  function hitungModal(id, basePrice) {
    const tglSewaInput = document.querySelector(`.input-tgl-sewa[data-id="${id}"]`);
    const tglKembaliInput = document.querySelector(`.input-tgl-kembali[data-id="${id}"]`);
    const durasiEl = document.getElementById(`modalDurasi${id}`);
    const dendaEl = document.getElementById(`modalDenda${id}`);
    const totalEl = document.getElementById(`modalTotal${id}`);
    const tagihanSekarangEl = document.getElementById(`modalTagihanSekarang${id}`);
    const labelBayarEl = document.getElementById(`modalLabelBayarSekarang${id}`);
    const subBayarEl = document.getElementById(`modalSubBayarSekarang${id}`);

    if (!tglSewaInput || !tglKembaliInput) return;

    const d1 = new Date(tglSewaInput.value);
    const d2 = new Date(tglKembaliInput.value);

    let total = basePrice;
    if (isNaN(d1) || isNaN(d2) || d2 < d1) {
      if (durasiEl) durasiEl.innerText = '-';
      if (dendaEl) dendaEl.innerText = 'Tanggal tidak valid';
      if (totalEl) totalEl.innerText = 'Rp ' + Number(basePrice).toLocaleString('id-ID');
    } else {
      const diffDays = Math.floor((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
      const lama = diffDays < 1 ? 1 : diffDays;
      if (durasiEl) durasiEl.innerText = lama;

      let denda = 0;
      if (lama > 3) {
        denda = (lama - 3) * 50000;
        if (dendaEl) {
          dendaEl.innerText = '+ Denda Rp ' + Number(denda).toLocaleString('id-ID');
          dendaEl.className = 'text-danger fw-semibold';
        }
      } else {
        if (dendaEl) {
          dendaEl.innerText = 'Tanpa Denda';
          dendaEl.className = 'text-muted';
        }
      }
      total = basePrice + denda;
      if (totalEl) totalEl.innerText = 'Rp ' + Number(total).toLocaleString('id-ID');
    }

    // Cek skema pembayaran (lunas vs dp)
    const selectedTipe = document.querySelector(`input[name="tipe_pembayaran"][data-id="${id}"]:checked`);
    const isDP = selectedTipe && selectedTipe.value === 'dp';
    const tagihanSekarang = isDP ? Math.ceil(total * 0.5) : total;

    if (tagihanSekarangEl) {
      tagihanSekarangEl.innerText = 'Rp ' + Number(tagihanSekarang).toLocaleString('id-ID');
    }

    if (labelBayarEl && subBayarEl) {
      if (isDP) {
        labelBayarEl.innerText = 'Uang Muka DP (50%):';
        const sisa = Math.max(0, total - tagihanSekarang);
        subBayarEl.innerText = 'Sisa Rp ' + Number(sisa).toLocaleString('id-ID') + ' saat ambil jas';
      } else {
        labelBayarEl.innerText = 'Bayar Penuh (100%):';
        subBayarEl.innerText = 'Lunas saat ini';
      }
    }
  }

  // Tampilkan panduan pembayaran berdasarkan metode yang dipilih
  function updateMetodeGuide(id, metode) {
    const qrisBox = document.getElementById(`guideQris${id}`);
    const tfBox = document.getElementById(`guideTransfer${id}`);
    const tokoBox = document.getElementById(`guideToko${id}`);

    if (qrisBox) qrisBox.style.display = (metode === 'qris') ? 'block' : 'none';
    if (tfBox) tfBox.style.display = (metode === 'transfer') ? 'block' : 'none';
    if (tokoBox) tokoBox.style.display = (metode === 'toko') ? 'block' : 'none';
  }

  // Event listener saat tanggal sewa/kembali diubah
  document.querySelectorAll('.input-tgl-sewa, .input-tgl-kembali').forEach(input => {
    input.addEventListener('change', function() {
      const id = this.getAttribute('data-id');
      const harga = parseFloat(this.getAttribute('data-harga') || 0);
      hitungModal(id, harga);
    });
  });

  // Event listener skema pembayaran
  document.querySelectorAll('.input-tipe-bayar').forEach(radio => {
    radio.addEventListener('change', function() {
      const id = this.getAttribute('data-id');
      const tglInput = document.querySelector(`.input-tgl-sewa[data-id="${id}"]`);
      const basePrice = parseFloat(tglInput ? tglInput.getAttribute('data-harga') : 0);
      hitungModal(id, basePrice);
    });
  });

  // Event listener metode pembayaran
  document.querySelectorAll('.input-metode-bayar').forEach(radio => {
    radio.addEventListener('change', function() {
      const id = this.getAttribute('data-id');
      updateMetodeGuide(id, this.value);
    });
  });

  // Cegah input huruf pada kolom angka
  document.querySelectorAll('.input-numeric-only').forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
      }
    });
  });

  // Preview foto identitas saat dipilih
  document.querySelectorAll('.input-foto-identitas').forEach(input => {
    input.addEventListener('change', function() {
      const previewId = this.getAttribute('data-preview');
      const previewContainer = document.getElementById(previewId);
      if (previewContainer && this.files && this.files[0]) {
        const file = this.files[0];
        if (file.size > 5 * 1024 * 1024) {
          alert('Ukuran foto terlalu besar! Maksimal 5 MB.');
          this.value = '';
          previewContainer.classList.add('d-none');
          return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = previewContainer.querySelector('.img-preview-target');
          if (img) img.src = e.target.result;
          previewContainer.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      } else if (previewContainer) {
        previewContainer.classList.add('d-none');
      }
    });
  });

  <?php if (!empty($bookingSuccess)): ?>
  // Tampilkan notifikasi pop up saat penyewaan berhasil
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal !== 'undefined') {
      const metodeTxt = "<?= htmlspecialchars(strtoupper($bookingSuccess['metode_pembayaran'] ?? 'TOKO')) ?>";
      const tipeTxt = "<?= htmlspecialchars(strtoupper($bookingSuccess['tipe_pembayaran'] ?? 'LUNAS')) ?>";
      let panduanHtml = '';
      if ("<?= $metodeSukses ?>" === 'transfer') {
        panduanHtml = '<div style="margin-top:8px; font-size:12px; color:#0f766e; background:#f0fdfa; padding:8px; border-radius:8px;"><b>Rekening Transfer:</b><br>BCA: <b>827-091-8822</b> (Rental Jas Modern)<br>Mandiri: <b>137-00-1928374-1</b></div>';
      } else if ("<?= $metodeSukses ?>" === 'qris') {
        panduanHtml = '<div style="margin-top:8px; font-size:12px; color:#0f766e; background:#f0fdfa; padding:8px; border-radius:8px;"><b>Metode QRIS:</b><br>Admin akan mengirimkan barcode QRIS ke nomor WhatsApp Anda.</div>';
      } else {
        panduanHtml = '<div style="margin-top:8px; font-size:12px; color:#0f766e; background:#f0fdfa; padding:8px; border-radius:8px;"><b>Bayar di Toko:</b><br>Bayar tunai/debit di kasir saat fitting & ambil jas.</div>';
      }

      Swal.fire({
        icon: 'success',
        title: 'Penyewaan Berhasil!',
        html: `
          <p class="text-muted mb-3" style="font-size:14px;">Terima kasih <b><?= htmlspecialchars($bookingSuccess['nama_customer']) ?></b>, pesanan sewa jas Anda telah kami terima.</p>
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px; text-align:left; font-size:13px; line-height:1.6; margin-bottom:12px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:6px; border-bottom:1px dashed #e2e8f0; padding-bottom:6px;">
              <span style="color:#64748b;">No. Order:</span>
              <b style="color:#0f172a;">#ORD-<?= str_pad((string)$bookingSuccess['order_id'], 4, '0', STR_PAD_LEFT) ?></b>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:6px; border-bottom:1px dashed #e2e8f0; padding-bottom:6px;">
              <span style="color:#64748b;">Model Jas:</span>
              <b style="color:#0f172a;"><?= htmlspecialchars($bookingSuccess['nama_jas']) ?></b>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:6px; border-bottom:1px dashed #e2e8f0; padding-bottom:6px;">
              <span style="color:#64748b;">Ukuran / Warna:</span>
              <span style="color:#0f172a;"><?= htmlspecialchars($bookingSuccess['ukuran_jas']) ?> &bull; <?= htmlspecialchars($bookingSuccess['warna_jas']) ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:6px; border-bottom:1px dashed #e2e8f0; padding-bottom:6px;">
              <span style="color:#64748b;">Skema Pembayaran:</span>
              <b style="color:#0f172a;">${tipeTxt} (${metodeTxt})</b>
            </div>
            <div style="display:flex; justify-content:space-between; padding-top:6px; border-top:1px dashed #e2e8f0;">
              <span style="color:#64748b;">Tagihan Sekarang:</span>
              <b style="color:#0d9488; font-size:16px;"><?= formatRupiah($bookingSuccess['nominal_bayar'] ?? $bookingSuccess['total_harga']) ?></b>
            </div>
            <?php if (!empty($bookingSuccess['sisa_bayar']) && $bookingSuccess['sisa_bayar'] > 0): ?>
            <div style="display:flex; justify-content:space-between; font-size:12px; color:#e11d48; margin-top:4px;">
              <span>Sisa di Toko:</span>
              <b><?= formatRupiah($bookingSuccess['sisa_bayar']) ?></b>
            </div>
            <?php endif; ?>
            ${panduanHtml}
          </div>
          <small style="color:#64748b; font-size:12px; display:block;">Admin kami akan segera menghubungi WhatsApp <b><?= htmlspecialchars($bookingSuccess['no_hp']) ?></b>.</small>
        `,
        confirmButtonText: '<i class="fa fa-check me-1"></i> Selesai',
        confirmButtonColor: '#0d9488',
        allowOutsideClick: false
      });
    } else {
      const modalEl = document.getElementById('modalSuccessBooking');
      if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
      }
    }
  });
  <?php endif; ?>
</script>
</body>
</html>

