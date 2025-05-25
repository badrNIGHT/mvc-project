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
			?>
		
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
			
			/*hadi chkal whover 3la + */
			.add-button {
			    background: none;
			    border: none;
			    font-size: 24px;
			    color: var(--text-dark);
			    cursor: pointer;
			    padding: 5px 10px;
			    transition: 0.3s;
			    transform: translatex(15px);
			}
			
			.add-text {
			    position: absolute;
			    left: -140px;
			    opacity: 0;
			    transition: 0.3s;
			    white-space: nowrap;
			    font-size: 14px;
			    background: rgba(254, 250, 250, 0.9);
			    padding: 5px 10px;
			    border-radius: 5px;
			    pointer-events: none;
			}
			
			.add-button:hover + .add-text {
			    opacity: 1;
			    transform: translateX(10px);
			}
			
			
			/* تنسيق زر الإضافة (+) */
			.add-button-container {
			    position: relative;
			    margin-right: 0px;
			}
			
			.add-button {
			    background: none;
			    border: none;
			    font-size: 24px;
			    color: var(--text-dark);
			    cursor: pointer;
			    padding: 5px 12px;
			    transition: all 0.3s ease;
			    position: relative;
			    left:0;
			}
			
			.add-button:hover {
			    color: var(--accent-blue);
			    transform: scale(1.2);
			}
			.add-button:active{
			    left:5px;
			}
			.add-text {
			    position: absolute;
			    top: 50%;
			    right: 100%; /* تغيير من left إلى right */
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
			    white-space: nowrap;
			    margin-right: 10px; /* إضافة margin بين النص والزر */
			}
			
			.add-button:hover + .add-text {
			    opacity: 1;
			    transform: translate(-10px, -50%); /* تعديل التحريك */
			}
			
			/* تعديل المسافة بين العناصر في الشريط العلوي */
			.top-actions {
			    display: flex;
			    gap: 15px;
			    align-items: center;
			}
			/**hadi zwa9 dyal panier */
			.cart-icon-container {
			        position: relative;
			        display: inline-block;
			    }
			    
			    .cart-count {
			        position: absolute;
			        top: -10px;
			        right: -10px;
			        background:rgb(24, 27, 163);
			        color: white;
			        border-radius: 50%;
			        width: 20px;
			        height: 20px;
			        display: flex;
			        align-items: center;
			        justify-content: center;
			        font-size: 12px;
			    }
			    /**hadi nav zwa9 */
			    /* تحسينات للأيقونات في القائمة */
			.nav-links a i {
			    font-size: 1.1em;
			    width: 20px;
			    text-align: center;
			}
			
			/* تنسيق خاص للقوائم المنسدلة */
			.dropdown-menu.vertical a i {
			    margin-right: 10px;
			    color: var(--accent-blue);
			}
			
			/* تحسين المسافات بين العناصر */
			.nav-links a {
			    gap: 10px;
			}
			
			/* تأثيرات إضافية للهافر */
			.nav-links a:hover i {
			    transform: scale(1.1);
			    transition: transform 0.3s ease;
			}
			/** hada lmosa3id jdid */
			   /* ===== تنسيقات الروبوت المساعد ===== */

/* ===== تنسيقات الروبوت المساعد المعدلة ===== */
.help-bot {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    z-index: 1000;
    overflow: hidden;
    border: 3px solid white;
    animation: float 3s ease-in-out infinite;
}

.help-bot:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
}

.robot-face {
    position: relative;
    width: 40px;
    height: 40px;
}

.robot-eyes {
    display: flex;
    justify-content: space-between;
    width: 100%;
    position: absolute;
    top: 10px;
}

.left-eye, .right-eye {
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
    transition: transform 0.1s ease;
}

.robot-mouth {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 20px;
    height: 5px;
    background: white;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.help-bot:hover .robot-mouth {
    height: 8px;
    border-radius: 0 0 8px 8px;
}

/* نافذة المساعدة المعدلة */
.help-container {
    position: fixed;
    bottom: 110px;
    right: 30px;
    width: 300px;
    height: 400px;
    background: #f8f9fa;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    z-index: 1000;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    border: 1px solid #e0e0e0;
    display: none;
}

.help-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
    position: relative;
    color: white;
}

.help-header h3 {
    margin: 0;
    font-size: 1.2em;
}

