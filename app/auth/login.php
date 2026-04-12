<?php
/**
 * Login Page - Soft Blue Background
 * Clean design with Heroicons
 */

session_start();
date_default_timezone_set('Asia/Jakarta');

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

if (!function_exists('password_verify')) {
    function password_verify($password, $hash)
    {
        return crypt($password, $hash) === $hash;
    }
}

$error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['login_time'] = time();
            
            header('Location: ../dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventaris Gudang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body.login-page {
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        
        /* 💙 SOFT BLUE BACKGROUND - PILIH SALAH SATU 💙 */
        background: #eff6ff; /* Opsi 1: Soft Sky Blue (Rekomendasi) */
        /* background: #f0f9ff; /* Opsi 2: Ultra Light Blue */
        /* background: #dbeafe; /* Opsi 3: Soft Blue */
        /* background: #e0f2fe; /* Opsi 4: Soft Cyan Blue */
        /* background: #f5f9ff; /* Opsi 5: Very Light Blue */
        /* background: #f0f4ff; /* Opsi 6: Soft Periwinkle */
        
        position: relative;
    }

    /* Login Card - White Theme */
    .login-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 40px;
        width: 100%;
        max-width: 420px;
        box-shadow: 
            0 8px 40px rgba(59, 130, 246, 0.10),
            0 2px 12px rgba(0, 0, 0, 0.03);
        position: relative;
        z-index: 10;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
        transform: translateY(-4px);
        box-shadow: 
            0 16px 50px rgba(59, 130, 246, 0.15),
            0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Subtle border glow - blue theme */
    .login-card::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: 25px;
        padding: 1px;
        background: linear-gradient(135deg, 
            rgba(96, 165, 250, 0.35), 
            rgba(59, 130, 246, 0.25), 
            transparent, 
            rgba(147, 197, 253, 0.25));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: 0.5;
    }

    .login-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-header .logo {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.20);
        animation: bounceIn 0.6s ease;
    }

    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { opacity: 1; }
        70% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .login-header .logo svg {
        width: 30px;
        height: 30px;
        color: white;
    }

    .login-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1e3a5f;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .login-header p {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .login-card .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        font-size: 14px;
        display: block;
    }

    .login-card .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #1e293b;
    }

    .login-card .form-control:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.15);
        outline: none;
        background: #ffffff;
    }

    .login-card .form-control::placeholder {
        color: #94a3b8;
    }

    .login-card .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .login-card .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        transition: left 0.5s ease;
    }

    .login-card .btn-primary:hover::before {
        left: 100%;
    }

    .login-card .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.35);
        transform: translateY(-2px);
    }

    .login-card .btn-primary:active {
        transform: translateY(0);
    }

    .login-card .alert {
        border: none;
        border-radius: 12px;
        border-left: 4px solid #ef4444;
        background: rgba(254, 226, 226, 0.95);
        color: #991b1b;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .login-card .alert::before {
        content: '⚠️';
        font-size: 16px;
        flex-shrink: 0;
    }

    .demo-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 24px;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #3b82f6;
    }

    .demo-box p {
        margin: 0;
        font-size: 13px;
        color: #475569;
        font-weight: 500;
    }

    .demo-box strong {
        color: #2563eb;
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
        .login-card {
            padding: 32px 28px;
            margin: 16px;
        }
        .login-header h1 { font-size: 24px; }
    }
    </style>
</head>
<body class="login-page">
    
    <div class="login-card">
        <div class="login-header">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
            <h1>Inventaris Ayu</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" 
                       placeholder="Masukkan username" 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                       required autofocus>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" 
                       placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>
        </form>
        
        <div class="demo-box">
            <p><strong>Demo:</strong> ayu / ayu123</p>
        </div>
    </div>
</body>
</html>