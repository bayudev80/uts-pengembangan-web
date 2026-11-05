<?php
$pageTitle = 'Tentang Saya';
$activeNav = 'saya';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Tentang Saya</h1>
        <p class="page-subtitle">Profil pengembang aplikasi Sistem Persewaan Jas</p>
    </div>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <strong>Profil Pribadi</strong>
        <span class="badge" style="background:#ccfbf1; color:#0f766e;"><i class="bi bi-code-slash"></i> Developer</span>
    </div>
    <div class="card-body">
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div class="avatar" style="width: 72px; height: 72px; font-size: 2rem; background: var(--primary); color: #fff;">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <h2 style="font-size: 1.3rem; color: var(--text-heading); font-weight: 700;">Halo, Selamat Datang!</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Mahasiswa / Web Developer &bull; Pengembangan Web 2</p>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-section-title"><i class="bi bi-journal-text"></i> Deskripsi & Catatan Proyek</div>
            <p style="color: var(--text-body); font-size: 0.925rem; line-height: 1.7; margin-bottom: 1rem;">
                Ini adalah halaman profil tentang saya. Halaman ini dibangun sebagai bagian dari proyek <strong>Pengembangan Web 2</strong>, 
                yang mengimplementasikan konsep arsitektur <strong>MVC (Model - View - Controller)</strong> dengan bahasa pemrograman PHP dan basis data MySQL.
            </p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <span class="badge" style="background:#f1f5f9; color:#334155;"><i class="bi bi-cpu"></i> PHP MVC</span>
                <span class="badge" style="background:#f1f5f9; color:#334155;"><i class="bi bi-database"></i> MySQL / PDO</span>
                <span class="badge" style="background:#f1f5f9; color:#334155;"><i class="bi bi-server"></i> Laragon</span>
                <span class="badge" style="background:#f1f5f9; color:#334155;"><i class="bi bi-palette"></i> Modern Clean UI</span>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
