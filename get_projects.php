<?php
// Sayfanın bir HTML değil, JSON çıktısı vereceğini tarayıcıya bildiriyoruz
header('Content-Type: application/json; charset=utf-8');

// Veritabanı bağlantımızı (db.php) bu dosyaya dahil ediyoruz
require_once 'db.php';

try {
    // Projeleri eklenme tarihine göre yeniden eskiye doğru çekecek SQL sorgumuz
    $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC");
    
    // Sorguyu çalıştırıyoruz
    $stmt->execute();

    // Verilerin hepsini ilişkisel dizi (Key-Value) şeklinde alıyoruz
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Diziyi JSON formatına dönüştürüp ekrana basıyoruz
    echo json_encode($projects);

} catch(PDOException $e) {
    // Eğer bir hata olursa, bunu da JSON formatında döndürüyoruz ki Frontend çökmesin
    echo json_encode(['error' => 'Projeler çekilirken bir hata oluştu: ' . $e->getMessage()]);
}
?>