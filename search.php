<?php 
declare(strict_types=1); 
session_start();
// Connexion à la base de données
try {
    $conn = new PDO("mysql:host=localhost;dbname=multi_vendeurs", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("<h3 style='color:red'>Erreur de connexion à la base de données :</h3><p>".$e->getMessage()."</p>");
}

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchTerm = '%' . trim($_GET['query']) . '%';
    
    try {
        $stmt = $conn->prepare("
            SELECT p.id, p.nom, p.description, p.prix, p.image, u.nom AS vendeur_nom, 'produits' AS source 
            FROM produits p
            JOIN utilisateurs u ON p.vendeur_id = u.id
            WHERE (p.nom LIKE ? OR p.description LIKE ? OR p.marque LIKE ?)
            UNION ALL
            SELECT p2.id, p2.nom, p2.description, p2.prix, p2.image, NULL AS vendeur_nom, 'produits2' AS source 
            FROM produits2 p2
            WHERE (p2.nom LIKE ? OR p2.description LIKE ? OR p2.marque LIKE ?)
            UNION ALL
            SELECT prm.id, prm.nom, prm.description, prm.prix, prm.image, NULL AS vendeur_nom, 'promotions' AS source 
            FROM promotions prm
            WHERE (prm.nom LIKE ? OR prm.description LIKE ? OR prm.marque LIKE ?)
        ");
        $params = array_fill(0, 9, $searchTerm);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Résultats de recherche</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f7;
                    padding: 20px;
                }

                .search-header {
                    background-color: #0066cc;
                    color: white;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    text-align: center;
                }

                .products-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                    gap: 15px;
                    padding: 10px;
                }

                .product-card {
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                }

                .product-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }

                .product-image-container {
                    position: relative;
                    width: 100%;
                    height: 180px;
                    overflow: hidden;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background: transparent;
                    padding: 0;
                    margin: 0;
                }

                .product-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                    transition: transform 0.6s ease;
                }

                .product-info {
                    padding: 12px;
                }
                .product-info h3 {
                    font-size: 16px;
                    margin: 0 0 5px 0; /* réduire la marge basse */
                    color: #333;
                    height: 40px;
                    overflow: hidden;
                }
                .product-info p {
                    font-size: 14px;
                    color: #666;
                    margin: 3px 0; /* réduire la marge */
                    height: 36px; /* réduire un peu la hauteur */
                    overflow: hidden;
                }
                .price {
                    font-weight: bold;
                    color: #d9534f;
                    font-size: 16px;
                    margin: 8px 0; /* réduire la marge */
                }
                .seller {
                    font-size: 12px;
                    color: #888;
                    margin: 3px 0; /* réduire la marge */
                }
                .buy-btn {
                    background:rgb(40, 68, 167);
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    border-radius: 4px;
                    cursor: pointer;
                    width: 100%;
                    font-size: 14px;
                    transition: background 0.3s ease;
                    margin-top: 0px; /* réduire la marge haute du bouton */
                }

                .buy-btn:hover {
                    background:rgb(37, 135, 210);
                }

                .no-results {
                    grid-column: 1/-1;
                    text-align: center;
                    padding: 40px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }

                .no-results a {
                    color: #0066cc;
                    text-decoration: none;
                }

                .no-results a:hover {
                    text-decoration: underline;
                }

                @media (max-width: 768px) {
                    .products-container {
                        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    }
                    .product-image-container {
                        height: 140px;
                    }
                    .product-info h3 {
                        font-size: 14px;
                        height: 36px;
                    }
                    .product-info p {
                        font-size: 12px;
                        height: 36px;
                    }
                }




                /*dark*/
                /* متغيرات الألوان للوضع الليلي والنهاري */
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
    --price-color: #d9534f; /* لون السعر في الوضع الفاتح */
    --text-color: #333; /* لون النص الرئيسي في الوضع الفاتح */
    --secondary-text: #666; /* لون النص الثانوي في الوضع الفاتح */
}

[data-theme="dark"] {
    --primary-dark: #121212;
    --primary-dark-light: #1e1e1e;
    --light-bg: #121212;
    --pure-white: #ffffff;
    --text-dark: #f1f1f1;
    --text-light: #a0a0a0;
    --price-color: #ff6b6b; /* لون السعر في الوضع الداكن */
    --text-color: #f1f1f1; /* لون النص الرئيسي في الوضع الداكن */
    --secondary-text: #cccccc; /* لون النص الثانوي في الوضع الداكن */
}

/* تطبيق الألوان على العناصر */
.product-info h3 {
    color: var(--text-color);
}

.product-info p {
    color: var(--secondary-text);
}

.price {
    color: var(--price-color);
}

.seller {
    color: var(--secondary-text);
}

.buy-btn {
    background: var(--accent-blue);
    color: white;
}

.buy-btn:hover {
    background: #0052a3;
}

/* تحسينات إضافية للوضع الداكن */
[data-theme="dark"] .product-card {
    background-color: var(--primary-dark-light);
    border: 1px solid #333;
}

[data-theme="dark"] .product-image-container {
    background-color: rgba(255,255,255,0.05);
}

[data-theme="dark"] .search-header {
    background-color: #004080;
}

[data-theme="dark"] .no-results {
    background-color: var(--primary-dark-light);
    color: var(--text-dark);
}

[data-theme="dark"] .no-results a {
    color: #4da6ff;
}
    
    
            </style>
        </head>
        <body>
            <?php include 'navbar.php'; ?>
            
            <div class="search-header">
                <h2>Résultats de recherche pour : "<?php echo htmlspecialchars(trim($_GET['query'])); ?>"</h2>
                <a href="used_products.php" style="color:white;">Afficher tous les produits</a>
            </div>
            
            <div class="products-container">
                <?php if (count($results) > 0): ?>
                    <?php foreach ($results as $product): ?>
                        <div class="product-card">
                            <a href="product_details.php?id=<?php echo $product['id']; ?>&source=<?php echo $product['source']; ?>" style="text-decoration:none;color:inherit;">
                                <div class="product-image-container">
                                    <img class="product-image" src="image/<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'default-product.jpg'; ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
                                </div>
                                <div class="product-info">
                                    <div>
                                        <h3><?php echo htmlspecialchars($product['nom']); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                                        <p class="price"><?php echo htmlspecialchars($product['prix']); ?> DH</p>
                                        <?php if ($product['vendeur_nom']): ?>
                                            <p class="seller">Vendeur : <?php echo htmlspecialchars($product['vendeur_nom']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <form action="add_to_cart_from_search.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="source" value="<?php echo $product['source']; ?>">
                                        <button type="submit" class="buy-btn">
                                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                                        </button>
                                    </form>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results">
                        <p>Aucun résultat correspondant à votre recherche</p>
                        <a href="used_products.php">Afficher tous les produits</a>
                    </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    } catch(PDOException $e) {
        echo '<div style="color:red;padding:20px;background:#ffeeee;border-radius:8px;">
                <h3>Erreur lors de la recherche</h3>
                <p>'.htmlspecialchars($e->getMessage()).'</p>
              </div>';
    }
} else {
    header("Location: used_products.php");
    exit();
}
?> 
