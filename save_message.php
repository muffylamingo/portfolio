<?php
// Gelen isteğin ve dönecek cevabın JSON formatında olacağını belirtiyoruz
header('Content-Type: application/json; charset=utf-8');

// Veritabanı bağlantımızı dahil ediyoruz
require_once 'db.php';

// JavaScript Fetch API'den gelen ham JSON verisini okuyoruz
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Verilerin boş gelip gelmediğini kontrol ediyoruz
if (isset($data['name']) && isset($data['email']) && isset($data['message'])) {
    
    // Güvenlik: Boşlukları temizle ve HTML etiketlerini zararsız hale getir (XSS Koruması)
    $name = htmlspecialchars(trim($data['name']));
    $email = htmlspecialchars(trim($data['email']));
    $message = htmlspecialchars(trim($data['message']));

    // Sunucu Tarafı (Backend) Validasyonu: E-posta formatı doğru mu?
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz e-posta formatı.']);
        exit;
    }

    try {
        // SQL Enjeksiyonuna karşı korumalı PDO hazırlığı (Ödevde ekstra puan getirir)
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)");
        
        // Sorguyu çalıştır ve verileri eşleştir
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message
        ]);

        // Başarılı olursa frontend'e yeşil ışık yak
        echo json_encode(['status' => 'success', 'message' => 'Sistem günlüğü başarıyla veritabanına kaydedildi.']);

    } catch(PDOException $e) {
        // Veritabanı hatası olursa Frontend'e bildir
        echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }

} else {
    // Eksik veri geldiyse hata döndür
    echo json_encode(['status' => 'error', 'message' => 'Eksik veri gönderimi engellendi.']);
}
?>