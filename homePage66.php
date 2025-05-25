<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boutique de Téléphones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php
session_start();
include 'config.php';

$sql = "SELECT produits.*, utilisateurs.nom AS vendeur_nom 
        FROM produits 
        JOIN utilisateurs ON produits.vendeur_id = utilisateurs.id";
$result = $conn->query($sql);
?>

<div class="products-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-card">
            <img src="image/<?php echo $row['image']; ?>" alt="<?php echo $row['nom']; ?>">
            <div class="product-info">
                <h3><?php echo htmlspecialchars($row['nom']); ?></h3>
                <p>السعر: <?php echo $row['prix']; ?> د.م</p>
                <p>البائع: <?php echo htmlspecialchars($row['vendeur_nom']); ?></p>
            </div>
        </div>
    <?php endwhile; ?>
</div>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            padding-top: 120px;
        }

        /* شريط الأدوات العلوي */
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
        }

        .logo-container img {
            height: 50px;
            width: auto;
           
        }

        .search-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .search-input {
            padding: 12px 25px;
            width: 400px;
            border: 1px solid #d2d2d7;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            background: #f5f5f7;
            color: var(--text-dark);
        }

        .search-input::placeholder {
            color: var(--text-light);
        }

        .top-actions {
            display: flex;
            gap: 25px;
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
        .add-phone-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    margin-right: 0px;
    margin-left: 70px;
}

.add-button {
    background: none;
    border: none;
    font-size: 24px;
    color: var(--text-dark);
    cursor: pointer;
    padding: 5px 10px;
    transition: 0.3s;
    transform: translatex(20px);
}

.add-text {
    position: absolute;
    left: -140px;
    opacity: 0;
    transition: 0.3s;
    white-space: nowrap;
    font-size: 14px;
    background: rgba(255,255,255,0.9);
    padding: 5px 10px;
    border-radius: 5px;
    pointer-events: none;
}

.add-button:hover + .add-text {
    opacity: 1;
    transform: translateX(10px);
}
        /* النافبار */
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

        /* القائمة المنسدلة */
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

        .dropdown-menu.vertical a:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--accent-blue);
            font-weight: bold;
        }

        /* الكاروسيل */
        .carousel-container {
            width: 100%;
            max-width: 1200px;
            margin: 40px auto;
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            min-width: 100%;
            height: 500px;
            position: relative;
            overflow: hidden;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
        }

        .progress-container {
            width: 80%;
            height: 4px;
            background: rgba(0,0,0,0.1);
            position: absolute;
            bottom: 50px;
            left: 10%;
            border-radius: 2px;
        }

        .progress-bar {
            height: 100%;
            width: 0;
            background: var(--accent-blue);
            transition: width 5s linear;
        }

        .dots-container {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
        }

        .dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: rgba(0,0,0,0.2);
            border: 2px solid rgba(0,0,0,0.3);
            cursor: pointer;
            transition: 0.3s;
        }

        .dot.active {
            background: var(--accent-blue);
            border-color: var(--accent-blue);
            transform: scale(1.2);
        }

        .nav-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .nav-buttons button {
            background: rgba(0, 0, 0, 0.5);
            color: var(--pure-white);
            border: none;
            font-size: 2rem;
            padding: 10px 20px;
            cursor: pointer;
            pointer-events: all;
            transition: 0.3s;
        }

        .nav-buttons button:hover {
            background: rgba(0, 0, 0, 0.7);
            color: var(--pure-white);
        }
        /* تنسيق زر تسجيل الخروج */
.logout-link {
    color: #ff4444 !important; /* لون أحمر جذاب */
    padding: 12px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-link:hover {
    background: rgba(255, 68, 68, 0.1) !important;
    transform: translateY(-2px);
}

.logout-link i {
    font-size: 1.2em;
}

        /* أقسام المنتجات */
        .products-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.2rem;
            color: var(--text-dark);
            display: inline-block;
            padding-bottom: 10px;
        }

        .section-title h2:after {
            content: "";
            position: absolute;
            width: 80px;
            height: 3px;
            background-color: var(--accent-blue);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
        }
        /* hadi lefect dyal tsawr lwala*/
        .product-image {
            position: relative;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        .product-name-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px;
            text-align: center;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .product-card:hover .product-name-overlay {
            transform: translateY(0);
        }
        .product-name-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    padding: 20px;
    font-size: 1.3rem;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}
        /* للقسم الجديد - 4 بطاقات في الصف */
        .best-sellers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            position: relative;
            display: flex;
            flex-direction: column;
            background: var(--primary-dark);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 400px;
            border: 1px solid #d2d2d7;
        }
        .product-info {
    padding: 15px;
    background: var(--primary-dark);
    border-top: 1px solid #d2d2d7;
    text-align: center;
    flex-shrink: 0; /* لمنع التقلص عند تغيير الحجم */
}

