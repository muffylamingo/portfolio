<?php
session_start(); // Oturumu başlat
require_once 'db.php';

// --- GÜVENLİK DUVARI (SESSION KONTROLÜ) ---
// Eğer admin girişi yapılmadıysa, zorla login sayfasına yönlendir
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// --- SİSTEMDEN ÇIKIŞ YAPMA (LOGOUT) ---
if (isset($_GET['logout'])) {
    session_destroy(); // Oturumu yok et
    header("Location: login.php");
    exit;
}

// --- YENİ PROJE EKLEME İŞLEMİ (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_project'])) {
    $title = htmlspecialchars($_POST['title']);
    $desc = htmlspecialchars($_POST['description']);
    
    // Veritabanına yeni proje ekle
    $stmt = $pdo->prepare("INSERT INTO projects (title, description) VALUES (:title, :desc)");
    $stmt->execute([':title' => $title, ':desc' => $desc]);
    $success_msg = "Sistem Güncellendi: Yeni görev başarıyla arşive eklendi!";
}

// --- GELEN MESAJLARI VERİTABANINDAN ÇEKME ---
$msg_stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY date_sent DESC");
$messages = $msg_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kontrol Odası - Domain Expansion</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: var(--dark-bg); padding: 40px; }
        .admin-container { max-width: 1200px; margin: 0 auto; }
        .panel { background: #111; padding: 30px; border-radius: 10px; border: 1px dashed #444; }
        .message-card { background: #1a1a1a; padding: 15px; margin-bottom: 15px; border-left: 3px solid var(--accent-yellow); border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--accent-yellow); padding-bottom: 20px; margin-bottom: 40px;">
            <div>
                <h1 style="color: var(--accent-yellow);">> KONTROL_ODASI</h1>
                <p style="color: var(--text-gray);">Hoş geldin, Yetki Seviyesi: Admin</p>
            </div>
            <a href="admin.php?logout=1" style="color: #ff3366; text-decoration: none; font-weight: bold; padding: 10px 20px; border: 1px solid #ff3366; border-radius: 5px;">[ Bağlantıyı Kes ]</a>
        </div>

        <?php if(isset($success_msg)) echo "<p style='color: #00ffff; font-weight: bold; margin-bottom: 20px;'>$success_msg</p>"; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            
            <div class="panel">
                <h2 style="color: var(--accent-yellow); margin-bottom: 20px;">> YENİ_GÖREV_EKLE</h2>
                <form method="POST" id="contact-form">
                    <input type="text" name="title" placeholder="Projenin Adı" required>
                    <textarea name="description" rows="5" placeholder="Proje Detayları, kullanılan teknolojiler (Örn: C#, React...)" required></textarea>
                    <button type="submit" name="add_project" class="submit-btn">Arşive Yükle</button>
                </form>
            </div>

            <div class="panel" style="max-height: 600px; overflow-y: auto;">
                <h2 style="color: var(--accent-yellow); margin-bottom: 20px;">> İLETİŞİM_AĞI (Gelen Mesajlar)</h2>
                
                <?php if(count($messages) > 0): ?>
                    <?php foreach($messages as $msg): ?>
                        <div class="message-card">
                            <strong style="color: var(--text-light); font-size: 1.1rem;"><?php echo htmlspecialchars($msg['name']); ?></strong> 
                            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color: var(--accent-yellow); font-size: 0.9rem; text-decoration: none; margin-left: 10px;">[ E-Posta Gönder ]</a>
                            <p style="color: #ccc; margin-top: 10px; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                            <small style="color: #555; display: block; margin-top: 10px;">> İletişim Zamanı: <?php echo $msg['date_sent']; ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #888;">Henüz gelen bir veri aktarımı yok.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>