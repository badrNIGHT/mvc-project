<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPPO - Shuriken Phone Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        [data-theme="dark"] .top-bar,
        [data-theme="dark"] .navbar,
        [data-theme="dark"] .product-card,
        [data-theme="dark"] .dropdown-menu.vertical,
        [data-theme="dark"] .search-input {
            background-color: var(--primary-dark-light);
            border-color: #333;
        }

        [data-theme="dark"] .footer {
            background: #1e1e1e;
            border-top-color: #333;
        }

        [data-theme="dark"] .footer-bottom {
            border-top-color: #333;
        }

        [data-theme="dark"] .nav-links a:hover,
        [data-theme="dark"] .dropdown-menu.vertical a:hover {
            background: rgba(255,255,255,0.05);
        }

        [data-theme="dark"] .search-input {
            background: #1e1e1e;
            color: #f1f1f1;
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
            padding-top: 120px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Top Bar Styles */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--primary-dark);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1002;
            border-bottom: 1px solid #d2d2d7;
            transition: background-color 0.3s ease;
        }

        .logo-container {
            display: flex;
            align-items: center;
            position: relative;
            height: 50px;
            width: auto;
        }

        .logo-light, .logo-dark {
            height: 100%;
            width: auto;
            max-width: 120px;
            transition: opacity 0.3s ease;
            object-fit: contain;
        }

        .logo-dark {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            transform: scale(0.8);
        }

        [data-theme="dark"] .logo-light {
            opacity: 0;
        }

        [data-theme="dark"] .logo-dark {
            opacity: 1;
            transform: scale(1);
        }

        .dark-mode-wrapper {
            margin-left: 15px;
            display: flex;
            align-items: center;
        }

        .dark-mode-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .dark-mode-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .dark-mode-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d2d2d7;
            transition: .4s;
            border-radius: 34px;
        }

        .dark-mode-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            z-index: 2;
        }

        .dark-mode-slider .sun-icon {
            position: absolute;
            left: 8px;
            top: 6px;
            color: #333;
            font-size: 16px;
            z-index: 1;
        }

        .dark-mode-slider .moon-icon {
            position: absolute;
            right: 8px;
            top: 6px;
            color: #f0f0f0;
            font-size: 16px;
            z-index: 1;
        }

        input:checked + .dark-mode-slider {
            background-color: #555;
        }

        input:checked + .dark-mode-slider:before {
            transform: translateX(30px);
        }

        .search-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
        }

        .search-input {
            padding: 12px 25px;
            width: 100%;
            border: 1px solid #d2d2d7;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            background: var(--primary-dark-light);
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: var(--text-light);
        }

        .top-actions {
            position: absolute;
            right: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .cart-icon, .account-icon {
            font-size: 24px;
            color: var(--text-dark);
            cursor: pointer;
            transition: 0.3s;
        }

        .cart-icon:hover, .account-icon:hover {
            color: var(--accent-blue);
        }

        .cart-icon-container {
            position: relative;
            display: inline-block;
        }
        
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background: rgb(24, 27, 163);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Navbar Styles */
        .navbar {
            background: var(--primary-dark);
            padding: 1rem;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            z-index: 1001;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-bottom: 1px solid #d2d2d7;
            transition: background-color 0.3s ease;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a:hover {
            background: rgba(0,0,0,0.05);
            transform: translateY(-2px);
            color: var(--accent-blue);
        }

        .nav-links a i {
            font-size: 1.1em;
            width: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .nav-links a:hover i {
            transform: scale(1.1);
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-menu.vertical {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--primary-dark);
            width: 220px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            padding: 10px 0;
            transition: all 0.3s ease;
            z-index: 1003;
            border: 1px solid #d2d2d7;
        }

        .dropdown:hover .dropdown-menu.vertical {
            display: flex;
        }

        .dropdown-menu.vertical a {
            padding: 12px 20px;
            color: var(--text-dark);
            text-decoration: none;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .dropdown-menu.vertical a i {
            margin-right: 10px;
            color: var(--accent-blue);
        }

        .dropdown-menu.vertical a:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--accent-blue);
            font-weight: bold;
        }
        
        /* Video Header Section */
        .video-header {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .video-header video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        
        .video-header .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }
        
        .video-header .content {
            text-align: center;
            color: white;
            z-index: 1;
            padding: 20px;
        }
        
        .video-header h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .video-header p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        
        .scroll-down {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 2rem;
            animation: bounce 2s infinite;
            cursor: pointer;
            z-index: 1;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-20px) translateX(-50%);
            }
            60% {
                transform: translateY(-10px) translateX(-50%);
            }
        }
        
        /* Products Section - 4 Cards per Row */
        .products-section {
            padding: 80px 20px;
            background-color: var(--light-bg);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: var(--text-dark);
            position: relative;
        }
        
        .section-title:after {
            content: "";
            position: absolute;
            width: 80px;
            height: 3px;
            background-color: var(--accent-blue);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
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
        }
        
        .model-info-details {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .spec-item {
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
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
        
        /* Notification */
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
        
        /* Footer */
        .footer {
            background: var(--primary-dark-light);
            color: var(--text-dark);
            padding: 60px 0 30px;
            margin-top: 80px;
            border-top: 1px solid #d2d2d7;
            transition: background-color 0.3s ease;
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
        }

        .footer-column ul li {
            margin-bottom: 15px;
        }

        .footer-column ul li a {
            color: var(--text-light);
            text-decoration: none;
            transition: 0.3s;
            display: block;
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
            justify-content: center;
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
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .payment-methods img {
            height: 30px;
            transition: 0.3s;
        }

        .payment-methods img:hover {
            filter: grayscale(0) brightness(1);
        }

        /* Newsletter Form */
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

        /* Responsive Design */
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
            .top-bar {
                flex-direction: column;
                padding: 10px;
                height: auto;
            }
            
            .logo-container {
                margin-bottom: 10px;
            }
            
            .search-container {
                position: static;
                transform: none;
                width: 100%;
                margin: 10px 0;
            }
            
            .search-input {
                width: 100%;
            }
            
            .top-actions {
                margin-top: 10px;
                position: static;
            }
            
            .navbar {
                top: 120px;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 10px;
            }
            
            .dropdown-menu.vertical {
                position: static;
                width: 100%;
                box-shadow: none;
                border: none;
            }
            
            .video-header h1 {
                font-size: 2.5rem;
            }
            
            .video-header p {
                font-size: 1.2rem;
            }
            
            .products-container {
                grid-template-columns: 1fr;
            }
            
            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .footer-column h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .newsletter-form {
                max-width: 300px;
                margin: 15px auto 0;
            }
        }
    </style>
</head>
<body>
    <!-- الشريط العلوي مع زر الوضع الليلي -->
    <div class="top-bar">
        <div class="logo-container">
            <img src="image/shuriken logo-Photoroom.png" alt="Logo" class="logo-light">
            <img src="image/WhatsApp Image 2025-05-21 at 12.30.11 AM-Photoroom.png" alt="Logo" class="logo-dark">
            <div class="dark-mode-wrapper">
                <label class="dark-mode-switch">
                    <input type="checkbox" id="dark-mode-toggle">
                    <span class="dark-mode-slider round">
                        <i class="fas fa-sun sun-icon"></i>
                        <i class="fas fa-moon moon-icon"></i>
                    </span>
                </label>
            </div>
        </div>
        <div class="search-container">
            <form action="search.php" method="get">
                <input type="text" name="query" class="search-input" placeholder="Recherchez votre téléphone..." required>
                <button type="submit" style="display:none;"></button>
            </form>
        </div>
        <div class="top-actions">
            <div class="cart-icon-container">
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart cart-icon"></i>
                    <?php if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                        <span class="cart-count"><?php echo $_SESSION['cart_count']; ?></span>
                    <?php else: ?>
                        <span class="cart-count" style="display: none;">0</span>
                    <?php endif; ?>
                </a>
            </div>
            
            <a href="account.php" class="account-icon-link">
                <i class="fas fa-user-circle account-icon"></i>
            </a>
        </div>
    </div>
    
    <!-- النافبار -->
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homepage.php"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="dropdown">
                <a href="#android"><i class="fab fa-android"></i> Android</a>
                <div class="dropdown-menu vertical">
                    <a href="samsung.php"><i class="fas fa-mobile-alt"></i> Samsung</a>
                    <a href="RedMagic.php"><i class="fas fa-mobile-alt"></i> RedMagic</a>
                    <a href="Huawei.php"><i class="fas fa-mobile-alt"></i> Huawei</a>
                    <a href="Realme.php"><i class="fas fa-mobile-alt"></i> Realme</a>
                    <a href="Xiaomi.php"><i class="fas fa-mobile-alt"></i> Xiaomi</a>
                    <a href="asus.php"><i class="fas fa-mobile-alt"></i> Asus</a>
                    <a href="OnePlus.php"><i class="fas fa-mobile-alt"></i> OnePlus</a>
                    <a href="NothingPhone.php"><i class="fas fa-mobile-alt"></i> Nothing Phone</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#ios"><i class="fab fa-apple"></i> iOS</a>
                <div class="dropdown-menu vertical">
                    <a href="iphone.php"><i class="fas fa-mobile-alt"></i> iPhone</a>
                    <a href="ipad.php"><i class="fas fa-tablet-alt"></i> iPad</a>
                </div>
            </li>
            <li><a href="promotions.php"><i class="fas fa-tag"></i> Promotions</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
            <?php endif; ?>
        </ul>
    </nav>
    
    <!-- Section vidéo d'en-tête -->
    <header class="video-header">
        <video autoplay muted loop>
            <source src="video/vidio oppo.mp4" type="video/mp4">
            Votre navigateur ne supporte pas les vidéos.
        </video>
        <div class="overlay"></div>
        <div class="content">
            <h1>OPPO</h1>
            <p>Découvrez nos derniers modèles</p>
        </div>
        <div class="scroll-down" onclick="scrollToProducts()">↓</div>
    </header>
    
    <!-- Section des produits - 4 cartes par ligne -->
    <section class="products-section">
        <h2 class="section-title">Nos téléphones OPPO</h2>
        <div class="products-container" id="products-container">
            <!-- Les produits seront chargés ici via JavaScript -->
        </div>
    </section>
    
    <!-- Notification d'ajout au panier -->
    <div class="notification" id="notification">Produit ajouté au panier</div>
    
     <footer class="footer">
			            <div class="footer-container">
			                <!-- العمود الأول -->
			                <div class="footer-column">
			                    <h3>Aide & Contact</h3>
			                    <ul>
			                    <li><a href="contact.php"><i class="fas fa-phone-alt"></i> +212 656-704536</a></li>
			                    <li><a href="contact.php"><i class="fas fa-phone-alt"></i> +212 624-017026</a></li>
			                        <li><a href="contact.php"><i class="fas fa-envelope"></i> ShurikenPhone.com</a></li>
			                        <li><a href="contact.php"><i class="fas fa-map-marker-alt"></i> IFMOTICA , FES, MAROC</a></li>
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
			    
			                <!-- العمود الخامس -->
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

    <script>
        // Dark Mode Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const htmlElement = document.documentElement;
            
            function loadTheme() {
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                    htmlElement.setAttribute('data-theme', 'dark');
                    darkModeToggle.checked = true;
                } else {
                    htmlElement.setAttribute('data-theme', 'light');
                    darkModeToggle.checked = false;
                }
            }
            
            loadTheme();
            
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    htmlElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    htmlElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });
            
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    loadTheme();
                }
            });
        });

        // Load OPPO Products from Database
        function loadOppoProducts() {
            const container = document.getElementById('products-container');
            container.innerHTML = '<div class="loading" style="text-align:center; padding:20px; grid-column:1/-1">Chargement des produits...</div>';
            
            fetch('get_oppo_products.php')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(products => {
                    container.innerHTML = '';

                    if (!products || products.length === 0) {
                        container.innerHTML = '<div class="no-products" style="text-align:center; padding:20px; grid-column:1/-1">Aucun produit OPPO disponible pour le moment</div>';
                        return;
                    }

                    if (products.error) {
                        container.innerHTML = `<div class="error" style="text-align:center; padding:20px; grid-column:1/-1">${products.error}</div>`;
                        return;
                    }

                    products.forEach(product => {
                        const card = document.createElement('div');
                        card.className = 'product-card';

                        card.innerHTML = `
                            <div class="product-image-container">
                                <img src="${product.image}" alt="${product.nom}" class="product-image" onerror="this.src='image/default-phone.jpg'">
                                <div class="model-info-overlay">
                                    <div class="model-info-title">${product.nom}</div>
                                    <div class="model-info-details">
                                        <div class="spec-item"><i class="fas fa-hdd"></i> Stockage: ${product.stockage}GB</div>
                                        <div class="spec-item"><i class="fas fa-memory"></i> RAM: ${product.ram}GB</div>
                                        <div class="spec-item"><i class="fas fa-box"></i> Quantité: ${product.quantite}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">${product.nom}</h3>
                                <div class="product-price">${parseFloat(product.prix).toFixed(2)} DH</div>
                                <button class="add-to-cart" onclick="addToCart(${product.id}, '${product.nom.replace(/'/g, "\\'")}', ${product.prix})">
                                    <i class="fas fa-shopping-cart"></i> Ajouter au panier
                                </button>
                            </div>
                        `;

                        container.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    container.innerHTML = `
                        <div class="error" style="text-align:center; padding:20px; grid-column:1/-1">
                            Erreur de chargement des produits: ${error.message}
                            <button onclick="loadOppoProducts()" style="margin-top:10px; padding:5px 10px; background:#0066cc; color:white; border:none; border-radius:4px; cursor:pointer">Réessayer</button>
                        </div>
                    `;
                });
        }

        // Add to Cart Function
        function addToCart(productId, productName, productPrice) {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout en cours...';
            btn.disabled = true;

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&product_name=${encodeURIComponent(productName)}&product_price=${productPrice}`
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message);
                    updateCartCount(data.cartCount);
                } else {
                    showNotification(data.message || 'Échec de l\'ajout au panier', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showNotification('Erreur lors de l\'ajout au panier', 'error');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // Show Notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            if (!notification) return;

            notification.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Update Cart Count
        function updateCartCount(count) {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = count;
                cartCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Scroll to Products
        function scrollToProducts() {
            document.querySelector('.products-section')?.scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadOppoProducts();
        });
    </script>
</body>
</html>