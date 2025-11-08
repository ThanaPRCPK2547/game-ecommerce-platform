<?php
require_once 'config.php';

// รับข้อมูลจาก URL parameters
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ฟังก์ชันสำหรับแสดงข้อมูลสินค้า
function getProducts($pdo, $limit = 6) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// ฟังก์ชันสำหรับแสดงโพสต์
function getPosts($pdo, $limit = 3) {
    $stmt = $pdo->prepare("
        SELECT p.*, u.full_name as author_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        WHERE p.status = 'published' 
        ORDER BY p.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// ฟังก์ชันสำหรับแสดงสถิติ
function getStats($pdo) {
    $stats = [];
    
    // นับจำนวนสินค้า
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $stats['products'] = $stmt->fetch()['count'];
    
    // นับจำนวนโพสต์
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 'published'");
    $stats['posts'] = $stmt->fetch()['count'];
    
    // นับจำนวนหมวดหมู่
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $stats['categories'] = $stmt->fetch()['count'];
    
    return $stats;
}

$stats = getStats($pdo);
$products = getProducts($pdo);
$posts = getPosts($pdo);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1><i class="fas fa-globe"></i> <?php echo SITE_NAME; ?></h1>
            <p>เว็บไซต์ที่พัฒนาด้วย PHP และ MySQL สำหรับ Awardspace.net</p>
        </header>

        <!-- Navigation -->
        <nav>
            <ul>
                <li><a href="?page=home"><i class="fas fa-home"></i> หน้าแรก</a></li>
                <li><a href="?page=products"><i class="fas fa-shopping-bag"></i> สินค้า</a></li>
                <li><a href="?page=posts"><i class="fas fa-newspaper"></i> บทความ</a></li>
                <li><a href="?page=about"><i class="fas fa-info-circle"></i> เกี่ยวกับเรา</a></li>
                <li><a href="?page=contact"><i class="fas fa-envelope"></i> ติดต่อเรา</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main>
            <?php if ($page == 'home'): ?>
                <!-- Home Page -->
                <div class="card">
                    <h2><i class="fas fa-tachometer-alt"></i> สถิติเว็บไซต์</h2>
                    <div class="grid">
                        <div class="card">
                            <h3><i class="fas fa-box"></i> สินค้า</h3>
                            <p style="font-size: 2rem; color: #3498db; font-weight: bold;"><?php echo $stats['products']; ?></p>
                        </div>
                        <div class="card">
                            <h3><i class="fas fa-newspaper"></i> บทความ</h3>
                            <p style="font-size: 2rem; color: #27ae60; font-weight: bold;"><?php echo $stats['posts']; ?></p>
                        </div>
                        <div class="card">
                            <h3><i class="fas fa-tags"></i> หมวดหมู่</h3>
                            <p style="font-size: 2rem; color: #e74c3c; font-weight: bold;"><?php echo $stats['categories']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Featured Products -->
                <div class="card">
                    <h2><i class="fas fa-star"></i> สินค้าแนะนำ</h2>
                    <div class="grid">
                        <?php foreach ($products as $product): ?>
                        <div class="card">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                            <p><strong>ราคา: ฿<?php echo number_format($product['price'], 2); ?></strong></p>
                            <p><small>หมวดหมู่: <?php echo htmlspecialchars($product['category_name']); ?></small></p>
                            <p><small>คงเหลือ: <?php echo $product['stock_quantity']; ?> ชิ้น</small></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Latest Posts -->
                <div class="card">
                    <h2><i class="fas fa-newspaper"></i> บทความล่าสุด</h2>
                    <?php foreach ($posts as $post): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($post['content'], 0, 200)) . '...'; ?></p>
                        <p><small>โดย: <?php echo htmlspecialchars($post['author_name']); ?> | 
                        วันที่: <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></small></p>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($page == 'products'): ?>
                <!-- Products Page -->
                <div class="card">
                    <h2><i class="fas fa-shopping-bag"></i> สินค้าทั้งหมด</h2>
                    <div class="grid">
                        <?php 
                        $allProducts = getProducts($pdo, 20);
                        foreach ($allProducts as $product): 
                        ?>
                        <div class="card">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <p><strong>ราคา: ฿<?php echo number_format($product['price'], 2); ?></strong></p>
                            <p><small>หมวดหมู่: <?php echo htmlspecialchars($product['category_name']); ?></small></p>
                            <p><small>คงเหลือ: <?php echo $product['stock_quantity']; ?> ชิ้น</small></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php elseif ($page == 'posts'): ?>
                <!-- Posts Page -->
                <div class="card">
                    <h2><i class="fas fa-newspaper"></i> บทความทั้งหมด</h2>
                    <?php 
                    $allPosts = getPosts($pdo, 20);
                    foreach ($allPosts as $post): 
                    ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <p><small>โดย: <?php echo htmlspecialchars($post['author_name']); ?> | 
                        วันที่: <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></small></p>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($page == 'about'): ?>
                <!-- About Page -->
                <div class="card">
                    <h2><i class="fas fa-info-circle"></i> เกี่ยวกับเรา</h2>
                    <p>ยินดีต้อนรับสู่เว็บไซต์ของเรา! เราเป็นเว็บไซต์ที่พัฒนาด้วย PHP และ MySQL ที่สามารถ deploy ลง Awardspace.net ได้อย่างง่ายดาย</p>
                    
                    <h3>คุณสมบัติของเว็บไซต์:</h3>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li>ระบบจัดการสินค้า (Products Management)</li>
                        <li>ระบบจัดการบทความ (Posts Management)</li>
                        <li>ระบบจัดการหมวดหมู่ (Categories Management)</li>
                        <li>ระบบจัดการผู้ใช้ (Users Management)</li>
                        <li>Responsive Design ที่ใช้งานได้ทุกอุปกรณ์</li>
                        <li>UI/UX ที่สวยงามและทันสมัย</li>
                    </ul>

                    <h3>เทคโนโลยีที่ใช้:</h3>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li>PHP 7.4+</li>
                        <li>MySQL 5.7+</li>
                        <li>HTML5 & CSS3</li>
                        <li>JavaScript (ES6+)</li>
                        <li>Font Awesome Icons</li>
                    </ul>
                </div>

            <?php elseif ($page == 'contact'): ?>
                <!-- Contact Page -->
                <div class="card">
                    <h2><i class="fas fa-envelope"></i> ติดต่อเรา</h2>
                    <form method="POST" action="?page=contact">
                        <div class="form-group">
                            <label for="name">ชื่อ-นามสกุล:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">อีเมล:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">หัวข้อ:</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">ข้อความ:</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn">ส่งข้อความ</button>
                    </form>

                    <?php
                    if ($_POST && isset($_POST['name'])) {
                        echo '<div class="alert alert-success">ขอบคุณสำหรับข้อความ! เราจะติดต่อกลับโดยเร็วที่สุด</div>';
                    }
                    ?>
                </div>

            <?php else: ?>
                <!-- 404 Page -->
                <div class="card">
                    <h2><i class="fas fa-exclamation-triangle"></i> หน้าไม่พบ</h2>
                    <p>ขออภัย หน้าที่คุณกำลังมองหาไม่มีอยู่</p>
                    <a href="?page=home" class="btn">กลับหน้าแรก</a>
                </div>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 <?php echo SITE_NAME; ?>. พัฒนาด้วย PHP และ MySQL สำหรับ Awardspace.net</p>
            <p>ติดต่อ: admin@example.com | โทร: 02-xxx-xxxx</p>
        </footer>
    </div>

    <script>
        // Simple JavaScript for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Add loading animation
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
