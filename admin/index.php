<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/database.php';

// Sinh CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Xử lý thêm người nhận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) { die("Lỗi CSRF Token"); }

    $recipient = trim($_POST['recipient_name']);
    $sender = trim($_POST['sender_name']);
    $message = trim($_POST['message']);
    
    // Xử lý file ảnh
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
    
    // Kiểm tra nếu có file được tải lên
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['photo']['tmp_name']);
        finfo_close($fileInfo);
        
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (in_array($mimeType, $allowedMimeTypes) && in_array($ext, $allowedExts) && $_FILES['photo']['size'] <= 5000000) {
            $uniqueId = uniqid('gift_');
            $fileName = $uniqueId . '.' . $ext;
            
            // Tạo thư mục uploads nếu chưa có
            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            $uploadPath = '../uploads/' . $fileName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $stmt = $pdo->prepare("INSERT INTO recipients (id, recipient_name, sender_name, photo, message) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$uniqueId, $recipient, $sender, 'uploads/'.$fileName, $message]);
                $success = "Tạo món quà thành công!";
            } else { 
                $error = "Lỗi khi lưu file ảnh vào thư mục uploads."; 
            }
        } else { 
            $error = "Định dạng ảnh không hợp lệ (chỉ JPG, PNG, WebP) hoặc vượt quá 5MB."; 
        }
    } else {
        $error = "Vui lòng chọn một file ảnh hợp lệ.";
    }
}

// Xử lý xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $id = $_POST['id'];
        
        $stmt = $pdo->prepare("SELECT photo FROM recipients WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && file_exists('../' . $row['photo'])) { 
            unlink('../' . $row['photo']); 
        }
        
        $pdo->prepare("DELETE FROM recipients WHERE id = ?")->execute([$id]);
        $success = "Đã xóa thành công!";
    }
}

// Lấy danh sách
$recipients = $pdo->query("SELECT * FROM recipients ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị Lời chúc</title>
    <style>
        body { font-family: 'Quicksand', Arial, sans-serif; background: #fff0f5; margin: 0; padding: 20px; color: #5a4b5c; }
        .wrapper { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(255,182,193,0.3); border: 2px dashed #ffb6c1; }
        h2, h3 { color: #d65a72; }
        input, textarea, button { width: 100%; padding: 12px; margin-bottom: 15px; box-sizing: border-box; border: 1px solid #ffb6c1; border-radius: 8px; font-family: inherit; outline: none;}
        input:focus, textarea:focus { border-color: #d65a72; }
        button { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border: none; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 5px 15px rgba(255, 154, 158, 0.4); }
        button:hover { transform: scale(1.02); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #fecfef; padding: 12px; text-align: center; }
        th { background: #ffb3c6; color: white; }
        .qr-img { width: 100px; height: 100px; border-radius: 10px; border: 1px solid #eee; }
        .action-btn { background: #6c5ce7; width: auto; padding: 8px 12px; font-size: 0.9em; margin: 2px; box-shadow: none; border-radius: 5px; }
        .action-btn:hover { transform: translateY(-2px); }
        .delete-btn { background: #ff7675; }
        .preview-btn { background: #00b894; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>🌸 Quản trị Lời Chúc 20/10 🌸</h2>
        <?php if(isset($success)) echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" name="recipient_name" placeholder="Tên người nhận (VD: Lan Anh)" required>
            <input type="text" name="sender_name" placeholder="Tên người gửi (VD: Minh Tuấn)" required>
            <textarea name="message" rows="4" required>Chúc bạn có một ngày 20/10 thật vui vẻ và ngập tràn hạnh phúc! Mong bạn luôn xinh đẹp, tự tin, gặp nhiều may mắn và được yêu thương thật nhiều. Chúc mọi điều dịu dàng nhất sẽ đến với bạn, hôm nay và cả những ngày sau này. 💗</textarea>
            <div style="margin-bottom: 10px; font-weight: bold; color: #d65a72;">Chọn ảnh (JPG, PNG, WebP - Tối đa 5MB):</div>
            <input type="file" name="photo" accept=".jpg, .jpeg, .png, .webp" required>
            <button type="submit">Tạo Lời Chúc & Mã QR ✨</button>
        </form>

        <hr style="border-top: 2px dashed #ffb6c1; margin: 30px 0;">
        <h3>🎁 Danh sách đã tạo</h3>
        <div style="overflow-x: auto;">
            <table>
                <tr>
                    <th>Người nhận</th>
                    <th>Ảnh</th>
                    <th>Mã QR</th>
                    <th>Thao tác</th>
                </tr>
                <?php foreach($recipients as $row): 
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'], 2) . '/gift.php?id=' . $row['id'];
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($baseUrl);
                ?>
                <tr>
                    <td style="font-weight: bold; color: #e87a90;"><?= htmlspecialchars($row['recipient_name']) ?></td>
                    <td><img src="../<?= htmlspecialchars($row['photo']) ?>" style="width:60px; height:60px; object-fit:cover; border-radius:10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"></td>
                    <td><img src="<?= $qrUrl ?>" class="qr-img" alt="QR Code"></td>
                    <td>
                        <button class="action-btn" onclick="copyLink('<?= $baseUrl ?>')">📋 Copy Link</button>
                        <a href="<?= $baseUrl ?>" target="_blank" style="text-decoration: none;"><button type="button" class="action-btn preview-btn">👁️ Xem trước</button></a>
                        
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa lời chúc này chứ?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="action-btn delete-btn">🗑️ Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($recipients)): ?>
                <tr>
                    <td colspan="4" style="color: #999;">Chưa có lời chúc nào được tạo.</td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <script>
    function copyLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Đã sao chép đường dẫn: ' + url);
        });
    }
    </script>
</body>
</html>