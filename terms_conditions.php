<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conditions Générales d’Utilisation - Shuriken Phone</title>
    <style>
        body {
            background: linear-gradient(135deg, #0f1113, #1d1f21, #2c3e50);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #f5f5f7;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            margin: 40px;
        }

        h1 {
            color: #48aaff;
            margin-bottom: 20px;
            font-size: 28px;
        }

        p {
            line-height: 1.8;
            margin-bottom: 16px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #cccccc;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            background-color: #1d1d1f;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .back-link a:hover {
            background-color: #0066cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Conditions Générales d’Utilisation</h1>
        <p>Bienvenue sur <strong>Shuriken Phone</strong>. En utilisant ce site, vous acceptez nos conditions d'utilisation décrites ci-dessous.</p>
        <p>Toutes les informations, textes, images et produits présents sur ce site sont la propriété de Shuriken Phone, sauf mention contraire.</p>
        <p>Vous vous engagez à ne pas utiliser ce site à des fins frauduleuses ou illégales, et à respecter les lois en vigueur.</p>
        <p>Shuriken Phone se réserve le droit de modifier ces conditions à tout moment sans préavis.</p>
        <p>Ce projet est un travail scolaire réalisé sous la supervision de M. <strong>Oussama</strong>, par <strong>Badr Zouhri</strong> et <strong>Adnane Es-Saady</strong>.</p>

        <footer>
            &copy; 2025 Shuriken Phone. Tous droits réservés.
        </footer>

        <div class="back-link">
            <a href="login.php">⬅ Retour à la page d’accueil</a>
        </div>
    </div>
</body>
</html>
