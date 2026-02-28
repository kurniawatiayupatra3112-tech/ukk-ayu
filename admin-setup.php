<?php
/**
 * Setup Admin Account
 * ⚠️ PENTING: HAPUS FILE INI SETELAH SETUP SELESAI!
 */

// 🔒 Proteksi: Hanya bisa diakses dari localhost
if (php_sapi_name() !== 'cli') {
    if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
        die('<h1>🚫 Akses Ditolak!</h1><p>Script ini hanya bisa dijalankan dari localhost.</p>');
    }
}

// Konfigurasi database
$host = 'localhost';
$dbname = 'inventaris_ayupatra';
$username = 'root';
$password = '';

// Data admin baru
$new_username = 'ayu';
$new_password = 'ayu123';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // ✅ Cek apakah tabel users ada
    $checkTable = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
    if (!$checkTable) {
        throw new Exception("Tabel 'users' tidak ditemukan! Pastikan database sudah di-import.");
    }
    
    // ✅ Cek apakah kolom 'role' ada, jika tidak buat query tanpa role
    $columns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
    $hasRole = in_array('role', $columns);
    
    echo "<!DOCTYPE html><html><head><style>
        body { font-family: sans-serif; padding: 20px; background: #f1f5f9; }
        .card { background: white; padding: 25px; border-radius: 12px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .success { background: #22c55e; color: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .warning { background: #fef3c7; color: #92400e; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #f59e0b; }
        .error { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin: 15px 0; }
        code { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-size: 0.9em; }
        a { color: white; font-weight: bold; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style></head><body><div class='card'>";
    
    echo "<h2>🔧 Setup Admin Account</h2>";
    
    // Generate password hash
    // ⚠️ Warning Intelephense "Undefined constant PASSWORD_BCRYPT" bisa diabaikan
    // Fungsi ini berjalan normal di PHP 8.1
    $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
    
    echo "<p><strong>Username:</strong> $new_username</p>";
    echo "<p><strong>Password:</strong> $new_password</p>";
    echo "<p><strong>Hash:</strong><br><code>" . htmlspecialchars($password_hash) . "</code></p>";
    
    // ✅ Delete existing user (menggunakan prepared statement)
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
    $stmt->execute([$new_username]);
    echo "<p>🗑️ Data lama (jika ada) telah dihapus.</p>";
    
    // ✅ Insert new user (dinamis sesuai kolom yang ada)
    if ($hasRole) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
        $result = $stmt->execute([$new_username, $password_hash]);
    } else {
        // Fallback jika kolom 'role' belum ada di tabel
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $result = $stmt->execute([$new_username, $password_hash]);
        echo "<p style='color: #f59e0b;'>⚠️ Kolom 'role' tidak ditemukan, user dibuat tanpa role.</p>";
    }
    
    if ($result) {
        echo "<div class='success'>";
        echo "<h3>✅ User Created Successfully!</h3>";
        echo "<p>Username: <strong>$new_username</strong></p>";
        echo "<p>Password: <strong>$new_password</strong></p>";
        // ✅ FIX: Path login disesuaikan dengan struktur folder
        echo "<p><a href='app/auth/login.php'>➡️ Go to Login Page</a></p>";
        echo "</div>";
    }
    
    // ✅ FIX: Test verifikasi dengan username yang BENAR ('ayu')
    echo "<h3>🧪 Verification Test:</h3>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$new_username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $verify = password_verify($new_password, $user['password']);
        echo "<p>Password verify result: <strong>" . ($verify ? "✅ SUCCESS" : "❌ FAILED") . "</strong></p>";
        if (!$verify) {
            echo "<p style='color: red;'>Cek kembali hashing password atau encoding database!</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ User '$new_username' tidak ditemukan di database!</p>";
    }
    
    echo "<div class='warning'>";
    echo "<strong>⚠️ PENTING:</strong> Segera hapus file <code>admin-setup.php</code> setelah setup selesai!<br>";
    echo "File ini berbahaya jika dibiarkan di server produksi.";
    echo "</div>";
    
    echo "</div></body></html>";
    
} catch (PDOException $e) {
    echo "<div style='background: #fee2e2; color: #991b1b; padding: 20px; border-radius: 10px; margin: 20px auto; max-width: 600px;'>";
    echo "<h3>❌ Database Error</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Tip:</strong> Pastikan database <code>inventaris_ayupatra</code> sudah dibuat dan user <code>root</code> memiliki akses.</p>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div style='background: #fee2e2; color: #991b1b; padding: 20px; border-radius: 10px; margin: 20px auto; max-width: 600px;'>";
    echo "<h3>❌ Error</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>