<?php
session_start(); // PHP Oturumunu başlat (Ödev zorunluluğu)
require_once 'db.php';

$error = '';

// Eğer form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Veritabanından kullanıcıyı bul
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :user");
    $stmt->execute([':user' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kullanıcı varsa ve şifre (hash) eşleşiyorsa
    if ($admin && password_verify($password, $admin['password_hash'])) {
        // Giriş Başarılı! Session oluştur ve admin paneline yönlendir
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        
        // Çerez (Cookie) Örneği - Ödev şartı için sisteme son giriş saatini cookie'ye yazıyoruz
        setcookie('last_login', date('Y-m-d H:i:s'), time() + (86400 * 30), "/"); 
        
        header("Location: admin.php");
        exit;
    } else {
        $error = "HATA: Geçersiz Codename veya Şifre!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sistem Girişi - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #0a0a0a;">
    
    <div style="background: #111; padding: 40px; border: 2px solid var(--accent-yellow); border-radius: 10px; width: 400px; text-align: center; box-shadow: 0 0 20px rgba(255,227,104,0.2);">
        <h2 style="color: var(--accent-yellow); margin-bottom: 30px;">> ADMIN_LOGIN</h2>
        
        <?php if($error): ?>
            <p style="color: #ff3366; margin-bottom: 20px; font-weight: bold;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" id="contact-form">
            <input type="text" name="username" placeholder="Codename (mustafa)" required style="text-align: center;">
            <input type="password" name="password" placeholder="Password (123456)" required style="text-align: center;">
            <button type="submit" class="submit-btn" style="width: 100%; margin-top: 10px;">Sisteme Gir</button>
        </form>
        <br>
        <a href="index.html" style="color: var(--text-gray); font-size: 0.9rem; text-decoration: none;">&lt; Portfolyoya Dön</a>
    </div>

</body>
</html>