.product-info h3 {
    font-size: 1.1rem;
    color: var(--text-dark);
    margin: 0;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* تعديل ارتفاع الصورة لترك مساحة للنص */
.product-image {
    height: calc(100% - 60px); /* يترك مساحة 60px للنص */
    width: 100%;
    overflow: hidden;
}

/* تعديل خاص لقسم الأكثر مبيعًا */
.best-sellers-grid .product-card {
    height: 350px;
}

.best-sellers-grid .product-image {
    height: calc(100% - 50px); /* مساحة أقل للنص في الأكثر مبيعًا */
}

/* تأثيرات الهافر مع النص */
.product-card:hover .product-info {
    background: rgba(0,0,0,0.02);
    border-color: var(--accent-blue);
}

.product-card:hover .product-info h3 {
    color: var(--accent-blue);
}

        /* جعل بطاقات الأكثر مبيعًا أقل ارتفاعًا قليلاً */
        .best-sellers-grid .product-card {
            height: 350px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: var(--accent-blue);
        }

        .product-image {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.03);
        }

        /* قسم المنتجات القادمة */
        .upcoming-products {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        /* عرض الشرائح */
        .slider-container {
            position: relative;
            overflow: hidden;
            margin: 0 auto;
            width: 100%;
        }

        .slider-track {
            display: flex;
            transition: transform 0.7s cubic-bezier(0.25, 0.1, 0.25, 1);
        } 

        .slider-item {
            min-width: 20%;
            padding: 0 10px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .slider-nav {
            text-align: center;
            margin-top: 30px;
        }

        .slider-nav button {
            background: var(--accent-blue);
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
            font-size: 1.2rem;
            color: var(--pure-white);
        }

        .slider-nav button:hover {
            background: var(--text-dark);
            color: var(--pure-white);
            transform: scale(1.1);
        }

        .slider-card {
            height: 320px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border: 1px solid #d2d2d7;
        }

        .slider-card:hover {
            transform: scale(1.03);
            border-color: var(--accent-blue);
        }

        .slider-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* تذييل الصفحة (Footer) */
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

        /* نموذج النشرة البريدية */
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
    </style>
</head>
<body>
    <!-- الشريط العلوي -->
    <div class="top-bar">
        <div class="logo-container">
            <img src="image/WhatsApp Image 2025-05-02 at 3.33.32 PM.jpeg" alt="Logo">
        </div>

        <div class="search-container">
            <input type="text" class="search-input" placeholder="Recherchez votre telephone...">
        </div>

        <div class="top-actions">
    <div class="add-phone-wrapper">
        <button class="add-button">+</button>
        <span class="add-text">Ajouter votre téléphone</span>
    </div>
    <i class="fas fa-shopping-cart cart-icon"></i>
    <i class="fas fa-user-circle account-icon"></i>
</div>
    </div>

    <!-- النافبار -->
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="homePage.php"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="dropdown">
                <a href="#android">Android</a>
                <div class="dropdown-menu vertical">
                    <a href="oppo.php">Oppo</a>
                    <a href="samsung.php">Samsung</a>
                    <a href="RedMagic.php">RedMagic</a>
                    <a href="Huawei.php">Huawei</a>
                    <a href="Realme.php">Realme</a>
                    <a href="Xiaomi.php">Xiaomi</a>
                    <a href="asus.php">Asus</a>
                    <a href="OnePlus.php">OnePlus</a>
                    <a href="NothingPhone.php">Nothing Phone</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#ios">iOS</a>
                <div class="dropdown-menu vertical">
                <a href="iphone.php">iPhone</a>
                <a href="ipad.php">iPad</a>
                </div>
            </li>
            <li><a href="#promo">Promotions</a></li>
            <li><a href="#contact">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
            <li class="logout-item">
                <a href="logout.php" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- الكاروسيل -->
    <div class="carousel-container">
        <div class="carousel-track">
            <div class="carousel-item"><img src="image/tsiwratprojet2.webp" alt="Slide 1"></div>
            <div class="carousel-item"><img src="image/pub red magic 2.jpg" alt="Slide 2"></div>
            <div class="carousel-item"><img src="image/mli7aaa.png" alt="Slide 3"></div>
            <div class="carousel-item"><img src="image/WhatsApp Image 2025-05-03 at 4.37.48 PM (4).jpeg" alt="Slide 4"></div>
        </div>

        <div class="nav-buttons">
            <button id="prevBtn">&#10094;</button>
            <button id="nextBtn">&#10095;</button>
        </div>

        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>

        <div class="dots-container"></div>
    </div>

     <!-- قسم أحدث الهواتف -->
<section class="products-section">
    <div class="section-title">
        <h2>Derniers Téléphones</h2>
    </div>
    
    <div class="products-grid">
        <div class="product-card">
            <div class="product-image">
                <img src="image/WhatsApp Image 2025-05-02 at 5.19.21 PM.jpeg" alt="nubia Z70 ultra">
                <div class="product-name-overlay">nubia Z70 ultra</div>
            </div>
        </div>

        <div class="product-card">
            <div class="product-image">
                <img src="image/red_magic_10_pro_plus.jpg" alt="REDMAGIC 10 pro Gold Saga">
                <div class="product-name-overlay">REDMAGIC 10 pro Gold Saga</div>
            </div>
        </div>

        <div class="product-card">
            <div class="product-image">
                <img src="image/WhatsApp Image 2025-05-02 at 5.15.39 PM.jpeg" alt="Nothing Phone 1">
                <div class="product-name-overlay">Nothing Phone 1</div>
            </div>
        </div>
    </div>
</section>
    <!-- قسم الأكثر مبيعًا الجديد -->
    <section class="products-section">
        <div class="section-title">
            <h2>Les Plus Vendus</h2>
        </div>
        
        <div class="best-sellers-grid">
            <!-- البطاقة 1 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="image/WhatsApp Image 2025-05-03 at 3.05.31 PM.jpeg" alt="iPhone 14 pro">
                </div>
                <div class="product-info"><h3>iphone 14 pro</h3></div>
            </div>

            <!-- البطاقة 2 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="image/WhatsApp Image 2025-05-02 at 5.57.06 PM.jpeg" alt="Samsung S23 ultra">
                </div>
                <div class="product-info"><h3>Samsung S23 ultra</h3></div>
            </div>

            <!-- البطاقة 3 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="image/WhatsApp Image 2025-05-03 at 3.10.39 PM.jpeg" alt="poco F6 pro">
                </div>
                <div class="product-info"><h3>poco F6 pro</h3></div>
            </div>

            <!-- البطاقة 4 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="image/WhatsApp Image 2025-05-02 at 5.55.07 PM.jpeg" alt="OnePlus 12">
                </div>
                <div class="product-info"><h3>OnePlus 12</h3></div>
            </div>
        </div>
    </section>
    <!-- قسم الهواتف القادمة -->
    <section class="upcoming-products">
        <div class="section-title">
            <h2>Modèles de l'année Prochaine</h2>
        </div>
        
        <div class="slider-container">
            <div class="slider-track">
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 7.56.05 PM.jpeg" alt="iphone 17">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPhone 17</div>
                            <div class="model-info-details">
                                Écran 6.1" OLED 120Hz<br>
                                Processeur A19 Bionic<br>
                                Triple caméra 48MP
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 7.52.11 PM.jpeg" alt="iPhone 17 air">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPhone 17 Air</div>
                            <div class="model-info-details">
                                Écran 6.7" Super Retina XDR<br>
                                Batterie 4500mAh<br>
                                Design ultra-fin 7.1mm
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 7.45.26 PM.jpeg" alt="iphone 17 pro">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPhone 17 Pro</div>
                            <div class="model-info-details">
                                Écran 6.3" ProMotion<br>
                                A19 Pro chipset<br>
                                Système photo révolutionnaire
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 7.48.47 PM.jpeg" alt="iphone 17 pro max">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPhone 17 Pro Max</div>
                            <div class="model-info-details">
                                Écran 6.9" LTPO OLED<br>
                                Batterie 5000mAh<br>
                                Charge sans fil 25W
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 8.03.05 PM.jpeg" alt="ipad pro M5">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPad Pro M5</div>
                            <div class="model-info-details">
                                Chipset Apple M5<br>
                                Écran Mini-LED 12.9"<br>
                                Compatible Stylo 3ème gén
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 8.06.28 PM.jpeg" alt="ipad mini 8">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPad Mini 8</div>
                            <div class="model-info-details">
                                Design bord à bord<br>
                                Processeur A16 Bionic<br>
                                Caméra avant 12MP
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 8.14.36 PM.jpeg" alt="ipad air 7 2025">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPad Air 7 (2025)</div>
                            <div class="model-info-details">
                                Écran 10.9" Liquid Retina<br>
                                Compatible Magic Keyboard<br>
                                Colors vives
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-item">
                    <div class="slider-card">
                        <img src="image/WhatsApp Image 2025-05-02 at 8.19.27 PM.jpeg" alt="ipod">
                        <div class="model-info-overlay">
                            <div class="model-info-title">iPod Touch 8G</div>
                            <div class="model-info-details">
                                Retour de la légende<br>
                                Stockage jusqu'à 1TB<br>
                                Compatible Apple Music
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="slider-nav">
            <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
            <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
        <!-- تذييل الصفحة الجديد -->
        <footer class="footer">
            <div class="footer-container">
                <!-- العمود الأول -->
                <div class="footer-column">
                    <h3>Aide & Contact</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-phone-alt"></i> +212 656-704536</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> ShurikenPhone.com</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Nos magasins</a></li>
                        <li><a href="#"><i class="fas fa-headset"></i> Service client</a></li>
                    </ul>
                </div>
    
                <!-- العمود الثاني -->
                <div class="footer-column">
                    <h3>Informations</h3>
                    <ul>
                        <li><a href="#">À propos de nous</a></li>
                        <li><a href="#">Livraison & Retour</a></li>
                        <li><a href="#">Paiement sécurisé</a></li>
                        <li><a href="#">Garantie produits</a></li>
                    </ul>
                </div>
    
                <!-- العمود الثالث -->
                <div class="footer-column">
                    <h3>Mon Compte</h3>
                    <ul>
                        <li><a href="#">Mon profil</a></li>
                        <li><a href="#">Mes commandes</a></li>
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
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
    
            <div class="footer-bottom">
                <div class="payment-methods">
                    <img src="image/WhatsApp Image 2025-05-02 at 3.56.28 PM (1).jpeg" alt="Visa">
                    <img src="image/WhatsApp Image 2025-05-02 at 3.56.27 PM (1).jpeg" alt="cih">
                    <img src="image/WhatsApp Image 2025-05-02 at 4.02.29 PM.jpeg" alt="Mastercard">
                    <img src="image/WhatsApp Image 2025-05-02 at 4.05.43 PM.jpeg" alt="chaabi">
                </div>
                <p>&copy; SHURIKEN PHONE STORE</p>
            </div>
        </footer>

    <script>
        // الكاروسيل الرئيسي
        const carouselTrack = document.querySelector('.carousel-track');
        const carouselItems = document.querySelectorAll('.carousel-item');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dotsContainer = document.querySelector('.dots-container');
        const progressBar = document.querySelector('.progress-bar');
        
        let currentIndex = 0;
        const totalItems = carouselItems.length;
        let intervalId;
        
        // إنشاء النقاط
        carouselItems.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                goToSlide(index);
            });
            dotsContainer.appendChild(dot);
        });
        
        // الانتقال إلى شريحة محددة
        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
            resetInterval();
        }
        
        // تحديث الكاروسيل
        function updateCarousel() {
            carouselTrack.style.transform = translateX(-${currentIndex * 100}%);
            
            // تحديث النقاط النشطة
            document.querySelectorAll('.dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
            
            // إعادة تعيين شريط التقدم
            progressBar.style.width = '0';
            progressBar.style.transition = 'none';
            setTimeout(() => {
                progressBar.style.transition = 'width 5s linear';
                progressBar.style.width = '100%';
            }, 10);
        }
        
        // التلقائي
        function startInterval() {
            intervalId = setInterval(() => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            }, 5000);
        }
        
        function resetInterval() {
            clearInterval(intervalId);
            startInterval();
        }
        
        // الأزرار
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
            resetInterval();
        });
        
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
            resetInterval();
        });
        
        // بدء الكاروسيل
        progressBar.style.width = '100%';
        startInterval();
        
        // السلايدر الجانبي
        const sliderTrack = document.querySelector('.slider-track');
        const sliderItems = document.querySelectorAll('.slider-item');
        const prevSlideBtn = document.querySelector('.prev-slide');
        const nextSlideBtn = document.querySelector('.next-slide');
        
        let currentSlide = 0;
        const slidesToShow = 5;
        const totalSlides = sliderItems.length;
        let sliderInterval;

        sliderItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            clearInterval(sliderInterval);
        });
        
        item.addEventListener('mouseleave', () => {
            startSliderInterval();
        });
    });
        
        // إنشاء نسخة من العناصر الأولى وإضافتها للنهاية
        function cloneFirstSlides() {
            for (let i = 0; i < slidesToShow; i++) {
                const clone = sliderItems[i].cloneNode(true);
                sliderTrack.appendChild(clone);
            }
        }
        
        cloneFirstSlides();
