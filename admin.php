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
        $sql = "INSERT INTO products (name, price, description, image, category, genres, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['name'], 
            $_POST['price'], 
            $_POST['description'], 
            $_POST['image'],
            $_POST['category'],
            $_POST['genres'],
            $_POST['rating']
        ]);
        $success = 'เพิ่มเกมเรียบร้อย';
    }
    
    if ($_GET['action'] ?? '' === 'delete' && $_GET['id']) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $success = 'ลบเกมเรียบร้อย';
    }

    if ($_POST['action'] ?? '' === 'sync_steam') {
        $response = file_get_contents('api.php?action=sync_steam');
        $result = json_decode($response, true);
        $success = $result['message'] ?? 'Sync เสร็จสิ้น';
    }
    
    $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
    $stats = [
        'total_games' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
        'steam_games' => $pdo->query("SELECT COUNT(*) FROM products WHERE category = 'steam'")->fetchColumn(),
        'free_games' => $pdo->query("SELECT COUNT(*) FROM products WHERE price LIKE '%Free%'")->fetchColumn(),
        'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn()
    ];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎮 Game Store Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (!isAdmin()): ?>
        <div class="login-container">
            <h2>🎮 Game Store Admin</h2>
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
            <h1>🎮 Game Store Admin</h1>
            <div>
                <a href="index.php">ดูหน้าเว็บ</a>
                <a href="?action=logout">ออกจากระบบ</a>
            </div>
        </header>

        <main class="admin-main">
            <?php if (isset($success)): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <!-- สถิติ -->
            <section class="stats-section">
                <h2>📊 สถิติร้านเกม</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?= $stats['total_games'] ?></h3>
                        <p>เกมทั้งหมด</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['steam_games'] ?></h3>
                        <p>เกม Steam</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['free_games'] ?></h3>
                        <p>เกมฟรี</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $stats['total_orders'] ?></h3>
                        <p>คำสั่งซื้อ</p>
                    </div>
                </div>
            </section>

            <!-- API Tools -->
            <section class="api-tools">
                <h2>🔧 เครื่องมือ API</h2>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="sync_steam">
                    <button type="submit" class="api-btn steam">Sync เกมจาก Steam</button>
                </form>
                <button onclick="testAPI()" class="api-btn epic">ทดสอบ Epic API</button>
            </section>

            <!-- เพิ่มเกม -->
            <section class="add-product">
                <h2>➕ เพิ่มเกมใหม่</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add_product">
                    <div class="form-grid">
                        <input type="text" name="name" placeholder="ชื่อเกม" required>
                        <input type="text" name="price" placeholder="ราคา (เช่น ฿999 หรือ Free)" required>
                        <select name="category" required>
                            <option value="">เลือกแพลตฟอร์ม</option>
                            <option value="steam">Steam</option>
                            <option value="epic">Epic Games</option>
                            <option value="origin">Origin</option>
                            <option value="uplay">Uplay</option>
                            <option value="gog">GOG</option>
                            <option value="mobile">Mobile</option>
                        </select>
                        <input type="number" name="rating" step="0.1" min="0" max="5" placeholder="คะแนน (0-5)">
                        <input type="text" name="genres" placeholder="ประเภทเกม (เช่น Action, RPG)">
                        <input type="url" name="image" placeholder="URL รูปภาพ">
                    </div>
                    <textarea name="description" placeholder="รายละเอียดเกม" rows="3"></textarea>
                    <button type="submit">เพิ่มเกม</button>
                </form>
            </section>

            <!-- รายการเกม -->
            <section class="products-list">
                <h2>🎮 จัดการเกม (<?= count($products) ?> เกม)</h2>
                <div class="products-table">
                    <?php foreach ($products as $product): ?>
                        <div class="product-row">
                            <div class="product-info">
                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                <span class="price"><?= htmlspecialchars($product['price']) ?></span>
                                <span class="platform-badge <?= $product['category'] ?>"><?= strtoupper($product['category']) ?></span>
                                <?php if ($product['rating']): ?>
                                    <span class="rating">⭐ <?= $product['rating'] ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-actions">
                                <a href="?action=delete&id=<?= $product['id'] ?>" 
                                   onclick="return confirm('ต้องการลบเกมนี้?')" 
                                   class="delete-btn">ลบ</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>

        <script>
            async function testAPI() {
                try {
                    const response = await fetch('api.php?action=epic');
                    const data = await response.json();
                    alert('Epic API ทำงานได้! พบเกมฟรี: ' + data.length + ' เกม');
                } catch (error) {
                    alert('API Error: ' + error.message);
                }
            }
        </script>
    <?php endif; ?>
</body>
</html>
