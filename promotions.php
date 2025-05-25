<?php declare(strict_types=1); 
session_start();
include 'config.php';
include 'navbar.php';

// Fetch promotions from the database
$current_date = date('Y-m-d');
$sql = "SELECT * FROM promotions 
        WHERE disponible = 1 
        AND date_debut <= '$current_date' 
        AND date_fin >= '$current_date'
        ORDER BY pourcentage DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions Spéciales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #ffffff;
            --primary-dark-light: #f5f5f7;
            --secondary-gold: #000000;
            --secondary-gold-light: #86868b;
            --accent-blue: #0066cc;
            --light-bg: #ffffff;
            --pure-white: #ffffff;
            --text-dark: #1d1d1f;
            --text-light: #86868b;
        }

        [data-theme="dark"] {
            --primary-dark: #121212;
            --primary-dark-light: #1e1e1e;
            --light-bg: #121212;
            --pure-white: #121212;
            --text-dark: #f1f1f1;
            --text-light: #86868b;
        }

        [data-theme="dark"] .product-card,
        [data-theme="dark"] .promo-header {
            background-color: var(--primary-dark-light);
            border-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-dark);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .promo-header {
            background: var(--primary-dark);
            color: var(--text-dark);
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #d2d2d7;
            transition: background-color 0.3s ease;
        }

        .promo-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .promo-header p {
            font-size: 1.2rem;
            color: var(--text-light);
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .product-card {
            position: relative;
            background: var(--primary-dark);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 420px;
            border: 1px solid #d2d2d7;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: var(--accent-blue);
        }

       .product-image-container {
    position: relative;
    width: 100%;
    height: 60%; /* يمكن زيادة هذه النسبة إذا لزم الأمر */
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background: transparent; /* إزالة أي لون خلفية */
    padding: 0; /* إزالة أي padding */
    margin: 0;
}

        .product-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* تغيير من contain إلى cover لتغطية المساحة بالكامل */
    object-position: center; /* لتوسيط الصورة */
    transition: transform 0.6s ease;
}

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .promo-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #e74c3c;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 1;
        }

        .model-info-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .model-info-overlay {
            opacity: 1;
        }

        .model-info-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            text-align: center;
            color: white;
        }

        .model-info-details {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.9;
            color: white;
        }

        .spec-item {
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .spec-item i {
            margin-left: 10px;
            color: #fff;
        }

        .product-info {
            padding: 15px;
            background: var(--primary-dark);
            border-top: 1px solid #d2d2d7;
            text-align: center;
        }

        .product-name {
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .product-price {
            font-size: 1.3rem;
            color: var(--accent-blue);
            font-weight: 700;
            margin-bottom: 15px;
        }

        .original-price {
            text-decoration: line-through;
            color: var(--text-light);
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        .add-to-cart {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: var(--accent-blue);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .add-to-cart:hover {
            background-color: #0052a3;
            transform: translateY(-2px);
        }

        .add-to-cart i {
            margin-left: 5px;
        }

        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification.error {
            background-color: #f44336;
        }

        @media (max-width: 1200px) {
            .products-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .products-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-container {
                grid-template-columns: 1fr;
            }

            .promo-header h1 {
                font-size: 2rem;
            }

            .promo-header p {
                font-size: 1rem;
            }
        }
        /*zwi9a jdida*/
        
    </style>
</head>
<body>
    <header class="promo-header">
        <h1>Promotions en cours</h1>
        <p>Découvrez nos offres exceptionnelles</p>
    </header>
    
    <div class="products-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <?php
                // Calculate the promotional price if prix_promotion is NULL
                $effectivePrice = $product['prix_promotion'] ?? ($product['prix_original'] * (1 - $product['pourcentage'] / 100));
                
                // Determine image path with fallback
                $imageName = basename($product['image']);
                $possiblePaths = [
                    'images/' . $imageName,
                    'uploads/' . $imageName,
                    'image/' . $imageName,
                    str_replace(' ', '', 'images/' . $imageName),
                    str_replace(' ', '', 'uploads/' . $imageName)
                ];
                
                $defaultImage = 'images/default-product.jpg';
                $finalImage = $defaultImage;
                
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $finalImage = $path;
                        break;
                    }
                }
                ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <span class="promo-badge">-<?= $product['pourcentage'] ?>%</span>
                        <img src="<?= htmlspecialchars($finalImage) ?>" 
                             alt="<?= htmlspecialchars($product['nom']) ?>" 
                             class="product-image"
                             onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultImage) ?>'">
                        <div class="model-info-overlay">
                            <div class="model-info-title"><?= htmlspecialchars($product['nom']) ?></div>
                            <div class="model-info-details">
                                <div class="spec-item"><i class="fas fa-box"></i> Stock: <?= $product['quantite'] ?></div>
                                <?php if($product['stockage']): ?>
                                    <div class="spec-item"><i class="fas fa-hdd"></i> Stockage: <?= $product['stockage'] ?>GB</div>
                                <?php endif; ?>
                                <?php if($product['ram']): ?>
                                    <div class="spec-item"><i class="fas fa-memory"></i> RAM: <?= $product['ram'] ?>GB</div>
                                <?php endif; ?>
                                <div class="spec-item"><i class="fas fa-clock"></i> Valide jusqu'au: <?= date('d/m/Y', strtotime($product['date_fin'])) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($product['nom']) ?></h3>
                        <div class="product-price">
                            <?= number_format((float)$effectivePrice, 2) ?> DH
                            <span class="original-price"><?= number_format((float)$product['prix_original'], 2) ?> DH</span>
                        </div>
                        <button class="add-to-cart" onclick="addToCart(<?= $product['id'] ?>, '<?= addslashes($product['nom']) ?>', <?= $effectivePrice ?>)">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column:1/-1; text-align:center; padding:2rem;">
                Aucune promotion disponible pour le moment
            </div>
        <?php endif; ?>
    </div>

    <div class="notification" id="notification">Produit ajouté au panier</div>

    <script>
    function addToCart(productId, productName, productPrice) {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout en cours...';
        btn.disabled = true;

        fetch('add_to_cart2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&product_name=${encodeURIComponent(productName)}&product_price=${productPrice}`
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const notification = document.getElementById('notification');
                if (notification) {
                    notification.textContent = data.message;
                    notification.classList.add('show');
                    setTimeout(() => notification.classList.remove('show'), 3000);
                }
                
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                    cartCount.style.display = data.cartCount > 0 ? 'flex' : 'none';
                }
            } else {
                const notification = document.getElementById('notification');
                if (notification) {
                    notification.textContent = data.message || 'Échec de l\'ajout au panier';
                    notification.classList.add('show', 'error');
                    setTimeout(() => notification.classList.remove('show', 'error'), 3000);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const notification = document.getElementById('notification');
            if (notification) {
                notification.textContent = 'Erreur lors de l\'ajout au panier';
                notification.classList.add('show', 'error');
                setTimeout(() => notification.classList.remove('show', 'error'), 3000);
            }
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>