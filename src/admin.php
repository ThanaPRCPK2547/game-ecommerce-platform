<?php
require_once 'config.php';

// ตรวจสอบการเข้าสู่ระบบ (สำหรับ admin)
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = false;
}

// ฟังก์ชันสำหรับการเข้าสู่ระบบ
function login($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        return true;
    }
    return false;
}

// ฟังก์ชันสำหรับออกจากระบบ
function logout() {
    $_SESSION['admin_logged_in'] = false;
    unset($_SESSION['admin_user']);
}

// ฟังก์ชันสำหรับจัดการสินค้า
function getProducts($pdo) {
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC
    ");
    return $stmt->fetchAll();
}

function addProduct($pdo, $data) {
    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, price, category_id, stock_quantity) 
        VALUES (?, ?, ?, ?, ?)
    ");
    return $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['category_id'],
        $data['stock_quantity']
    ]);
}

function updateProduct($pdo, $id, $data) {
    $stmt = $pdo->prepare("
        UPDATE products 
        SET name = ?, description = ?, price = ?, category_id = ?, stock_quantity = ? 
        WHERE id = ?
    ");
    return $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['category_id'],
        $data['stock_quantity'],
        $id
    ]);
}

function deleteProduct($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}

// ฟังก์ชันสำหรับจัดการหมวดหมู่
function getCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}

function addCategory($pdo, $name, $description) {
    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    return $stmt->execute([$name, $description]);
}