/****************/
        function updateSlider() {
            const slideWidth = sliderItems[0].offsetWidth;
            sliderTrack.style.transition = 'transform 0.7s cubic-bezier(0.25, 0.1, 0.25, 1)';
            sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        }
/****************/
        function startSliderInterval() {
            sliderInterval = setInterval(() => {
                currentSlide++;
                updateSlider();
                
                // إذا وصلنا إلى نهاية العناصر الأصلية
                if (currentSlide >= totalSlides) {
                    setTimeout(() => {
                        sliderTrack.style.transition = 'none';
                        currentSlide = 0;
                        sliderTrack.style.transform = 'translateX(0)';
                    }, 700);
                }
            }, 3000);
        }
        
        prevSlideBtn.addEventListener('click', () => {
            clearInterval(sliderInterval);
            
            if (currentSlide <= 0) {
                currentSlide = totalSlides;
                sliderTrack.style.transition = 'none';
                sliderTrack.style.transform = `translateX(-${currentSlide * sliderItems[0].offsetWidth}px)`;
                setTimeout(() => {
                    currentSlide--;
                    sliderTrack.style.transition = 'transform 0.7s cubic-bezier(0.25, 0.1, 0.25, 1)';
                    updateSlider();
                }, 10);
            } else {
                currentSlide--;
                updateSlider();
            }
            
            startSliderInterval();
        });
        
        nextSlideBtn.addEventListener('click', () => {
            clearInterval(sliderInterval);
            
            if (currentSlide >= totalSlides) {
                sliderTrack.style.transition = 'none';
                currentSlide = 0;
                sliderTrack.style.transform = 'translateX(0)';
                setTimeout(() => {
                    currentSlide++;
                    sliderTrack.style.transition = 'transform 0.7s cubic-bezier(0.25, 0.1, 0.25, 1)';
                    updateSlider();
                }, 10);
            } else {
                currentSlide++;
                updateSlider();
            }
            
            startSliderInterval();
        });
        
        // جعل السلايدر متجاوبًا
        window.addEventListener('resize', updateSlider);
        
        // بدء السلايدر التلقائي
        startSliderInterval();
        
        // إضافة تأثير للاشتراك في النشرة البريدية
        document.querySelector('.newsletter-form button').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Merci pour votre abonnement à notre newsletter !');
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-paper-plane"></i>';
            }, 2000);
        });
         // كود إخفاء/إظهار النافبار عند التمرير
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    const topBar = document.querySelector('.top-bar');
    const navbarHeight = navbar.offsetHeight;
    const topBarHeight = topBar.offsetHeight;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            // أعلى الصفحة - إظهار كل العناصر
            navbar.style.transform = 'translateY(0)';
            topBar.style.transform = 'translateY(0)';
            return;
        }

        if (currentScroll > lastScroll && currentScroll > navbarHeight) {
            // التمرير لأسفل - إخفاء النافبار
            navbar.style.transform = `translateY(-${navbarHeight}px)`;
            topBar.style.transform = `translateY(-${topBarHeight}px)`;
        } else if (currentScroll < lastScroll) {
            // التمرير لأعلى - إظهار النافبار
            navbar.style.transform = 'translateY(0)';
            topBar.style.transform = 'translateY(0)';
        }

        lastScroll = currentScroll;
    });

    // باقي الكود السابق للكاروسيل والسلايدر...
    </script>
</body>
</html>