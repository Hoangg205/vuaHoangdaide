<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'sql12.freesqldatabase.com'; 
$db   = 'sql12839217';
$user = 'sql12839217';
$pass = 'SGS7FZHj76';
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối CSDL thành công!"; // Bỏ comment dòng này nếu muốn test nhanh
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>