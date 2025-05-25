<?php
declare(strict_types=1);
session_start();
include 'config.php';

// User ID check
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: login.php');
    exit();
}

// Get cart contents from panier table
$sql = "SELECT id, produit_id, nom_produit AS nom, description, marque, prix, quantite, image, stockage, ram 
        FROM panier 
        WHERE utilisateur_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$cartCount = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = [
        'id' => (int)$row['id'],
        'produit_id' => (int)$row['produit_id'],
        'name' => $row['nom'],
        'description' => $row['description'],
        'marque' => $row['marque'],
        'price' => (float)$row['prix'],
        'quantity' => (int)$row['quantite'],
        'image' => $row['image'],
        'stockage' => $row['stockage'],
        'ram' => $row['ram']
    ];
    $cartCount += (int)$row['quantite'];
}

$stmt->close();

// Update session
$_SESSION['cart'] = $cartItems;
$_SESSION['cart_count'] = $cartCount;

// Handle product removal
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $item_removed = false;

    // Remove from session
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['produit_id'] === $product_id) {
            unset($_SESSION['cart'][$key]);
            $item_removed = true;
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));

    // Remove from database
    if ($item_removed) {
        $deleteStmt = $conn->prepare("DELETE FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
        $deleteStmt->bind_param("ii", $userId, $product_id);
        if ($deleteStmt->execute()) {
            $_SESSION['message'] = "Le produit a été supprimé du panier";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression dans la base de données";
        }
        $deleteStmt->close();
    } else {
        $_SESSION['error'] = "Produit non trouvé dans le panier";
    }

    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Shuriken Phone Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       :root {
            --primary-bg: #ffffff;
            --primary-text: #1d1d1f;
            --secondary-text: #86868b;
            --accent-blue: #0066cc;
            --light-gray: #f5f5f7;
            --border-color: #d2d2d7;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* Dark Mode Variables */
        [data-theme="dark"] {
            --primary-bg: #1e1e1e;
            --primary-text: #f1f1f1;
            --secondary-text: #aaaaaa;
            --light-gray: #2d2d2d;
            --border-color: #444444;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--light-gray);
            color: var(--primary-text);
            line-height: 1.6;
            padding-top: 120px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .cart-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--primary-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-header h1 {
            font-size: 1.8rem;
            color: var(--primary-text);
        }

        .cart-header h1 i {
            color: var(--accent-blue);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: var(--primary-bg);
            color: var(--primary-text);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            background-color: var(--light-gray);
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
            color: var(--primary-text);
        }

        .item-details p {
            margin: 0.3rem 0;
            color: var(--secondary-text);
        }

        .item-actions a {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .item-actions a:hover {
            color: #c82333;
        }

        .item-actions i {
            margin-right: 0.3rem;
        }

        .cart-summary {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .cart-total {
            font-size: 1.3rem;
            font-weight: 600;
            text-align: right;
            margin-bottom: 1.5rem;
            color: var(--primary-text);
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--accent-blue);
            color: white;
        }

        .btn-primary:hover {
            background-color: #004d99;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
        }

        .btn-secondary {
            background-color: var(--light-gray);
            color: var(--primary-text);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: var(--border-color);
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            background: var(--primary-bg);
            border-radius: 8px;
        }

        .empty-cart p {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: var(--primary-text);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 90px;
            }
            
            .cart-container {
                padding: 1rem;
                margin: 1rem;
            }
            
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .item-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .item-image {
                width: 60px;
                height: 60px;
            }
            
            .item-actions {
                margin-top: 1rem;
                align-self: flex-end;
            }
            
            .cart-actions {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="cart-container">
        <div class="cart-header">
            <h1><i class="fas fa-shopping-cart"></i> Votre Panier</h1>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['cart'])): ?>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $item): 
                $price = isset($item['price']) && is_numeric($item['price']) ? (float)$item['price'] : 0;
                $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
                $subtotal = $price * $quantity;
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <div class="item-info">
                        
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['name'] ?? '') ?></h3>
                            <?php if (!empty($item['marque'])): ?>
                                <p>Marque: <?= htmlspecialchars($item['marque']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($item['stockage'])): ?>
                                <p>Stockage: <?= htmlspecialchars($item['stockage']) ?>GB</p>
                            <?php endif; ?>
                            <?php if (!empty($item['ram'])): ?>
                                <p>RAM: <?= htmlspecialchars($item['ram']) ?>GB</p>
                            <?php endif; ?>
                            <p>Prix unitaire: <?= number_format($price, 2) ?> DH</p>
                            <p>Quantité: <?= $quantity ?></p>
                            <p>Sous-total: <?= number_format($subtotal, 2) ?> DH</p>
                        </div>
                    </div>
                    <div class="item-actions">
                        <a href="cart.php?id=<?= $item['produit_id'] ?>" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="cart-summary">
                <div class="cart-total">
                    Total: <?= number_format($total, 2) ?> DH
                </div>
                
                <div class="cart-actions">
                    <a href="used_products.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Continuer vos achats
                    </a>
                    <a href="checkout.php" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i> Passer la commande
                    </a>
                </div>
            </div>
            
        <?php else: ?>
            <div class="empty-cart">
                <p>Votre panier est vide</p>
                <a href="used_products.php" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Commencer vos achats
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>

  
</body>
</html>