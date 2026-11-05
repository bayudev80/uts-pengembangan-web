<?php
$pageTitle = 'Login Panel Admin - Rental Jas';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0d9488 100%);
            padding: 24px;
            color: #1e293b;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.15);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            color: white;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 14px;
            box-shadow: 0 8px 18px rgba(13, 148, 136, 0.35);
        }

        .brand-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            font-size: 0.85rem;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.925rem;
            color: #0f172a;
            background: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.05rem;
            padding: 4px;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: #0d9488;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            color: white;
            border: none;
            padding: 13px 18px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.4);
            transform: translateY(-1px);
        }

        .alert-box {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .footer-note {
            margin-top: 24px;
            text-align: center;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
        }

        .footer-link {
            color: #0d9488;
            font-size: 0.825rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: #0f766e;
            text-decoration: underline;
        }

        .hint-badge {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            color: #0f766e;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.775rem;
            margin-top: 16px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fa fa-vest"></i>
            </div>
            <h1 class="brand-title">Rental Jas</h1>
            <p class="brand-subtitle">Silakan login untuk mengakses Panel Admin</p>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert-box <?= ($flash['type'] === 'success') ? 'alert-success' : 'alert-error' ?>">
                <i class="bi bi-<?= ($flash['type'] === 'success') ? 'check-circle-fill' : 'exclamation-circle-fill' ?>"></i>
                <div><?= htmlspecialchars($flash['message']) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login_proses" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Username Admin</label>
                <div class="input-wrapper">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" id="username" name="username" class="form-input" 
                           placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Masukkan password" required>
                    <button type="button" class="toggle-password" id="togglePassword" title="Lihat/Sembunyikan password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
            </button>
        </form>

        <div class="footer-note">
            <a href="katalog.php" class="footer-link">
                <i class="bi bi-arrow-left"></i> Kembali ke Katalog Customer
            </a>
        </div>
    </div>

    <script>
        // Toggle lihat / sembunyikan password
        const toggleBtn = document.getElementById('togglePassword');
        const passInput = document.getElementById('password');

        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', function() {
                const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                }
            });
        }
    </script>
</body>
</html>