// ฟังก์ชันสำหรับจัดการโพสต์
function getPosts($pdo) {
    $stmt = $pdo->query("
        SELECT p.*, u.full_name as author_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        ORDER BY p.created_at DESC
    ");
    return $stmt->fetchAll();
}

function addPost($pdo, $data) {
    $stmt = $pdo->prepare("
        INSERT INTO posts (title, content, author_id, status) 
        VALUES (?, ?, ?, ?)
    ");
    return $stmt->execute([
        $data['title'],
        $data['content'],
        $data['author_id'],
        $data['status']
    ]);
}

// รับข้อมูลจาก URL
$action = isset($_GET['action']) ? $_GET['action'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

// ประมวลผลการส่งฟอร์ม
if ($_POST) {
    if ($action == 'login') {
        if (login($pdo, $_POST['username'], $_POST['password'])) {
            $message = "เข้าสู่ระบบสำเร็จ!";
            $messageType = "success";
        } else {
            $message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
            $messageType = "error";
        }
    } elseif ($action == 'logout') {
        logout();
        $message = "ออกจากระบบแล้ว!";
        $messageType = "success";
    } elseif ($action == 'add_product') {
        if (addProduct($pdo, $_POST)) {
            $message = "เพิ่มสินค้าสำเร็จ!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาดในการเพิ่มสินค้า!";
            $messageType = "error";
        }
    } elseif ($action == 'update_product') {
        if (updateProduct($pdo, $_POST['id'], $_POST)) {
            $message = "อัปเดตสินค้าสำเร็จ!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาดในการอัปเดตสินค้า!";
            $messageType = "error";
        }
    } elseif ($action == 'delete_product') {
        if (deleteProduct($pdo, $_POST['id'])) {
            $message = "ลบสินค้าสำเร็จ!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาดในการลบสินค้า!";
            $messageType = "error";
        }
    } elseif ($action == 'add_category') {
        if (addCategory($pdo, $_POST['name'], $_POST['description'])) {
            $message = "เพิ่มหมวดหมู่สำเร็จ!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาดในการเพิ่มหมวดหมู่!";
            $messageType = "error";
        }
    } elseif ($action == 'add_post') {
        if (addPost($pdo, $_POST)) {
            $message = "เพิ่มโพสต์สำเร็จ!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาดในการเพิ่มโพสต์!";
            $messageType = "error";
        }
    }
}

// ตรวจสอบการเข้าสู่ระบบ
if (!$_SESSION['admin_logged_in']) {
    // หน้าเข้าสู่ระบบ
    ?>
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>เข้าสู่ระบบ - <?php echo SITE_NAME; ?></title>
        <link rel="stylesheet" href="style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <header>
                <h1><i class="fas fa-lock"></i> เข้าสู่ระบบ Admin</h1>
                <p>กรุณาเข้าสู่ระบบเพื่อจัดการเว็บไซต์</p>
            </header>
            
            <main>
                <div class="card" style="max-width: 400px; margin: 0 auto;">
                    <?php if (isset($message)): ?>
                        <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="?action=login">
                        <div class="form-group">
                            <label for="username">ชื่อผู้ใช้:</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">รหัสผ่าน:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn">เข้าสู่ระบบ</button>
                    </form>
                    
                    <p style="text-align: center; margin-top: 20px;">
                        <a href="index.php" class="btn">กลับหน้าแรก</a>
                    </p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <h4>ข้อมูลสำหรับทดสอบ:</h4>
                        <p><strong>Username:</strong> admin</p>
                        <p><strong>Password:</strong> admin123</p>
                    </div>
                </div>
            </main>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// หน้า Admin Dashboard
$products = getProducts($pdo);
$categories = getCategories($pdo);
$posts = getPosts($pdo);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-cogs"></i> Admin Panel</h1>
            <p>จัดการเว็บไซต์ - ยินดีต้อนรับ <?php echo $_SESSION['admin_user']['full_name']; ?></p>
        </header>

        <nav>
            <ul>
                <li><a href="?type=products"><i class="fas fa-box"></i> จัดการสินค้า</a></li>
                <li><a href="?type=categories"><i class="fas fa-tags"></i> จัดการหมวดหมู่</a></li>
                <li><a href="?type=posts"><i class="fas fa-newspaper"></i> จัดการโพสต์</a></li>
                <li><a href="?type=users"><i class="fas fa-users"></i> จัดการผู้ใช้</a></li>
                <li><a href="index.php"><i class="fas fa-home"></i> กลับหน้าแรก</a></li>
                <li><a href="?action=logout"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a></li>
            </ul>
        </nav>

        <main>
            <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($type == 'products' || $type == ''): ?>
                <!-- จัดการสินค้า -->
                <div class="card">
                    <h2><i class="fas fa-box"></i> จัดการสินค้า</h2>
                    
                    <!-- ฟอร์มเพิ่มสินค้า -->
                    <div class="card">
                        <h3>เพิ่มสินค้าใหม่</h3>
                        <form method="POST" action="?action=add_product">
                            <div class="form-group">
                                <label for="name">ชื่อสินค้า:</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">รายละเอียด:</label>
                                <textarea id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">ราคา:</label>
                                <input type="number" id="price" name="price" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="category_id">หมวดหมู่:</label>
                                <select id="category_id" name="category_id" required>
                                    <option value="">เลือกหมวดหมู่</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="stock_quantity">จำนวนคงเหลือ:</label>
                                <input type="number" id="stock_quantity" name="stock_quantity" min="0" required>
                            </div>
                            <button type="submit" class="btn btn-success">เพิ่มสินค้า</button>
                        </form>
                    </div>

                    <!-- รายการสินค้า -->
                    <div class="card">
                        <h3>รายการสินค้าทั้งหมด</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคา</th>
                                    <th>หมวดหมู่</th>
                                    <th>คงเหลือ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>฿<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                    <td><?php echo $product['stock_quantity']; ?></td>
                                    <td>
                                        <a href="?type=products&action=edit&id=<?php echo $product['id']; ?>" class="btn">แก้ไข</a>
                                        <form method="POST" action="?action=delete_product" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่?')">ลบ</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($type == 'categories'): ?>
                <!-- จัดการหมวดหมู่ -->
                <div class="card">
                    <h2><i class="fas fa-tags"></i> จัดการหมวดหมู่</h2>
                    
                    <!-- ฟอร์มเพิ่มหมวดหมู่ -->
                    <div class="card">
                        <h3>เพิ่มหมวดหมู่ใหม่</h3>
                        <form method="POST" action="?action=add_category">
                            <div class="form-group">
                                <label for="name">ชื่อหมวดหมู่:</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">รายละเอียด:</label>
                                <textarea id="description" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">เพิ่มหมวดหมู่</button>
                        </form>
                    </div>

                    <!-- รายการหมวดหมู่ -->
                    <div class="card">
                        <h3>รายการหมวดหมู่ทั้งหมด</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ชื่อหมวดหมู่</th>
                                    <th>รายละเอียด</th>
                                    <th>วันที่สร้าง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($type == 'posts'): ?>
                <!-- จัดการโพสต์ -->
                <div class="card">
                    <h2><i class="fas fa-newspaper"></i> จัดการโพสต์</h2>
                    
                    <!-- ฟอร์มเพิ่มโพสต์ -->
                    <div class="card">
                        <h3>เพิ่มโพสต์ใหม่</h3>
                        <form method="POST" action="?action=add_post">
                            <div class="form-group">
                                <label for="title">หัวข้อ:</label>
                                <input type="text" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="content">เนื้อหา:</label>
                                <textarea id="content" name="content" rows="10" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="status">สถานะ:</label>
                                <select id="status" name="status">
                                    <option value="draft">ร่าง</option>
                                    <option value="published">เผยแพร่</option>
                                </select>
                            </div>
                            <input type="hidden" name="author_id" value="<?php echo $_SESSION['admin_user']['id']; ?>">
                            <button type="submit" class="btn btn-success">เพิ่มโพสต์</button>
                        </form>
                    </div>

                    <!-- รายการโพสต์ -->
                    <div class="card">
                        <h3>รายการโพสต์ทั้งหมด</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>หัวข้อ</th>
                                    <th>ผู้เขียน</th>
                                    <th>สถานะ</th>
                                    <th>วันที่สร้าง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo $post['id']; ?></td>
                                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                                    <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                                              background: <?php echo $post['status'] == 'published' ? '#d4edda' : '#fff3cd'; ?>; 
                                              color: <?php echo $post['status'] == 'published' ? '#155724' : '#856404'; ?>;">
                                            <?php echo $post['status'] == 'published' ? 'เผยแพร่' : 'ร่าง'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($type == 'users'): ?>
                <!-- จัดการผู้ใช้ -->
                <div class="card">
                    <h2><i class="fas fa-users"></i> จัดการผู้ใช้</h2>
                    <p>ระบบจัดการผู้ใช้จะเพิ่มในเวอร์ชันถัดไป</p>
                </div>

            <?php endif; ?>
        </main>
    </div>
</body>
</html>
