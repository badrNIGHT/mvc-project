<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès Vendeur Requis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6bff; /* اللون الأزرق الأساسي */
            --secondary-color: #6c757d; /* اللون الرمادي */
            --dark-color: #212529; /* اللون الأسود الداكن */
            --light-color: #f8f9fa; /* اللون الأبيض الفاتح */
            --danger-color: #dc3545; /* اللون الأحمر للتنبيهات */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .access-container {
            max-width: 800px;
            margin: auto;
            padding: 40px 20px;
            text-align: center;
            flex: 1;
        }
        
        .access-icon {
            font-size: 5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .access-title {
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: var(--dark-color);
        }
        
        .access-message {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: var(--secondary-color);
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a56ff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(74, 107, 255, 0.3);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: rgba(74, 107, 255, 0.1);
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .access-title {
                font-size: 1.8rem;
            }
            
            .access-message {
                font-size: 1rem;
            }
            
            .buttons-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="access-container">
        <div class="access-icon">
            <i class="fas fa-store-alt"></i>
        </div>
        
        <h1 class="access-title">Accès Réservé aux Vendeurs</h1>
        
        <p class="access-message">
            Pour accéder à cette page et vendre vos produits sur notre plateforme, 
            vous devez avoir le rôle de vendeur. Si vous souhaitez devenir vendeur, 
            vous pouvez modifier votre rôle dans les paramètres de votre compte.
        </p>
        
        <div class="buttons-container">
            <a href="homepage.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
            
            <a href="account.php?action=change_role" class="btn btn-outline">
                <i class="fas fa-user-cog"></i> Devenir vendeur
            </a>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>