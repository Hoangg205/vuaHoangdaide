<?php
$host = 'sql12.freesqldatabase.com'; // Điền Host của bạn vào đây
$db   = 'sql12839239';
$user = 'sql12839239';
$pass = 'TzICsPSpFJ';
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>