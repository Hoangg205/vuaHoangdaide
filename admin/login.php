<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loại bỏ khoảng trắng thừa nếu có
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :user LIMIT 1");
    $stmt->execute(['user' => $username]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Trường hợp 1: Mật khẩu đã được mã hóa chuẩn Bcrypt
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: index.php");
            exit;
        } 
        // Trường hợp 2: Mật khẩu đang lưu ở dạng chữ thường (như "hoang1")
        elseif ($password === $admin['password']) {
            // Tự động mã hóa lại mật khẩu chuẩn và lưu đè vào CSDL
            $new_hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $updateStmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
            $updateStmt->execute([$new_hashed_password, $admin['id']]);
            
            // Cho phép đăng nhập thành công
            $_SESSION['admin_logged_in'] = true;
            header("Location: index.php");
            exit;
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị</title>
    <style>
        body { font-family: 'Quicksand', sans-serif; background: #fff0f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(255,182,193,0.5); text-align: center; width: 100%; max-width: 350px; border: 2px dashed #ffb6c1; }
        .login-box input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ffb6c1; border-radius: 10px; box-sizing: border-box; font-size: 16px; outline: none; }
        .login-box input:focus { border-color: #d65a72; }
        .btn-cute { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border: none; padding: 12px; width: 100%; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 5px 15px rgba(255, 154, 158, 0.4); }
        .btn-cute:hover { transform: scale(1.05); }
        .error-msg { color: red; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="color: #d65a72; margin-top: 0; margin-bottom: 20px;">Đăng nhập 🌸</h2>
        <?php if(!empty($error)) echo "<div class='error-msg'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required autocomplete="off">
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" class="btn-cute">Đăng nhập ngay</button>
        </form>
    </div>
</body>
</html>