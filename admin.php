<?php
require_once 'config.php';

if ($_POST['action'] ?? '' === 'login') {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
}

if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (isAdmin()) {
    if ($_POST['action'] ?? '' === 'add_product') {
        $sql = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['name'], $_POST['price'], $_POST['description'], $_POST['image']]);
        $success = 'เพิ่มสินค้าเรียบร้อย';
    }
    
    if ($_GET['action'] ?? '' === 'delete' && $_GET['id']) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $success = 'ลบสินค้าเรียบร้อย';
    }
    
    $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (!isAdmin()): ?>
        <div class="login-container">
            <h2>เข้าสู่ระบบ Admin</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <button type="submit">เข้าสู่ระบบ</button>
            </form>
            <a href="index.php">← กลับหน้าหลัก</a>
        </div>
    <?php else: ?>
        <header class="admin-header">
            <h1>🔧 Admin Panel</h1>
            <div>
                <a href="index.php">ดูหน้าเว็บ</a>
                <a href="?action=logout">ออกจากระบบ</a>
            </div>
        </header>

        <main class="admin-main">
            <?php if (isset($success)): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <section class="add-product">
                <h2>เพิ่มสินค้าใหม่</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add_product">
                    <input type="text" name="name" placeholder="ชื่อสินค้า" required>
                    <input type="number" name="price" step="0.01" placeholder="ราคา" required>
                    <input type="url" name="image" placeholder="URL รูปภาพ">
                    <textarea name="description" placeholder="รายละเอียดสินค้า"></textarea>
                    <button type="submit">เพิ่มสินค้า</button>
                </form>
            </section>

            <section class="products-list">
                <h2>จัดการสินค้า (<?= count($products) ?> รายการ)</h2>
                <div class="products-table">
                    <?php foreach ($products as $product): ?>
                        <div class="product-row">
                            <div class="product-info">
                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                <span class="price">฿<?= number_format($product['price'], 2) ?></span>
                            </div>
                            <div class="product-actions">
                                <a href="?action=delete&id=<?= $product['id'] ?>" 
                                   onclick="return confirm('ต้องการลบสินค้านี้?')" 
                                   class="delete-btn">ลบ</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    <?php endif; ?>
</body>
</html>
