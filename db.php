<?php
// Veritabanı ayarları
$host = 'localhost';
$dbname = 'portfolio_db';
$username = 'root'; // XAMPP'ın varsayılan MySQL kullanıcısı
$password = '';     // XAMPP'ta varsayılan şifre boştur

try {
    // PDO ile bağlantıyı kurma
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Hata modunu aktifleştirme (Hata ayıklamayı kolaylaştırır)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test etmek için aşağıdaki satırın başındaki // işaretlerini kaldırabilirsin:
    // echo "Bağlantı başarılı! Domain Expansion aktif."; 
    
} catch(PDOException $e) {
    // Bağlantı başarısız olursa çalışmayı durdur ve hatayı yazdır
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>