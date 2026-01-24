<?php
/**
 * Login Page
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
    <title>Login | Inventaris Jenab</title>
    
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
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 50%, #3a7ca5 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body.login-page::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s linear infinite;
            z-index: 1;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15), 0 0 40px rgba(30, 58, 95, 0.3);
            position: relative;
            z-index: 10;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2), 0 0 60px rgba(30, 58, 95, 0.4);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(30, 58, 95, 0.3);
            animation: bounceIn 0.6s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { opacity: 1; }
            70% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .login-header .logo svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        .login-card .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .login-card .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .login-card .form-control:focus {
            border-color: #2d5a8c;
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
            outline: none;
        }

        .login-card .form-control::placeholder {
            color: #94a3b8;
        }

        .login-card .btn-primary {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 58, 95, 0.3);
        }

        .login-card .btn-primary:hover {
            background: linear-gradient(135deg, #2d5a8c 0%, #1e3a5f 100%);
            box-shadow: 0 6px 20px rgba(30, 58, 95, 0.4);
            transform: translateY(-2px);
        }

        .login-card .btn-primary:active {
            transform: translateY(0);
        }

        .login-card .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid #ef4444;
            background: #fee2e2;
            color: #991b1b;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .demo-box {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-radius: 8px;
            padding: 14px;
            margin-top: 24px;
            border-left: 4px solid #22c55e;
        }

        .demo-box p {
            margin: 0;
            font-size: 13px;
            color: #15803d;
            font-weight: 500;
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
            <h1>Inventaris Jenab</h1>
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
            <p><strong>Demo:</strong> jenab / jenab123</p>
        </div>
    </div>
</body>
</html>