.help-header p {
    margin: 5px 0 0 0;
    font-size: 0.8em;
    opacity: 0.9;
}

.close-help {
    position: absolute;
    left: 15px;
    top: 15px;
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.close-help:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* منطقة الدردشة */
.chat-messages {
    height: 200px;
    overflow-y: auto;
    padding: 15px;
    background: white;
}

.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 3px;
}

/* رسائل المستخدم والروبوت */
.user-message, .bot-message {
    margin-bottom: 15px;
    animation: slideIn 0.3s ease-out;
}

.message-content {
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.user-avatar, .bot-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.user-avatar {
    background: #333;
    color: white;
    margin-left: auto;
    order: 2;
}

.bot-avatar {
    background: #667eea;
    color: white;
}

.user-message .message-content {
    flex-direction: row-reverse;
}

.message-text {
    background: white;
    padding: 10px 14px;
    border-radius: 15px;
    max-width: 80%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
}

.user-message .message-text {
    background: #333;
    color: white;
    margin-left: auto;
}

.bot-message .message-text {
    background: #f0f0f0;
    border: 1px solid #e0e0e0;
    color: #333;
}

.response-text {
    line-height: 1.5;
    word-wrap: break-word;
}

.timestamp {
    display: block;
    margin-top: 6px;
    font-size: 0.7em;
    opacity: 0.7;
    text-align: right;
}

/* الأسئلة السريعة المعدلة */
.quick-questions {
    padding: 12px;
    background: #f0f0f0;
    border-top: 1px solid #e0e0e0;
}

.quick-questions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    margin-bottom: 5px;
}

.quick-questions-content {
    max-height: 120px;
    overflow-y: auto;
    transition: max-height 0.3s ease;
}

.quick-questions-content.collapsed {
    max-height: 0;
    overflow: hidden;
}

.quick-questions button {
    background: #e0e0e0;
    color: #333;
    border: 1px solid #ccc;
    padding: 6px 10px;
    margin: 3px;
    border-radius: 15px;
    font-size: 0.8em;
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
    width: 100%;
    text-align: left;
}

.quick-questions button:hover {
    background: #d0d0d0;
    transform: translateY(-2px);
}

.questions-btn {
    background: #667eea !important;
    color: white !important;
    font-weight: 600 !important;
    margin-top: 8px !important;
    border: none !important;
}

.toggle-questions {
    background: none;
    border: none;
    color: #333;
    cursor: pointer;
    padding: 5px;
    font-size: 14px;
}

/* قائمة الأسئلة */
.questions-list-container {
    position: absolute;
    bottom: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 15px 15px 0 0;
    box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.15);
    max-height: 250px;
    overflow-y: auto;
    z-index: 1001;
    border: 1px solid #e0e0e0;
    display: none;
}

.questions-list-header {
    background: #667eea;
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: 600;
    border-radius: 15px 15px 0 0;
    position: relative;
}

.close-questions {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    opacity: 0.8;
    transition: all 0.3s ease;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-questions:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-50%) rotate(90deg);
}

.questions-list {
    padding: 10px;
    max-height: 250px;
    overflow-y: auto;
}

.questions-list::-webkit-scrollbar {
    width: 6px;
}

.questions-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.questions-list::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 3px;
}

.question-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    margin: 5px 0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    background: #f8f9fa;
}

.question-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
    border-color: #667eea;
}

.question-number {
    background: #667eea;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8em;
    font-weight: 600;
    flex-shrink: 0;
}

.question-item:hover .question-number {
    background: white;
    color: #667eea;
}

.question-text {
    flex: 1;
    font-size: 0.85em;
    line-height: 1.4;
    color: #333;
}

/* منطقة الإدخال */
.chat-input {
    padding: 12px;
    background: #f0f0f0;
    border-top: 1px solid #e0e0e0;
}

.input-container {
    display: flex;
    gap: 8px;
    align-items: center;
}

#userInput {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 20px;
    background: white;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}

#userInput:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
}

.voice-btn, .send-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #667eea;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
}

.voice-btn:hover, .send-btn:hover {
    background: #5a6edb;
    transform: translateY(-2px);
}

.voice-btn.listening {
    background: linear-gradient(45deg, #ff6b6b, #ff8e53);
    animation: pulse 1.5s infinite;
}

/* مؤشر الكتابة */
.typing-indicator {
    display: flex;
    gap: 4px;
    align-items: center;
    padding: 8px 0;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #667eea;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

/* الأنيميشن */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.5); opacity: 0; }
}

