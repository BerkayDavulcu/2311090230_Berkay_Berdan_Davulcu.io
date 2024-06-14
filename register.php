<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isim = trim($_POST["isim"]);
    $soyisim = trim($_POST["soyisim"]);
    $email = trim($_POST["email"]);
    $sifre = trim($_POST["sifre"]);
    $dogumTarihi = trim($_POST["dogumTarihi"]);
    $cinsiyet = trim($_POST["cinsiyet"]);

    if (empty($isim) || empty($soyisim) || empty($email) || empty($sifre) || empty($dogumTarihi) || empty($cinsiyet)) {
        die("Tüm alanlar doldurulmalıdır.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Geçersiz e-posta formatı.");
    }

    if (strlen($sifre) < 6) {
        die("Şifre en az 6 karakter olmalıdır.");
    }

    
    $dsn = 'mysql:host=localhost;dbname=finalödevi';
    $username = 'kullanici_adi';
    $password = 'sifre';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM kullanicilar WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            die("Bu e-posta adresi zaten kayıtlı.");
        }

        
        $stmt = $pdo->prepare("INSERT INTO kullanicilar (isim, soyisim, email, sifre, dogum_tarihi, cinsiyet) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$isim, $soyisim, $email, password_hash($sifre, PASSWORD_DEFAULT), $dogumTarihi, $cinsiyet]);

        echo "Kayıt başarılı!";
    } catch (PDOException $e) {
        die("Veritabanı hatası: " . $e->getMessage());
    }
}
?>
