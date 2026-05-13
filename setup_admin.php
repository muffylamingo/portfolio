<?php
require_once 'db.php';

$username = 'mustafa';
// Güvenlik için şifreyi (123456) direkt veritabanına yazmak yerine, modern bir şekilde şifreliyoruz. (Hocadan ekstra puan!)
$password = '123456'; 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (:user, :pass)");
    $stmt->execute([':user' => $username, ':pass' => $hashed_password]);
    echo "<h2 style='color:green; font-family:sans-serif;'>Admin kullanıcısı ('mustafa', şifre: '123456') başarıyla oluşturuldu! Güvenlik için bu dosyayı silebilirsin.</h2>";
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>