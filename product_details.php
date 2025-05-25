<?php 
declare(strict_types=1); 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    $conn = new PDO("mysql:host=localhost;dbname=multi_vendeurs", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("<h3 style='color:red'>خطأ في الاتصال بقاعدة البيانات:</h3><p>".$e->getMessage()."</p>");
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$productId = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT p.*, u.nom AS vendeur_nom 
                            FROM produits2 p
                            LEFT JOIN utilisateurs u ON p.vendeur_id = u.id
                            WHERE p.id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        die("المنتج غير موجود");
    }
} catch(PDOException $e) {
    die("خطأ في جلب بيانات المنتج: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['nom']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary-color: #ffffff;
        --secondary-color: #333333;
        --accent-color:rgb(0, 28, 211);
        --hover-color:rgb(73, 34, 230);
        --gray-light: #f8f9fa;
        --gray-medium: #e9ecef;
        --gray-dark: #6c757d;
        --border-radius: 8px;
    }
    
    .product-detail-container {
        max-width: 900px;
        margin: 1.5rem auto;
        padding: 0 15px;
        display: flex;
        flex-direction: row;
        gap: 30px;
        align-items: flex-start;
    }
    
    .product-images {
        width: 50%;
        background-color: var(--gray-light);
        border-radius: var(--border-radius);
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        aspect-ratio: 1/1;
    }
    
    .product-images img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-images img:hover {
        transform: scale(1.02);
    }
    
    .product-info {
        width: 50%;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .product-info h1 {
        font-size: 1.8rem;
        color: var(--secondary-color);
        margin-bottom: 5px;
    }
    
    .price {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--accent-color);
        margin-bottom: 10px;
    }
    
    .description {
        padding: 15px;
        background-color: var(--gray-light);
        border-radius: var(--border-radius);
    }
    
    .description h3 {
        margin-bottom: 10px;
        font-size: 1.2rem;
        color: var(--secondary-color);
    }
    
    .seller-info {
        background-color: var(--gray-light);
        padding: 15px;
        border-radius: var(--border-radius);
    }
    
    .seller-info h3 {
        margin-bottom: 10px;
        font-size: 1.2rem;
        color: var(--secondary-color);
    }
    
    .add-to-cart-btn {
        background-color: var(--accent-color);
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 1rem;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
    }
    
    .add-to-cart-btn:hover {
        background-color: var(--hover-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .product-detail-container {
            flex-direction: column;
        }
        
        .product-images,
        .product-info {
            width: 100%;
        }
        
        .product-images {
            aspect-ratio: 16/9;
        }
    }
</style>
    
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="product-detail-container">
        <div class="product-images">
            <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
        </div>
        
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['nom']); ?></h1>
            <div class="price"><?php echo htmlspecialchars($product['prix']); ?> DH</div>
            
            <div class="description">
                <h3>تفاصيل المنتج</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            
            <div class="seller-info">
                <h3>معلومات البائع</h3>
                <p>البائع: <?php echo htmlspecialchars($product['vendeur_nom'] ?? 'shuriken phone '); ?></p>
            </div>
            
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                
            </form>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
