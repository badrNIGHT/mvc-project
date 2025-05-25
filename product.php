<?php declare(strict_types=1); ?>

<?php
session_start();
include 'config.php';

// الحصول على معرف المنتج من الرابط
$produit_id = $_GET['id'] ?? 0;

// استعلام لعرض تفاصيل المنتج
$sql_produit = "SELECT produits.*, utilisateurs.nom AS vendeur_nom 
                FROM produits 
                JOIN utilisateurs ON produits.vendeur_id = utilisateurs.id 
                WHERE produits.id = $produit_id";
$result_produit = $conn->query($sql_produit);
$produit = $result_produit->fetch_assoc();

// استعلام لعرض التقييمات
$sql_avis = "SELECT avis.*, utilisateurs.nom 
             FROM avis 
             JOIN utilisateurs ON avis.utilisateur_id = utilisateurs.id 
             WHERE produit_id = $produit_id";
$result_avis = $conn->query($sql_avis);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title><?php echo $produit['nom']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <style>
        .product-details {
    text-align: center;
    padding: 20px;
    margin: 20px auto;
    max-width: 800px;
}

.product-details img {
    max-width: 400px;
    height: auto;
    border-radius: 10px;
}

/* تنسيقات التقييمات */
.avis-form {
    background: #f5f5f7;
    padding: 20px;
    margin: 20px auto;
    max-width: 600px;
    border-radius: 8px;
}

.avis-form select, .avis-form textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #d2d2d7;
    border-radius: 4px;
}

.avis-list {
    margin: 20px auto;
    max-width: 800px;
}

.avis-item {
    background: white;
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.note {
    color: #ffd700;
    font-size: 1.4em;
}
    </style>
    <!-- تفاصيل المنتج -->
    <div class="product-details">
        <img src="image/<?php echo $produit['image']; ?>" alt="<?php echo $produit['nom']; ?>">
        <h1><?php echo htmlspecialchars($produit['nom']); ?></h1>
        <p class="price">السعر: <?php echo $produit['prix']; ?> د.م</p>
        <p class="seller">البائع: <?php echo htmlspecialchars($produit['vendeur_nom']); ?></p>
    </div>

    <!-- نموذج إضافة تقييم -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="submit_avis.php" method="POST" class="avis-form">
            <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
            <select name="note" required>
                <option value="5">★★★★★</option>
                <option value="4">★★★★</option>
                <option value="3">★★★</option>
                <option value="2">★★</option>
                <option value="1">★</option>
            </select>
            <textarea name="commentaire" placeholder="اكتب تعليقك..." required></textarea>
            <button type="submit">إرسال التقييم</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">سجل دخول</a> لإضافة تقييم.</p>
    <?php endif; ?>

    <!-- عرض التقييمات -->
    <div class="avis-list">
        <?php while ($row = $result_avis->fetch_assoc()): ?>
            <div class="avis-item">
                <h4><?php echo htmlspecialchars($row['nom']); ?></h4>
                <div class="note"><?php echo str_repeat('★', $row['note']); ?></div>
                <p><?php echo htmlspecialchars($row['commentaire']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>