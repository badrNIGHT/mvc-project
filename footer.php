<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Fix</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .footer {
            background: #f5f5f7;
            color: var(--text-dark);
            padding: 60px 0 30px;
            margin-top: 80px;
            border-top: 1px solid #d2d2d7;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }

        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
            color: var(--text-dark);
        }

        .footer-column h3::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background: var(--text-dark);
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li {
            margin-bottom: 10px;
            padding-left: 0;
        }

        .footer-column ul li a {
            color: var(--text-light);
            text-decoration: none;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-column ul li a:hover {
            color: var(--accent-blue);
            transform: translateX(5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid #d2d2d7;
        }

        .social-icons {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .social-icons a {
            color: var(--text-dark);
            font-size: 1.5rem;
            transition: 0.3s;
        }

        .social-icons a:hover {
            color: var(--accent-blue);
            transform: translateY(-5px);
        }

        .payment-methods {
            margin-top: 30px;
        }

        .payment-methods img {
            height: 30px;
            margin: 0 5px;
            transition: 0.3s;
        }

        .payment-methods img:hover {
            filter: grayscale(0) brightness(1);
        }

        .newsletter-form {
            display: flex;
            margin-top: 15px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #d2d2d7;
            border-radius: 25px 0 0 25px;
            background: var(--primary-dark);
            color: var(--text-dark);
            outline: none;
        }

        .newsletter-form button {
            background: var(--text-dark);
            color: var(--primary-dark);
            border: none;
            padding: 0 20px;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            transition: 0.3s;
        }

        .newsletter-form button:hover {
            background: var(--accent-blue);
            color: var(--pure-white);
        }

        .model-info-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .slider-card:hover .model-info-overlay {
            opacity: 1;
        }

        .model-info-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }

        .model-info-details {
            font-size: 0.9rem;
            line-height: 1.4;
            opacity: 0.9;
        }

        .slider-card {
            position: relative;
            overflow: hidden;
        }
        [data-theme="dark"] .footer {
    background: #1e1e1e;
    border-top-color: #333;
}

[data-theme="dark"] .footer-bottom {
    border-top-color: #333;
}
    </style>
</head>
<body>

<!-- تذييل الصفحة -->
<footer class="footer">
    <div class="footer-container">
        <!-- العمود الأول -->
        <div class="footer-column">
            <h3>Aide & Contact</h3>
            <ul>
                <li><a href="contact.php"><i class="fas fa-phone-alt"></i> +212 656-704536</a></li>
                <li><a href="contact.php"><i class="fas fa-phone-alt"></i> +212 624-017026</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> ShurikenPhone.com</a></li>
                <li><a href="contact.php"><i class="fas fa-map-marker-alt"></i> IFMOTICA, FES, MAROC</a></li>
                <li><a href="contact.php"><i class="fas fa-headset"></i> Service client</a></li>
            </ul>
        </div>

        <!-- العمود الثاني -->
        <div class="footer-column">
            <h3>Informations</h3>
            <ul>
                <li><a href="A_propos_de_nous.php">À propos de nous</a></li>
                <li><a href="livraison_et_retour.php">Livraison & Retour</a></li>
                <li><a href="Paiement_securise.php">Paiement sécurisé</a></li>
                <li><a href="Garantie_produits.php">Garantie produits</a></li>
            </ul>
        </div>

        <!-- العمود الثالث -->
        <div class="footer-column">
            <h3>Mon Compte</h3>
            <ul>
                <li><a href="account.php">Mon profil</a></li>
                <li><a href="cart.php">Mes commandes</a></li>
                <li><a href="evaluer_telephone.php">Avis sur le telephones</a></li>
            </ul>
        </div>

        <!-- العمود الرابع -->
        <div class="footer-column">
            <h3>Newsletter</h3>
            <p>Abonnez-vous pour recevoir nos offres spéciales</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Votre email" required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>

            <div class="social-icons">
                <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="payment-methods">
            <img src="image/visa awldi-Photoroom.png" alt="Visa">
            <img src="image/WhatsApp Image 2025-05-02 at 3.56.27 PM (1)-Photoroom.png" alt="cih">
            <img src="image/master card asahbi-Photoroom.png" alt="Mastercard">
            <img src="image/cha3bi l3aziz-Photoroom.png" alt="chaabi">
        </div>
        <p>&copy; SHURIKEN PHONE STORE</p>
    </div>
</footer>

</body>
</html>