@keyframes scaleUp {
    from { opacity: 0; transform: scale(0.8) translateY(20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

@keyframes scaleDown {
    from { opacity: 1; transform: scale(1) translateY(0); }
    to { opacity: 0; transform: scale(0.8) translateY(20px); }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30% { transform: translateY(-10px); opacity: 1; }
}

			/**darck mod */
			    /* Dark Mode Toggle Button */
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
			[data-theme="dark"] .slider-card,
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
			/* Dark Mode Switch - النسخة المعدلة */
			/* Dark Mode Switch - النسخة المعدلة */
			.dark-mode-wrapper {
			    margin-left: 8px; /* يمكنك زيادة أو تقليل هذه القيمة لضبط المسافة من اللوجو */
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
			    background-color: #d2d2d7; /* اللون الرمادي الفاتح */
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
			
			/* ألوان الأيقونات المعدلة */
			.dark-mode-slider .sun-icon {
			    position: absolute;
			    left: 8px;
			    top: 6px;
			    color: #333; /* اللون الأسود بدلاً من الأصفر */
			    font-size: 16px;
			    z-index: 1;
			}
			
			.dark-mode-slider .moon-icon {
			    position: absolute;
			    right: 8px;
			    top: 6px;
			    color: #f0f0f0; /* اللون الأبيض */
			    font-size: 16px;
			    z-index: 1;
			}
			
			/* لون الزر عند التفعيل - تم تغييره لرمادي غامق */
			input:checked + .dark-mode-slider {
			    background-color: #555; /* اللون الرمادي الغامق بدلاً من الأزرق */
			}
			
			input:checked + .dark-mode-slider:before {
			    transform: translateX(30px);
			}
			
			/* تعديلات التموضع للشريط العلوي */
			.logo-container {
			    display: flex;
			    align-items: center;
			    position: absolute;
			    left: 20px; /* يمكنك تعديل هذه القيمة لتحريك المجموعة ككل */
			}
			
			.search-container {
			    position: absolute;
			    left: 50%;
			    transform: translateX(-50%);
			    width: 400px;
			}
			
			.top-actions {
			    position: absolute;
			    right: 20px;
			}
			/**kitbdal logo */
			.logo-container {
			    position: relative;
			    display: flex;
			    align-items: center;
			    height: 50px; /* ارتفاع ثابت للحاوية */
			    width: auto; /* عرض يتكيف مع المحتوى */
			}
			
			.logo-light, .logo-dark {
			    height: 100%; /* تأخذ كامل ارتفاع الحاوية */
			    width: auto;
			    max-width: 120px; /* تحديد أقصى عرض */
			    transition: opacity 0.3s ease; /* تأثير انتقالي */
			    object-fit: contain; /* للحفاظ على نسب الصورة */
			}
			
			.logo-dark {
			    position: absolute;
			    left: 0;
			    top: 0;
			    opacity: 0;
			    transform: scale(0.8); /* تصغير اللوجو الأسود بنسبة 20% */
			}
			
			[data-theme="dark"] .logo-light {
			    opacity: 0;
			}
			
			[data-theme="dark"] .logo-dark {
			    opacity: 1;
			    transform: scale(1); /* إعادة الحجم الطبيعي عند التفعيل */
			}

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			    </style>
			</head>
			<body>
			    <!-- top bar الشريط العلوي -->
			    <div class="top-bar">
			       <div class="logo-container">
			    <img src="image/shuriken logo-Photoroom.png" alt="Logo" class="logo-light">
			    <img src="image/WhatsApp Image 2025-05-21 at 12.30.11 AM-Photoroom.png" alt="Logo" class="logo-dark">
			       
			
			<!-- button dark mod-->
			    <div class="dark-mode-wrapper">
			        <label class="dark-mode-switch">
			            <input type="checkbox" id="dark-mode-toggle">
			            <span class="dark-mode-slider round">
			                <i class="fas fa-sun sun-icon"></i>
			                <i class="fas fa-moon moon-icon"></i>
			            </span>
			        </label>
			    </div> </div>
			<!--========================================-->
			
			
			
			        <div class="search-container">
			            <form action="search.php" method="get">
			            <input type="text" name="query" class="search-input" placeholder="Recherchez votre téléphone..." required>
			            <button type="submit" style="display:none;"></button>
			            </form>
			        </div>
			
			
			        <!--hado lbotonat 3 (+)-->
			        <!-- الأزرار الثلاثة -->
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
			            
			  
			    <!-- النافبار -->
			    <!-- النافبار -->
			<!-- النافبار -->
			<nav class="navbar">
			    <ul class="nav-links">
			        <li><a href="homepage.php"><i class="fas fa-home"></i> Accueil</a></li>
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
			        <li><a href="used_products.php"><i class="fas fa-graduation-cap"></i> Marketplace Étudiant</a></li>
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
			   
			
			
			
			
			
			
			
			
			
			
			
			
			<!-- Fenêtre d'aide améliorée -->
			<!-- lmosa3id jdid -->
			    
 <!-- الروبوت المساعد -->
<div class="help-bot">
    <div class="robot-face">
        <div class="robot-eyes">
            <div class="left-eye"></div>
            <div class="right-eye"></div>
        </div>
        <div class="robot-mouth"></div>
    </div>
</div>

<!-- نافذة المساعدة -->
<div id="helpContainer" class="help-container">
    <div class="help-header">
        <h3>🤖 Assistant Shuriken</h3>
        <p>Comment puis-je vous aider aujourd'hui ?</p>
        <button class="close-help"><i class="fas fa-times"></i></button>
    </div>

    <div id="chatMessages" class="chat-messages"></div>

    <div class="quick-questions">
        <div class="quick-questions-header">
            <p>Questions rapides :</p>
            <button class="toggle-questions"><i class="fas fa-chevron-up"></i></button>
        </div>
        <div class="quick-questions-content">
            <button onclick="askQuestion('Quels sont vos derniers téléphones ?')">📱 Derniers téléphones</button>
            <button onclick="askQuestion('Combien coûte la livraison ?')">🚚 Frais de livraison</button>
            <button onclick="askQuestion('Avez-vous des promotions en cours ?')">🎉 Promotions actuelles</button>
            <button onclick="askQuestion('Comment suivre ma commande ?')">📦 Suivi de commande</button>
            <button class="questions-btn" onclick="showQuestionsList()">📋 Toutes les questions</button>
        </div>
    </div>

    <div id="questionsListContainer" class="questions-list-container">
        <div class="questions-list-header">
            <h4>📋 Questions disponibles</h4>
            <button class="close-questions"><i class="fas fa-times"></i></button>
        </div>
        <div id="questionsList" class="questions-list"></div>
    </div>

    <div class="chat-input">
        <div class="input-container">
            <input type="text" id="userInput" placeholder="Écrivez votre question ici...">
            <button class="voice-btn" onclick="startVoiceRecognition()"><i class="fas fa-microphone"></i></button>
            <button class="send-btn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>





















			
			
			        <!-- تذييل الصفحة الجديد -->
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
			            carouselTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
			            
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
			/**********************************************/
			        function updateSlider() {
			            const slideWidth = sliderItems[0].offsetWidth;
			            sliderTrack.style.transition = 'transform 0.7s cubic-bezier(0.25, 0.1, 0.25, 1)';
			            sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
			        }
			/**********************************************/
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
			    //lmosa3id jdid
			   // تبديل عرض نافذة المساعدة
			// Fonction pour basculer l'affichage de l'aide
			//lmosa3id jdid
			// Assistant Robot Functionality
// ============== وظائف الروبوت المساعد المرتبط بالذكاء الاصطناعي الحقيقي ==============

// ============== نظام الروبوت المساعد الذكي - النسخة الكاملة ==============

// إعدادات الذكاء الاصطناعي
const AI_CONFIG = {
  API_URL: "https://api.openai.com/v1/chat/completions",
  API_KEY: "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",
  MODEL: "gpt-3.5-turbo",
  DEFAULT_LANGUAGE: "fr",
  SYSTEM_PROMPT: `Vous êtes l'assistant virtuel de la boutique Shuriken Phone Store, situé à Fès, Maroc.
    Soyez amical, concis et utile. Concentrez-vous sur les téléphones, produits, prix et services du magasin.
    Répondez dans la langue utilisée par le client (français, arabe ou anglais).
    
    Informations sur la boutique:
    - Adresse: IFMOTICA, Fès, Maroc
    - Téléphone: +212 656-704536, +212 624-017026
    - Email: ShurikenPhone.com
    - Livraison gratuite partout au Maroc (2-3 jours)
    - Garantie de 2 ans sur tous les produits
    - Paiement: Carte bancaire, virement, espèces à la livraison
    - Marques disponibles: iPhone, Samsung, Oppo, Xiaomi, Huawei, RedMagic, Realme, Asus, OnePlus, Nothing Phone`,
}

// قاعدة بيانات الهواتف المحسنة
const PRODUCTS_DATABASE = {
  samsung: [
    { name: "Samsung Galaxy S25 Ultra", description: "Smartphone flagship avec caméra 200MP", price: 12999.99 },
    { name: "Samsung Galaxy S25 Edge", description: "Téléphone premium avec écran incurvé", price: 14999.99 },
    { name: "Samsung Galaxy S24 Ultra", description: "Smartphone flagship avec caméra 200MP", price: 10999.99 }
  ],
  apple: [
    { name: "iPhone 16 Pro Max", description: "iPhone flagship avec le plus grand écran", price: 12999.99 },
    { name: "iPhone 16 Pro", description: "iPhone premium avec caméra pro", price: 11999.99 }
  ],
  xiaomi: [
    { name: "Xiaomi 15 ULTRA", description: "Smartphone flagship avec système caméra avancé", price: 8999.99 },
    { name: "Xiaomi 15", description: "Smartphone premium avec spécifications haut de gamme", price: 9999.99 }
  ],
  redmagic: [
    { name: "RedMagic 10s Pro", description: "Smartphone gaming avec refroidissement avancé", price: 8999.99 },
    { name: "REDMAGIC Golden Saga Limited Edition", description: "Téléphone gaming premium avec design unique", price: 7999.99 }
  ]
}

// قائمة الأسئلة المتاحة
const AVAILABLE_QUESTIONS = {
  fr: [
    "Quels sont vos derniers téléphones ?",
    "Combien coûte la livraison ?",
    "Avez-vous des promotions en cours ?",
    "Comment suivre ma commande ?",
    "Quelle est votre garantie ?",
    "Quels modes de paiement acceptez-vous ?",
    "Où se trouve votre magasin ?",
    "Quels sont vos horaires d'ouverture ?",
    "Avez-vous des iPhone en stock ?",
    "Quel est le meilleur Samsung Galaxy ?",
    "Recommandez-moi un téléphone gaming",
    "Quel téléphone pour moins de 5000 dirhams ?",
    "Comparez iPhone vs Samsung",
    "Avez-vous des accessoires ?",
    "Comment retourner un produit ?"
  ],
  ar: [
    "ما هي أحدث هواتفكم؟",
    "كم تكلفة التوصيل؟",
    "هل لديكم عروض حالية؟",
    "كيف أتتبع طلبي؟",
    "ما هو الضمان المقدم؟",
    "ما طرق الدفع المقبولة؟",
    "أين يقع متجركم؟",
    "ما هي ساعات العمل؟",
    "هل لديكم آيفون متوفر؟",
    "ما هو أفضل سامسونج جالاكسي؟",
    "أنصحني بهاتف للألعاب",
    "أي هاتف بأقل من 5000 درهم؟",
    "قارن بين آيفون وسامسونج",
    "هل لديكم ملحقات؟",
    "كيف أرجع منتج؟"
  ],
  en: [
    "What are your latest phones?",
    "How much does delivery cost?",
    "Do you have current promotions?",
    "How to track my order?",
    "What is your warranty?",
    "What payment methods do you accept?",
    "Where is your store located?",
    "What are your opening hours?",
    "Do you have iPhones in stock?",
    "What's the best Samsung Galaxy?",
    "Recommend me a gaming phone",
    "Which phone under 5000 dirhams?",
    "Compare iPhone vs Samsung",
    "Do you have accessories?",
    "How to return a product?"
  ]
}

// متغيرات عامة
const conversationHistory = []
let currentLanguage = AI_CONFIG.DEFAULT_LANGUAGE
let recognition
let isListening = false

// ============== الوظائف الأساسية ==============

// تبديل نافذة المساعدة
function toggleHelp() {
  const helpContainer = document.getElementById("helpContainer")
  const helpBot = document.querySelector(".help-bot")

  if (helpContainer.style.display === "block") {
    helpContainer.style.animation = "scaleDown 0.3s ease-out"
    setTimeout(() => {
      helpContainer.style.display = "none"
      helpBot.style.transform = "scale(1)"
    }, 300)
  } else {
    helpContainer.style.display = "block"
    helpContainer.style.animation = "scaleUp 0.3s ease-out"
    helpBot.style.transform = "scale(0)"
    document.getElementById("userInput").focus()
  }
}

// عرض/إخفاء قائمة الأسئلة
function showQuestionsList() {
  const questionsContainer = document.getElementById("questionsListContainer")
  const questionsList = document.getElementById("questionsList")
  
  if (questionsContainer.style.display === "block") {
    questionsContainer.style.display = "none"
    return
  }

  questionsList.innerHTML = ""
  const questions = AVAILABLE_QUESTIONS[currentLanguage] || AVAILABLE_QUESTIONS.fr

  questions.forEach((question, index) => {
    const questionItem = document.createElement("div")
    questionItem.className = "question-item"
    questionItem.innerHTML = `
      <span class="question-number">${index + 1}</span>
      <span class="question-text">${question}</span>
    `
    questionItem.onclick = () => {
      document.getElementById("userInput").value = question
      questionsContainer.style.display = "none"
      sendMessage()
    }
    questionsList.appendChild(questionItem)
  })

  questionsContainer.style.display = "block"
}

// إرسال رسالة
async function sendMessage() {
  const userInput = document.getElementById("userInput")
  const message = userInput.value.trim()
  if (!message) return

  addUserMessage(message)
  userInput.value = ""

  showTypingIndicator()
  
  try {
    await new Promise(resolve => setTimeout(resolve, 1000 + Math.random() * 1000))
    const aiResponse = await getAIResponse(message)
    removeTypingIndicator()
    addBotMessage(aiResponse)
  } catch (error) {
    removeTypingIndicator()
    addBotMessage("Désolé, une erreur s'est produite. Veuillez réessayer.")
  }
}

// طرح سؤال سريع
function askQuestion(question) {
  document.getElementById("userInput").value = question
  sendMessage()
}

// ============== الوظائف المساعدة ==============

// إضافة رسالة المستخدم
function addUserMessage(message) {
  const chatMessages = document.getElementById("chatMessages")
  const messageDiv = document.createElement("div")
  messageDiv.className = "user-message"
  messageDiv.innerHTML = `
    <div class="message-content">
      <div class="user-avatar"><i class="fas fa-user"></i></div>
      <div class="message-text">${message}</div>
    </div>
  `
  chatMessages.appendChild(messageDiv)
  chatMessages.scrollTop = chatMessages.scrollHeight
}

// إضافة رسالة الروبوت
function addBotMessage(message) {
  const chatMessages = document.getElementById("chatMessages")
  const messageDiv = document.createElement("div")
  messageDiv.className = "bot-message"
  messageDiv.innerHTML = `
    <div class="message-content">
      <div class="bot-avatar"><i class="fas fa-robot"></i></div>
      <div class="message-text">
        <div class="response-text">${message.replace(/\n/g, "<br>")}</div>
        <small class="timestamp">${new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}</small>
      </div>
    </div>
  `
  chatMessages.appendChild(messageDiv)
  chatMessages.scrollTop = chatMessages.scrollHeight
}

// عرض مؤشر الكتابة
function showTypingIndicator() {
  const chatMessages = document.getElementById("chatMessages")
  const typingDiv = document.createElement("div")
  typingDiv.className = "bot-message"
  typingDiv.innerHTML = `
    <div class="message-content">
      <div class="bot-avatar"><i class="fas fa-robot"></i></div>
      <div class="message-text">
        <div class="typing-indicator">
          <span></span><span></span><span></span>
        </div>
      </div>
    </div>
  `
  chatMessages.appendChild(typingDiv)
  chatMessages.scrollTop = chatMessages.scrollHeight
  return typingDiv
}

// إزالة مؤشر الكتابة
function removeTypingIndicator() {
  const chatMessages = document.getElementById("chatMessages")
  const typingIndicator = chatMessages.querySelector(".typing-indicator")
  if (typingIndicator) {
    typingIndicator.closest(".bot-message").remove()
  }
}

// ============== الذكاء الاصطناعي والردود ==============

// الحصول على رد ذكي
async function getAIResponse(userMessage) {
  try {
    conversationHistory.push({ role: "user", content: userMessage })
    
    if (AI_CONFIG.API_KEY) {
      const messages = [
        { role: "system", content: AI_CONFIG.SYSTEM_PROMPT },
        ...conversationHistory.slice(-10)
      ]

      const response = await fetch(AI_CONFIG.API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${AI_CONFIG.API_KEY}`
        },
        body: JSON.stringify({
          model: AI_CONFIG.MODEL,
          messages: messages,
          max_tokens: 200,
          temperature: 0.7
        })
      })

      if (response.ok) {
        const data = await response.json()
        if (data.choices?.[0]?.message) {
          const aiResponse = data.choices[0].message.content.trim()
          conversationHistory.push({ role: "assistant", content: aiResponse })
          return aiResponse
        }
      }
    }

    return getSmartResponse(userMessage)
  } catch (error) {
    console.error("Erreur IA:", error)
    return getSmartResponse(userMessage)
  }
}

// نظام الردود الذكية المحلي
function getSmartResponse(message) {
  const language = detectLanguage(message)
  currentLanguage = language
  const lowerMessage = message.toLowerCase()

  // البحث عن المنتجات
  if (lowerMessage.includes("iphone") || lowerMessage.includes("samsung") || 
      lowerMessage.includes("xiaomi") || lowerMessage.includes("redmagic")) {
    const products = searchProducts(lowerMessage)
    return formatProductResults(products, language)
  }

  // قاعدة بيانات الردود
  const responses = {
    greetings: {
      keywords: ["bonjour", "salut", "hey", "hello", "hi", "مرحبا", "السلام"],
      responses: {
        fr: ["🌟 Bonjour et bienvenue chez Shuriken Phone Store !", "👋 Salut ! Comment puis-je vous aider ?"],
        ar: ["🌟 أهلاً وسهلاً بك في متجر شوريكن للهواتف!", "👋 مرحباً! كيف يمكنني مساعدتك؟"],
        en: ["🌟 Hello and welcome to Shuriken Phone Store!", "👋 Hi! How can I help you?"]
      }
    },
    latest_phones: {
      keywords: ["derniers", "nouveaux", "أحدث", "جديد", "latest", "new"],
      responses: {
        fr: ["📱 Nos derniers modèles: iPhone 16 Pro Max, Samsung S25 Ultra, Xiaomi 15 ULTRA..."],
        ar: ["📱 أحدث موديلاتنا: آيفون 16 برو ماكس، سامسونج S25 ألترا، شاومي 15 ألترا..."],
        en: ["📱 Our latest models: iPhone 16 Pro Max, Samsung S25 Ultra, Xiaomi 15 ULTRA..."]
      }
    },
    // ... (بقية الردود بنفس الهيكل)
    default: {
      fr: ["🤖 Comment puis-je vous aider ?", "💡 Posez-moi vos questions sur nos produits"],
      ar: ["🤖 كيف يمكنني مساعدتك؟", "💡 اسألني عن منتجاتنا وأسعارنا"],
      en: ["🤖 How can I help you?", "💡 Ask me about our products and prices"]
    }
  }

  // البحث عن رد مناسب
  for (const [category, data] of Object.entries(responses)) {
    if (category === "default") continue
    
    for (const keyword of data.keywords) {
      if (lowerMessage.includes(keyword)) {
        const langResponses = data.responses[language] || data.responses.fr
        return langResponses[Math.floor(Math.random() * langResponses.length)]
      }
    }
  }

  // رد افتراضي
  const defaultResponses = responses.default[language] || responses.default.fr
  return defaultResponses[Math.floor(Math.random() * defaultResponses.length)]
}

// ============== الوظائف المساعدة الأخرى ==============

// البحث عن المنتجات
function searchProducts(query) {
  query = query.toLowerCase()
  let results = []

  for (const [brand, products] of Object.entries(PRODUCTS_DATABASE)) {
    if (query.includes(brand)) {
      results = [...results, ...products]
      continue
    }

    for (const product of products) {
      if (product.name.toLowerCase().includes(query) || 
          product.description.toLowerCase().includes(query)) {
        results.push(product)
      }
    }
  }

  return results
}

// تنسيق نتائج البحث
function formatProductResults(products, language) {
  if (products.length === 0) {
    return language === "fr" ? "Aucun produit trouvé" : 
           language === "ar" ? "لم يتم العثور على منتجات" : "No products found"
  }

  let response = language === "fr" ? "📱 Produits trouvés:\n\n" :
                 language === "ar" ? "📱 المنتجات الموجودة:\n\n" : "📱 Found products:\n\n"

  products.slice(0, 5).forEach((product, index) => {
    response += `${index + 1}. ${product.name} - ${product.price} DH\n`
    response += `   ${product.description}\n\n`
  })

  return response
}

// اكتشاف اللغة
function detectLanguage(message) {
  if (/[\u0600-\u06FF]/.test(message)) return "ar"
  if (message.match(/hello|hi|what|how|you|are/i)) return "en"
  return "fr"
}

// التعرف الصوتي
function startVoiceRecognition() {
  if (isListening) {
    stopVoiceRecognition()
    return
  }

  try {
    recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)()
    recognition.lang = currentLanguage === "ar" ? "ar-SA" : "fr-FR"

    recognition.onstart = () => {
      isListening = true
      document.querySelector(".voice-btn").classList.add("listening")
      document.getElementById("userInput").placeholder = "🎤 Écoute en cours..."
    }

    recognition.onresult = (event) => {
      const transcript = event.results[0][0].transcript
      document.getElementById("userInput").value = transcript
      stopVoiceRecognition()
      setTimeout(() => sendMessage(), 300)
    }

    recognition.onerror = (event) => {
      console.error("Erreur reconnaissance vocale:", event.error)
      stopVoiceRecognition()
    }

    recognition.start()
  } catch (e) {
    alert("Votre navigateur ne prend pas en charge la reconnaissance vocale")
  }
}

function stopVoiceRecognition() {
  if (recognition) recognition.stop()
  isListening = false
  document.querySelector(".voice-btn").classList.remove("listening")
  document.getElementById("userInput").placeholder = "Écrivez votre question ici..."
}

// ============== التهيئة ==============

document.addEventListener("DOMContentLoaded", () => {
  // إعداد الأحداث
  document.querySelector(".help-bot").addEventListener("click", toggleHelp)
  document.querySelector(".close-help").addEventListener("click", toggleHelp)
  document.querySelector(".close-questions").addEventListener("click", () => {
    document.getElementById("questionsListContainer").style.display = "none"
  })
  document.querySelector(".questions-btn").addEventListener("click", showQuestionsList)
  document.getElementById("userInput").addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage()
  })

  // إعداد إخفاء/إظهار الأسئلة السريعة
  document.querySelector('.toggle-questions').addEventListener('click', function() {
    const content = document.querySelector('.quick-questions-content')
    content.classList.toggle('collapsed')
    this.innerHTML = content.classList.contains('collapsed') ? 
      '<i class="fas fa-chevron-down"></i>' : '<i class="fas fa-chevron-up"></i>'
  })

  // رسالة ترحيبية
  setTimeout(() => {
    addBotMessage("🌟 Bonjour ! Je suis l'assistant virtuel de Shuriken Phone Store. Comment puis-je vous aider aujourd'hui ?")
  }, 1000)

  // تحريك عيون الروبوت
  document.addEventListener("mousemove", (e) => {
    if (document.getElementById("helpContainer").style.display !== "block") {
      const robot = document.querySelector(".help-bot")
      const rect = robot.getBoundingClientRect()
      const robotX = rect.left + rect.width / 2
      const robotY = rect.top + rect.height / 2

      const angle = Math.atan2(e.pageX - robotX, e.pageY - robotY)
      const distance = Math.min(3, Math.sqrt(Math.pow(e.pageX - robotX, 2) + Math.pow(e.pageY - robotY, 2)) / 50)

      const leftEye = document.querySelector(".left-eye")
      const rightEye = document.querySelector(".right-eye")

      if (leftEye && rightEye) {
        leftEye.style.transform = `translate(${Math.sin(angle) * distance}px, ${Math.cos(angle) * distance}px)`
        rightEye.style.transform = `translate(${Math.sin(angle) * distance}px, ${Math.cos(angle) * distance}px)`
      }
    }
  })
})
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			       //darck mod
			   // Dark Mode Functionality - النسخة المعدلة
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

			</script>
			</body>
			</html>
