<?php
$host = '127.0.0.1';
$dbname = 'qua_tang_2010';
$username = 'root';
$password = ''; // Mặc định XAMPP/Laragon thường rỗng

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>