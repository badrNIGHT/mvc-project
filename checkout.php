<?php
declare(strict_types=1);
session_start();

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Votre panier est vide";
    header('Location: cart.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';
    
    try {
        $conn->begin_transaction();
        
        // Validate and sanitize inputs
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $adresse = htmlspecialchars(trim($_POST['adresse'] ?? ''));
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
        $payment_method = in_array($_POST['payment_method'] ?? '', ['carte', 'paypal', 'livraison']) 
            ? $_POST['payment_method'] 
            : 'carte';
        
        if (!$email || empty($nom) || empty($adresse) || empty($telephone)) {
            throw new Exception("Veuillez remplir tous les champs correctement");
        }
        
        // Calculate total and prepare cart items
        $total = 0;
        $cart_items = [];
        foreach ($_SESSION['cart'] as $item) {
            if (!isset($item['name'], $item['price'], $item['quantity'])) {
                continue;
            }
            $price = (float)$item['price'];
            $quantity = (int)$item['quantity'];
            $subtotal = $price * $quantity;
            $total += $subtotal;
            
            $cart_items[] = [
                'name' => htmlspecialchars($item['name']),
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
        
        if ($total <= 0) {
            throw new Exception("Votre panier contient des articles invalides");
        }
        
        // 1. Create main order
        $stmt = $conn->prepare("
            INSERT INTO commandes (
                client_id, nom_client, email, adresse, telephone,
                montant_total, methode_paiement, produits, statut
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en_attente')
        ");
        
        $produits_json = json_encode($cart_items);
        
        if (empty($_SESSION['user_id'])) {
            throw new Exception("Vous devez être connecté pour passer une commande.");
        }
        $client_id = (int)$_SESSION['user_id'];
        
        $stmt->bind_param(
            "issssdss",
            $client_id,
            $nom,
            $email,
            $adresse,
            $telephone,
            $total,
            $payment_method,
            $produits_json
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'enregistrement de la commande");
        }
        
        $commande_id = $conn->insert_id;
        
        // 2. Add order details (without produit_id)
        $details_table_exists = $conn->query("SHOW TABLES LIKE 'details_commande'")->num_rows > 0;
        if ($details_table_exists) {
            $stmt = $conn->prepare("
                INSERT INTO details_commande (
                    commande_id, nom_produit, prix_unitaire, quantite, sous_total
                ) VALUES (?, ?, ?, ?, ?)
            ");
            
            foreach ($cart_items as $item) {
                $stmt->bind_param(
                    "isdid",
                    $commande_id,
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['subtotal']
                );
                if (!$stmt->execute()) {
                    throw new Exception("Erreur lors de l'enregistrement des détails de commande");
                }
            }
        }
        
        $conn->commit();
        
        // Clear cart after successful order
        unset($_SESSION['cart']);
        $_SESSION['cart_count'] = 0;
        
        // Redirect to thank you page
        header('Location: merci.php?commande_id=' . $commande_id);
        exit();
        
    } catch (Exception $e) {
        if (isset($conn) && method_exists($conn, 'rollback')) {
            $conn->rollback();
        }
        $_SESSION['error'] = $e->getMessage();
        error_log("Checkout Error: " . $e->getMessage());
    }
}

function formatPrice(float $price): string {
    return number_format($price, 2, '.', ' ') . ' DH';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Shuriken Phone Store</title>
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
            --success-color: #28a745;
            --error-color: #dc3545;
        }

        /* Dark Mode Variables */
        [data-theme="dark"] {
            --primary-bg: #1e1e1e;
            --primary-text: #f1f1f1;
            --secondary-text: #aaaaaa;
            --light-gray: #2d2d2d;
            --border-color: #444444;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            --accent-blue: #3a8cff;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--light-gray);
            color: var(--primary-text);
            line-height: 1.6;
            padding-top: 120px;
            margin: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 0 20px;
        }

        .checkout-form, .order-summary {
            background: var(--primary-bg);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--primary-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-text);
        }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            background-color: var(--primary-bg);
            color: var(--primary-text);
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .payment-methods {
            margin-top: 2rem;
        }

        .payment-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            background-color: var(--primary-bg);
        }

        .payment-card:hover {
            border-color: var(--accent-blue);
            transform: translateY(-2px);
        }

        .payment-card.selected {
            border: 2px solid var(--accent-blue);
            background-color: rgba(0, 102, 204, 0.05);
        }

        .payment-card i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
            color: var(--accent-blue);
        }

        .card-options {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--border-color);
        }

        .payment-card.selected .card-options {
            display: block;
        }

        .card-option {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
        }

        .card-option input {
            width: auto;
            margin-right: 0.5rem;
        }

        .card-option label {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            cursor: pointer;
            color: var(--primary-text);
        }

        .card-option img {
            width: 40px;
            margin-left: 0.5rem;
            filter: [data-theme="dark"] ? invert(1) : none;
        }

        .btn-submit {
            background-color: var(--accent-blue);
            color: white;
            border: none;
            padding: 1rem;
            width: 100%;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background-color: #004d99;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.2rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .order-item h3 {
            margin: 0 0 0.3rem 0;
            font-size: 1.1rem;
            color: var(--primary-text);
        }

        .order-item p {
            margin: 0;
            color: var(--secondary-text);
            font-size: 0.9rem;
        }

        .order-total {
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            text-align: right;
            color: var(--primary-text);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 90px;
            }
            .checkout-container {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 15px;
            }
            .checkout-form, .order-summary {
                padding: 1.5rem;
            }
            .section-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="checkout-container">
        <form class="checkout-form" method="POST" action="checkout.php">
            <h2 class="section-title"><i class="fas fa-user"></i> Informations personnelles</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nom">Nom complet *</label>
                <input type="text" id="nom" name="nom" required
                       value="<?= htmlspecialchars($_SESSION['user_nom'] ?? $_POST['nom'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required
                       value="<?= htmlspecialchars($_SESSION['user_email'] ?? $_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone *</label>
                <input type="tel" id="telephone" name="telephone" required
                       value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse de livraison *</label>
                <textarea id="adresse" name="adresse" rows="4" required><?= 
                    htmlspecialchars($_POST['adresse'] ?? '') 
                ?></textarea>
            </div>
            
            <h2 class="section-title"><i class="fas fa-credit-card"></i> Méthode de paiement</h2>
            
            <div class="payment-methods">
                <div class="payment-card" onclick="selectPayment('carte')">
                    <input type="radio" name="payment_method" id="carte" value="carte" checked hidden>
                    <label for="carte" style="cursor: pointer; display: flex; align-items: center;">
                        <i class="fas fa-credit-card"></i> Carte bancaire
                    </label>
                    
                    <div class="card-options">
                        <div class="card-option">
                            <input type="radio" name="card_type" id="visa" value="visa" checked>
                            <label for="visa">
                                Visa
                                <img src="image/visa awldi-Photoroom.png" alt="Visa" class="payment-logo">
                            </label>
                        </div>
                        <div class="card-option">
                            <input type="radio" name="card_type" id="mastercard" value="mastercard">
                            <label for="mastercard">
                                Mastercard
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="payment-logo">
                            </label>
                        </div>
                        <div class="card-option">
                            <input type="radio" name="card_type" id="cih" value="cih">
                            <label for="cih">
                                CIH Bank
                                <img src="image/WhatsApp Image 2025-05-02 at 3.56.27 PM (1)-Photoroom.png" alt="CIH Bank" class="payment-logo">
                            </label>
                        </div>
                        <div class="card-option">
                            <input type="radio" name="card_type" id="chaabi" value="chaabi">
                            <label for="chaabi">
                                Chaabi Bank
                                <img src="image/cha3bi l3aziz-Photoroom.png" alt="Chaabi Bank" class="payment-logo">
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="payment-card" onclick="selectPayment('paypal')">
                    <input type="radio" name="payment_method" id="paypal" value="paypal" hidden>
                    <label for="paypal" style="cursor: pointer; display: flex; align-items: center;">
                        <i class="fab fa-paypal"></i> PayPal
                    </label>
                </div>
                
                <div class="payment-card" onclick="selectPayment('livraison')">
                    <input type="radio" name="payment_method" id="livraison" value="livraison" hidden>
                    <label for="livraison" style="cursor: pointer; display: flex; align-items: center;">
                        <i class="fas fa-money-bill-wave"></i> Paiement à la livraison
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-lock"></i> Confirmer le paiement
            </button>
        </form>
        
        <div class="order-summary">
            <h2 class="section-title"><i class="fas fa-receipt"></i> Récapitulatif</h2>
            
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <?php if (isset($item['name'], $item['price'], $item['quantity'])): ?>
                    <div class="order-item">
                        <div>
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p>Quantité: <?= (int)$item['quantity'] ?></p>
                        </div>
                        <div>
                            <p><?= formatPrice((float)$item['price'] * (int)$item['quantity']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <div class="order-total">
                Total: <?= formatPrice(array_reduce($_SESSION['cart'], function($total, $item) {
                    if (!isset($item['price'], $item['quantity'])) return $total;
                    return $total + ((float)$item['price'] * (int)$item['quantity']);
                }, 0)) ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        function selectPayment(method) {
            document.querySelectorAll('.payment-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            const selectedCard = document.getElementById(method).closest('.payment-card');
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            selectPayment('carte');
            
            // Apply dark mode to payment logos if needed
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                document.querySelectorAll('.payment-logo').forEach(logo => {
                    logo.style.filter = 'invert(1)';
                });
            }
        });
    </script>
</body>
</html>