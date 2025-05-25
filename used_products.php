<?php declare(strict_types=1); 
// تفعيل عرض الأخطاء للتصحيح
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';

// معالجة رسائل النجاح/الأخطاء
$alert = '';
if (isset($_GET['success'])) {
    $alert = '<div class="alert success">تمت إضافة المنتج بنجاح!</div>';
} elseif (isset($_GET['error'])) {
    $alert = '<div class="alert error">حدث خطأ: '.htmlspecialchars($_GET['error']).'</div>';
}

// معالجة البحث
$searchQuery = '';
$whereClause = "WHERE u.role = 'vendeur'";
$params = [];
$searchActive = false;

if (isset($_GET['search']) && isset($_SESSION['search_query']) && !empty($_SESSION['search_query'])) {
    $searchQuery = $_SESSION['search_query'];
    $searchTerm = '%'.$searchQuery.'%';
    $whereClause = "WHERE (p.nom LIKE ? OR p.description LIKE ? OR p.marque LIKE ?) AND u.role = 'vendeur'";
    $params = [$searchTerm, $searchTerm, $searchTerm];
    $searchActive = true;
    unset($_SESSION['search_query']);
}

// جلب المنتجات
$sql = "SELECT p.*, u.nom AS vendeur_nom 
        FROM produits p 
        JOIN utilisateurs u ON p.vendeur_id = u.id
        $whereClause
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);

// التحقق من نوع الاتصال واستخدام الدالة المناسبة
if (is_a($conn, 'PDO')) {
    $products = $result->fetchAll(PDO::FETCH_ASSOC);
} elseif (is_a($conn, 'mysqli')) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    die("نوع اتصال قاعدة البيانات غير معروف");
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $searchActive ? 'نتائج البحث' : 'Produits d\'Occasion - Étudiants'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 <style>
    :root {
        --primary-color: #4a6bff;
        --secondary-color: #ff6b6b;
        --dark-color: #2d3436;
        --light-color: #f9f9f9;
        --gray-color: #636e72;
        --success-color: #00b894;
        --danger-color: #d63031;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-color);
        color: var(--dark-color);
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }
    
    .alert {
        padding: 15px;
        margin: 20px auto;
        max-width: 1200px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
    
    .alert.success {
        background-color: #d4edda;
        color: var(--success-color);
        border: 1px solid #c3e6cb;
    }
    
    .alert.error {
        background-color: #f8d7da;
        color: var(--danger-color);
        border: 1px solid #f5c6cb;
    }
    
   .header {
    background: linear-gradient(135deg, var(--primary-color), #6c5ce7);
    color: white;
    padding: 60px 0 50px; /* تصغير الحجم من 100px/80px إلى 60px/50px */
    text-align: center;
    margin-bottom: 30px; /* تصغير الهامش السفلي */
    position: relative;
    clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
    overflow: hidden;
}

.header h1 {
    font-size: 2.2rem; /* تصغير الحجم من 3rem */
    margin: 0 0 10px; /* تقليل الهامش السفلي */
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    position: relative;
    display: inline-block;
    animation: float 3s ease-in-out infinite;
}

.header h1::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--secondary-color);
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.5s ease;
}

.header:hover h1::after {
    transform: scaleX(1);
    transform-origin: left;
}

.header p {
    font-size: 1.1rem; /* تصغير حجم النص الفرعي */
    opacity: 0.9;
    max-width: 700px; /* تصغير العرض الأقصى */
    margin: 0 auto 15px;
    animation: fadeInUp 1s ease;
}

/* إضافة تأثيرات حركية */
@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* إضافة جزيئات متحركة في الخلفية */
.particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    animation: floatParticle linear infinite;
}

