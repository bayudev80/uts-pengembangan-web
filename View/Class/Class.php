<?php
$pageTitle = 'Informasi Kelas';
$activeNav = 'class';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Halaman Kelas</h1>
        <p class="page-subtitle">Informasi modul dan pembelajaran Pengembangan Web 2</p>
    </div>
</div>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <strong>Modul Pengembangan Web 2</strong>
        <span class="badge" style="background:#ecfdf5; color:#065f46;"><i class="bi bi-check2-circle"></i> Aktif</span>
    </div>
    <div class="card-body">
        <div style="margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; color: var(--text-heading); font-weight: 700; margin-bottom: 0.5rem;">
                <i class="bi bi-mortarboard-fill" style="color: var(--primary);"></i> Selamat Datang di Website Pembelajaran Kelas
            </h2>
            <p style="color: var(--text-muted); font-size: 0.925rem; line-height: 1.7;">
                Pada proyek ini, kita mempraktikkan pembuatan sistem CRUD modular berbasis PHP OOP (Object-Oriented Programming) dan pola desain MVC (Model-View-Controller).
            </p>
        </div>

        <div class="detail-grid">
            <div class="detail-section">
                <div class="detail-section-title"><i class="bi bi-layers-half"></i> Modul CRUD Aktif</div>
                <ul style="padding-left: 1.25rem; font-size: 0.9rem; color: var(--text-body); line-height: 1.8;">
                    <li><strong>Customer:</strong> Kelola data penyewa</li>
                    <li><strong>Katalog Jas:</strong> Koleksi jas, harga, stok</li>
                    <li><strong>Transaksi Order:</strong> Pencatatan masa sewa & total biaya</li>
                    <li><strong>Admin:</strong> Manajemen hak akses pengelola</li>
                </ul>
            </div>

            <div class="detail-section">
                <div class="detail-section-title"><i class="bi bi-gear-wide-connected"></i> Standar Pengembangan</div>
                <ul style="padding-left: 1.25rem; font-size: 0.9rem; color: var(--text-body); line-height: 1.8;">
                    <li><strong>Arsitektur:</strong> Single-Entry Point Routing</li>
                    <li><strong>Database:</strong> PDO Prepared Statements (Aman SQLi)</li>
                    <li><strong>Session:</strong> Flash Messages Feedback</li>
                    <li><strong>Antarmuka:</strong> Bootstrap Icons & Modern CSS</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
