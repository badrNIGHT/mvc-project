<?php
declare(strict_types=1);
session_start();

if (!isset($_GET['commande_id'])) {
    header('Location: index.php');
    exit();
}

$commande_id = (int)$_GET['commande_id'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - Shuriken Phone Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #ffffff;
            --primary-text: #1d1d1f;
            --secondary-text: #86868b;
            --accent-blue: #0066cc;
            --light-gray: #f5f5f7;
            --border-color: #d2d2d7;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --success-color: #28a745;
        }

        /* Dark Mode Variables */
        [data-theme="dark"] {
            --primary-bg: #1e1e1e;
            --primary-text: #f1f1f1;
            --secondary-text: #aaaaaa;
            --light-gray: #2d2d2d;
            --border-color: #444444;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            --accent-blue: #3a8cff;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-gray);
            color: var(--primary-text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .thank-you-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .thank-you-container {
            max-width: 700px;
            width: 100%;
            margin: 2rem auto;
            text-align: center;
            padding: 4rem 3rem;
            background: var(--primary-bg);
            border-radius: 20px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .thank-you-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--accent-blue), var(--primary-text));
        }
        
        .thank-you-icon {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .thank-you-icon .circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 119, 182, 0.1);
            animation: pulse 2s infinite;
        }
        
        .thank-you-icon .check {
            font-size: 3.5rem;
            color: var(--accent-blue);
            z-index: 2;
            animation: checkIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        h1 {
            color: var(--primary-text);
            margin-bottom: 1.5rem;
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .order-number {
            font-size: 1.6rem;
            color: var(--accent-blue);
            font-weight: 700;
            margin: 2rem 0;
            display: inline-block;
            padding: 0.8rem 2rem;
            background: rgba(0, 119, 182, 0.08);
            border-radius: 12px;
            border: 1px dashed rgba(0, 119, 182, 0.3);
        }
        
        .confirmation-message {
            font-size: 1.15rem;
            margin-bottom: 2.5rem;
            color: var(--secondary-text);
            line-height: 1.8;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .confirmation-message strong {
            color: var(--primary-text);
            font-weight: 600;
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 32px;
            background: var(--accent-blue);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            gap: 8px;
        }
        
        .btn-primary:hover {
            background: #004d99;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary i {
            transition: transform 0.3s ease;
        }
        
        .btn-primary:hover i {
            transform: translateX(-3px);
        }
        
        .order-details {
            margin-top: 3rem;
            padding-top: 2.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .order-details p {
            display: flex;
            justify-content: space-between;
            max-width: 450px;
            margin: 1rem auto;
            padding: 0 1rem;
        }
        
        .order-details span:first-child {
            color: var(--secondary-text);
            font-weight: 500;
        }
        
        .order-details span:last-child {
            color: var(--primary-text);
            font-weight: 600;
        }
        
        .support-text {
            margin-top: 3rem;
            font-size: 0.95rem;
            color: var(--secondary-text);
        }
        
        .support-text a {
            color: var(--accent-blue);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .support-text a:hover {
            color: var(--primary-text);
            text-decoration: underline;
        }
        
        /* Animations */
        @keyframes checkIn {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .thank-you-container {
                padding: 3rem 2rem;
                margin: 1rem;
                border-radius: 16px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .order-number {
                font-size: 1.4rem;
                padding: 0.6rem 1.5rem;
            }
            
            .confirmation-message {
                font-size: 1.05rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="thank-you-wrapper">
        <div class="thank-you-container" style="animation: fadeIn 0.6s ease-out;">
            <div class="thank-you-icon">
                <div class="circle"></div>
                <i class="fas fa-check check"></i>
            </div>
            
            <h1>Merci pour votre confiance !</h1>
            
            <div class="order-number">Commande #<?= $commande_id ?></div>
            
            <div class="confirmation-message">
                <p>Votre commande a été confirmée et est en cours de préparation.</p>
                <p>Un email de confirmation a été envoyé à <strong><?= htmlspecialchars($_SESSION['user_email'] ?? 'votre adresse email') ?></strong> avec tous les détails.</p>
            </div>
            
            <div class="order-details">
                <p><span>Référence:</span> <span>#<?= $commande_id ?></span></p>
                <p><span>Date:</span> <span><?= date('d/m/Y à H:i') ?></span></p>
                <p><span>Statut:</span> <span>En préparation</span></p>
            </div>
            
            <a href="used_products.php" class="btn-primary">
                <i class="fas fa-arrow-left"></i> Retour à la boutique
            </a>
            
            <p class="support-text">
                Besoin d'aide ? <a href="contact.php">Contactez notre service client</a>
            </p>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Animation séquentielle des éléments
            const elements = [
                '.thank-you-icon',
                'h1',
                '.order-number',
                '.confirmation-message',
                '.order-details',
                '.btn-primary',
                '.support-text'
            ];
            
            elements.forEach((el, index) => {
                const element = document.querySelector(el);
                if (element) {
                    element.style.opacity = '0';
                    element.style.animation = `fadeIn 0.5s ease-out ${index * 0.15 + 0.3}s forwards`;
                }
            });
        });
    </script>
</body>
</html>