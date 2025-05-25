<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<!-- Navigation Bar -->
<div class="top-bar">
    <div class="logo-container">
        <img src="image/shuriken logo-Photoroom.png" alt="Logo" class="logo-light">
        <img src="image/WhatsApp Image 2025-05-21 at 12.30.11 AM-Photoroom.png" alt="Logo" class="logo-dark">
    

    <!-- Dark Mode Toggle -->
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
    <!-- Search Bar -->
    <div class="search-container">
        <form action="search.php" method="get">
            <input type="text" name="query" class="search-input" placeholder="Recherchez votre téléphone..." required>
            <button type="submit" style="display:none;"></button>
        </form>
    </div>

    <!-- Top Actions -->
    <div class="top-actions">
        <div class="add-button-container">
            <a href="add_product.php" class="add-button">+</a>
            <span class="add-text">Ajouter votre téléphone</span>
        </div>
        
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
        
        <div class="account-icon-container">
            <a href="account.php" class="account-icon-link">
                <i class="fas fa-user-circle account-icon"></i>
            </a>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<nav class="navbar">
    <ul class="nav-links">
        <li><a href="homePage.php"><i class="fas fa-home"></i> Accueil</a></li>
        <li class="dropdown">
            <a href="#android"><i class="fab fa-android"></i> Android</a>
            <div class="dropdown-menu vertical">
                 <a href="oppo.php"><i class="fas fa-mobile-alt"></i> Oppo</a>
                <a href="samsung.php"><i class="fas fa-mobile-alt"></i> Samsung</a>
                <a href="RedMagic.php"><i class="fas fa-mobile-alt"></i> RedMagic</a>
                <a href="Huawei.php"><i class="fas fa-mobile-alt"></i> Huawei</a>
                <a href="Realme.php"><i class="fas fa-mobile-alt"></i> Realme</a>
                <a href="Xiaomi.php"><i class="fas fa-mobile-alt"></i> Xiaomi</a>
                <a href="asus.php"><i class="fas fa-mobile-alt"></i> Asus</a>
                <a href="OnePlus.php"><i class="fas fa-mobile-alt"></i> OnePlus</a>
                <a href="NothingPhone.php"><i class="fas fa-mobile-alt"></i> Nothing Phone</a>

                <!-- Add more Android brands -->
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
        <li class="logout-item">
            <a href="logout.php" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>

<style>
    /* Base Styles */
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

    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
        padding-top: 120px;
        margin: 0;
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
    }

    .logo-container {
        position: relative;
        display: flex;
        align-items: center;
        height: 50px;
    }

    .logo-container img {
        height: 100%;
        width: auto;
        transition: opacity 0.3s ease;
    }

    .logo-dark {
        position: absolute;
        left: 0;
        opacity: 0;
    }

    /* Dark Mode Switch */
    .dark-mode-wrapper {
        margin-left: 20px;
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

    /* Search Bar */
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
        background: #f5f5f7;
        color: var(--text-dark);
    }

    .search-input::placeholder {
        color: var(--text-light);
    }

    /* Top Actions */
    .top-actions {
        position: absolute;
        right: 20px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .add-button-container {
        position: relative;
    }

    .add-button {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--text-dark);
        cursor: pointer;
        padding: 5px 12px;
        transition: all 0.3s ease;
    }

    .add-button:hover {
        color: var(--accent-blue);
        transform: scale(1.2);
    }

    .add-text {
        position: absolute;
        top: 50%;
        right: 100%;
        transform: translateY(-50%);
        opacity: 0;
        width: max-content;
        transition: all 0.3s ease;
        font-size: 14px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        pointer-events: none;
        margin-right: 10px;
    }

    .add-button:hover + .add-text {
        opacity: 1;
        transform: translate(-10px, -50%);
    }

    .cart-icon-container {
        position: relative;
        display: inline-block;
    }

    .cart-icon, .account-icon {
        font-size: 24px;
        color: var(--text-dark);
        cursor: pointer;
        transition: 0.3s;
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
        transition: all 0.3s ease;
    }

    .dropdown-menu.vertical a:hover {
        background-color: rgba(0,0,0,0.05);
        color: var(--accent-blue);
    }

    /* Logout Link */
    .logout-link {
        color: #ff4444 !important;
        padding: 12px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .logout-link:hover {
        background: rgba(255, 68, 68, 0.1) !important;
    }

    /* Dark Mode Styles */
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
    [data-theme="dark"] .dropdown-menu.vertical,
    [data-theme="dark"] .search-input {
        background-color: var(--primary-dark-light);
        border-color: #333;
    }

    [data-theme="dark"] .search-input {
        background: #1e1e1e;
        color: #f1f1f1;
    }

    [data-theme="dark"] .nav-links a:hover,
    [data-theme="dark"] .dropdown-menu.vertical a:hover {
        background: rgba(255,255,255,0.05);
    }

    [data-theme="dark"] .logo-light {
        opacity: 0;
    }

    [data-theme="dark"] .logo-dark {
        opacity: 1;
    }
</style>

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
                if(darkModeToggle) darkModeToggle.checked = true;
            } else {
                htmlElement.setAttribute('data-theme', 'light');
                if(darkModeToggle) darkModeToggle.checked = false;
            }
        }
        
        loadTheme();
        
        if(darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    htmlElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    htmlElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });
        }
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                loadTheme();
            }
        });
    });

    // Hide navbar on scroll
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    const topBar = document.querySelector('.top-bar');
    const navbarHeight = navbar.offsetHeight;
    const topBarHeight = topBar.offsetHeight;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            navbar.style.transform = 'translateY(0)';
            topBar.style.transform = 'translateY(0)';
            return;
        }

        if (currentScroll > lastScroll && currentScroll > navbarHeight) {
            navbar.style.transform = `translateY(-${navbarHeight}px)`;
            topBar.style.transform = `translateY(-${topBarHeight}px)`;
        } else if (currentScroll < lastScroll) {
            navbar.style.transform = 'translateY(0)';
            topBar.style.transform = 'translateY(0)';
        }

        lastScroll = currentScroll;
    });
</script>
</body>
</html>