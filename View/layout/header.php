<?php
// Layout Header
$pageTitle = $pageTitle ?? 'Sewa Jas App';
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Sewa Jas Admin</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- CSS Utama -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navbar Panel Admin (Top Navigation) -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php?action=dashboard" class="nav-brand">
                <div class="brand-icon">
                    <i class="fa fa-vest"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-name">Rental Jas</span>
                    <span class="brand-badge">ADMIN</span>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="index.php?action=dashboard" class="nav-link <?= ($activeNav === 'dashboard') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="index.php?action=jas" class="nav-link <?= ($activeNav === 'jas') ? 'active' : '' ?>"><i class="bi bi-box-seam"></i> Katalog Jas</a></li>
                <li><a href="index.php?action=order" class="nav-link <?= ($activeNav === 'order') ? 'active' : '' ?>"><i class="bi bi-receipt"></i> Transaksi Order</a></li>
                <li><a href="index.php?action=pengembalian" class="nav-link <?= ($activeNav === 'pengembalian') ? 'active' : '' ?>"><i class="bi bi-arrow-return-left"></i> Pengembalian</a></li>
                <li><a href="index.php?action=customer" class="nav-link <?= ($activeNav === 'customer') ? 'active' : '' ?>"><i class="bi bi-people"></i> Customer</a></li>
                <li><a href="index.php?action=admin" class="nav-link <?= ($activeNav === 'admin') ? 'active' : '' ?>"><i class="bi bi-shield-lock"></i> Admin</a></li>
            </ul>

            <!-- Menu akun: Web Katalog + Logout dijadikan satu dropdown -->
            <div class="user-menu" id="userMenu">
                <button type="button" class="user-menu-toggle" id="userMenuToggle" aria-haspopup="true" aria-expanded="false" aria-controls="userMenuPanel">
                    <span class="user-avatar"><i class="bi bi-person-fill"></i></span>
                    <span class="user-name"><?= htmlspecialchars($_SESSION['admin_user']['username'] ?? 'Admin') ?></span>
                    <i class="bi bi-chevron-down user-caret"></i>
                </button>
                <div class="user-menu-panel" id="userMenuPanel" role="menu" hidden>
                    <div class="user-menu-head">
                        <span class="user-menu-hint">Masuk sebagai</span>
                        <strong><?= htmlspecialchars($_SESSION['admin_user']['username'] ?? 'Admin') ?></strong>
                    </div>
                    <a href="katalog.php" class="user-menu-item" role="menuitem" target="_blank" rel="noopener">
                        <i class="bi bi-shop"></i> Lihat Web Katalog
                        <i class="bi bi-box-arrow-up-right user-menu-ext"></i>
                    </a>
                    <div class="user-menu-divider"></div>
                    <a href="index.php?action=logout" class="user-menu-item user-menu-danger" role="menuitem" onclick="return confirm('Apakah Anda yakin ingin logout dari panel admin?');">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="main-content">