@keyframes floatParticle {
    0% {
        transform: translateY(0) translateX(0);
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh) translateX(100px);
        opacity: 0;
    }
}
    
    .search-results-header {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        margin: -20px -20px 30px;
        border-radius: 0 0 10px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .search-results-header a {
        color: white;
        text-decoration: none;
        background-color: rgba(255,255,255,0.2);
        padding: 8px 15px;
        border-radius: 20px;
        transition: all 0.3s;
    }
    
    .search-results-header a:hover {
        background-color: rgba(255,255,255,0.3);
    }
    
    .products-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 20px 40px;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .product-image {
        height: 220px;
        overflow: hidden;
        position: relative;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: var(--secondary-color);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.8rem;
        z-index: 1;
    }
    
    .promo-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: var(--danger-color);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.8rem;
        z-index: 1;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-info h3 {
        margin: 0 0 10px;
        color: var(--dark-color);
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .product-description {
        color: var(--gray-color);
        margin-bottom: 15px;
        font-size: 0.9rem;
        line-height: 1.5;
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        font-weight: bold;
        font-size: 1.3rem;
        color: var(--primary-color);
        margin: 15px 0;
    }
    
    .original-price {
        text-decoration: line-through;
        color: var(--gray-color);
        font-size: 1rem;
        margin-right: 10px;
    }
    
    .buy-btn {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        background: linear-gradient(to right, var(--primary-color), #6c5ce7);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .buy-btn:hover {
        background: linear-gradient(to right, #3a56ff, #5d4ae6);
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(74, 107, 255, 0.3);
    }
    
    .seller-info {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        font-size: 0.85rem;
    }
    
    .seller-icon {
        margin-right: 8px;
        color: var(--primary-color);
        font-size: 1rem;
    }
    
    .no-products {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .no-products i {
        font-size: 3rem;
        color: var(--gray-color);
        margin-bottom: 20px;
    }
    
    .no-products h3 {
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .no-products p {
        color: var(--gray-color);
        margin-bottom: 20px;
    }
    
    .no-products a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: var(--primary-color);
        color: white;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .no-products a:hover {
        background: #3a56ff;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }
        
        .product-image {
            height: 180px;
        }
        
        .header h1 {
            font-size: 2.2rem;
        }
        
        .header p {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
        
        .search-results-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
    }
    /**hada dyal dark mod dyal lcart */
    <style>
    :root {
        --primary-color: #4a6bff;
        --secondary-color: #ff6b6b;
        --dark-color: #2d3436;
        --light-color: #f9f9f9;
        --gray-color: #636e72;
        --success-color: #00b894;
        --danger-color: #d63031;
        
        /* إضافة متغيرات للوضع الداكن */
        --bg-color: #f9f9f9;
        --text-color: #2d3436;
        --card-bg: #ffffff;
        --card-shadow: 0 5px 15px rgba(0,0,0,0.1);
        --border-color: #eee;
    }
    
    /* إضافة كلاس للوضع الداكن */
    body.dark-mode {
        --bg-color: #1a1a1a;
        --text-color: #f0f0f0;
        --card-bg: #2d2d2d;
        --card-shadow: 0 5px 15px rgba(0,0,0,0.3);
        --border-color: #444;
        --gray-color: #aaa;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        margin: 0;
        padding: 0;
        line-height: 1.6;
        transition: background-color 0.3s, color 0.3s;
    }
    
    /* ... (بقية الأنماط تبقى كما هي) ... */
    
    .product-card {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: none;
    }
    
    .product-info h3 {
        margin: 0 0 10px;
        color: var(--text-color);
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .product-description {
        color: var(--gray-color);
        margin-bottom: 15px;
        font-size: 0.9rem;
        line-height: 1.5;
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .seller-info {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
        font-size: 0.85rem;
    }
    
    .no-products {
        text-align: center;
        padding: 50px 20px;
        background: var(--card-bg);
        border-radius: 10px;
        box-shadow: var(--card-shadow);
    }
    
    .no-products h3 {
        color: var(--text-color);
        margin-bottom: 10px;
    }

</style>



</head>
<body>
<?php include 'navbar.php'; ?>
    
    <?php if ($alert): ?>
        <?php echo $alert; ?>
    <?php endif; ?>
    
    <?php if ($searchActive): ?>
        <div class="search-results-header">
            <h2>نتائج البحث عن "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
            <a href="used_products.php">عرض جميع المنتجات</a>
        </div>
    <?php else: ?>
        <div class="header">
            <h1><i class="fas fa-graduation-cap"></i> Marketplace Étudiant</h1>
            <p>Trouvez des téléphones d'occasion à prix réduits proposés par des étudiants</p>
        </div>
    <?php endif; ?>
    
    <div class="products-container">
        <?php if (!empty($products)): ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
                            <span class="product-badge">Occasion</span>
                            <?php if ($product['promotion'] > 0): ?>
                                <span class="promo-badge">-<?php echo htmlspecialchars($product['promotion']); ?>%</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['nom']); ?></h3>
                            <p class="product-description"><?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...</p>
                            
                            <div class="product-price">
                                <?php if ($product['promotion'] > 0): ?>
                                    <span class="original-price"><?php echo htmlspecialchars($product['prix']); ?> DH</span>
                                    <?php echo htmlspecialchars(round($product['prix'] * (1 - $product['promotion'] / 100), 2)); ?> DH
                                <?php else: ?>
                                    <?php echo htmlspecialchars($product['prix']); ?> DH
                                <?php endif; ?>
                            </div>
                            
                            <form action="add_to_cart3.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['nom']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo ($product['promotion'] > 0) ? round($product['prix'] * (1 - $product['promotion'] / 100), 2) : $product['prix']; ?>">
                                <button type="submit" class="buy-btn">
                                    <i class="fas fa-shopping-cart"></i> Acheter
                                </button>
                            </form>
                            
                            <div class="seller-info">
                                <i class="fas fa-user-graduate seller-icon"></i>
                                <span>Vendu par: <?php echo htmlspecialchars($product['vendeur_nom']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-products">
                <i class="fas fa-box-open"></i>
                <h3><?php echo $searchActive ? 'لا توجد نتائج مطابقة للبحث' : 'Aucun produit disponible'; ?></h3>
                <p><?php echo $searchActive ? 'حاول باستخدام كلمات بحث مختلفة' : 'Revenez plus tard ou proposez vos propres articles!'; ?></p>
                <a href="used_products.php">عرض جميع المنتجات</a>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script>
    // تفعيل الوضع الداكن
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        
        // التحقق من الإعدادات المحفوظة
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
        
        // تبديل الوضع عند النقر
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                
                // حفظ التفضيل
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                }
            });
        }
    });
</script>
</body>
</html>