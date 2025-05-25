<?php
declare(strict_types=1);
session_start();
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluations des téléphones</title>
    <style>
     body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--light-bg);
        color: var(--text-dark);
        padding-top: 120px;
    }
    
    .container {
        max-width: 1400px;
        margin: 122px auto 0;
        padding: 20px;
    }
    
    h1 {
        text-align: center;
        color: var(--text-dark);
        margin-bottom: 50px;
        font-size: 2.5rem;
        position: relative;
    }
    
    h1:after {
        content: "";
        position: absolute;
        width: 80px;
        height: 3px;
        background-color: var(--accent-blue);
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .brand-filter {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin: 40px 0;
        gap: 10px;
    }
    
    .brand-btn {
        padding: 10px 25px;
        background-color: var(--primary-dark);
        color: var(--text-dark);
        border: 1px solid #d2d2d7;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
    }
    
    .brand-btn:hover, .brand-btn.active {
        background-color: var(--accent-blue);
        color: white;
        border-color: var(--accent-blue);
    }
    
    .phone-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 بطاقات في الصف */
    gap: 20px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}
 /**tswira bchkal zwin */
    .phone-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                    gap: 15px;
                    padding: 10px;
                }

                .phone-card {
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                }

                .phone-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }

               .phone-image-container {
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
        
        .phone-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* تغيير من contain إلى cover لتغطية المساحة بالكامل */
    object-position: center; /* لتوسيط الصورة */
    transition: transform 0.6s ease;
}
/***********************************/
        
        .phone-card:hover .phone-image {
            transform: scale(1.1);
        }

.phone-info {
    padding: 12px; /* تصغير الحشو */
}

.phone-name {
    font-size: 1.1rem; /* تصغير حجم الخط */
    margin-bottom: 6px;
}

.phone-price {
    font-size: 1.1rem; /* تصغير حجم الخط */
    margin-bottom: 10px;
}

.model-info-title {
    font-size: 1.1rem; /* تصغير حجم الخط */
}

.model-info-details {
    font-size: 0.8rem; /* تصغير حجم الخط */
}

.star {
    font-size: 22px; /* تصغير حجم النجوم */
}
    .star:hover, .star.active {
        color: #ffc107;
    }
    
    .no-phones {
        text-align: center;
        grid-column: 1 / -1;
        padding: 40px;
        color: var(--text-light);
    }
    
    /* متغيرات الألوان للوضع الليلي والنهاري */
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
    
    [data-theme="dark"] .phone-card,
    [data-theme="dark"] .brand-btn {
        background-color: var(--primary-dark-light);
        border-color: #333;
    }
    
    [data-theme="dark"] .brand-btn {
        color: var(--text-dark);
    }
    
    [data-theme="dark"] .brand-btn:hover, 
    [data-theme="dark"] .brand-btn.active {
        background-color: var(--accent-blue);
        color: white;
    }



@media (max-width: 1200px) {
    .phone-grid {
        grid-template-columns: repeat(3, 1fr); /* 3 بطاقات في الصف للشاشات المتوسطة */
    }
}

@media (max-width: 768px) {
    .phone-grid {
        grid-template-columns: repeat(2, 1fr); /* 2 بطاقة في الصف للشاشات الصغيرة */
    }
}

@media (max-width: 480px) {
    .phone-grid {
        grid-template-columns: 1fr; /* بطاقة واحدة في الصف للهواتف */
    }
}
    </style>
        <script>var currentUserId = <?php echo $userId; ?>;</script>
</head>
<body>
    <div class="container">
        <h1>Évaluations des téléphones intelligents</h1>
        
        <div class="brand-filter">
            <button class="brand-btn active" data-brand="all">Tous</button>
            <button class="brand-btn" data-brand="Samsung">Samsung</button>
            <button class="brand-btn" data-brand="Apple">iPhone</button>
            <button class="brand-btn" data-brand="Xiaomi">Xiaomi</button>
            <button class="brand-btn" data-brand="OPPO">OPPO</button>
            <button class="brand-btn" data-brand="HUAWEI">Huawei</button>
            <button class="brand-btn" data-brand="RedMagic">RedMagic</button>
            <button class="brand-btn" data-brand="Realme">Realme</button>
            <button class="brand-btn" data-brand="OnePlus">OnePlus</button>
            <button class="brand-btn" data-brand="ASUS">ASUS</button>
            <button class="brand-btn" data-brand="Nothing">Nothing</button>
        </div>
        
        <div class="phone-grid" id="phoneGrid">
            <!-- Les téléphones seront ajoutés ici via JavaScript -->
        </div>
    </div>
   


    <script>
        // Données des téléphones
        const phones = [
            // Samsung
            { id: 125, name: "Samsung Galaxy S25 Ultra", brand: "Samsung", price: 12999.99 * 2.6, storage: 512, ram: 12, image: "s25_ultra.jpg" },
            { id: 126, name: "Samsung Galaxy S25 Edge", brand: "Samsung", price: 14999.99 * 2.6, storage: 256, ram: 8, image: "s25_edge.jpg" },
            { id: 127, name: "Samsung Galaxy S24 Ultra", brand: "Samsung", price: 10999.99 * 2.6, storage: 256, ram: 12, image: "s24_ultra.jpg" },
            { id: 128, name: "Samsung Galaxy S24 FE", brand: "Samsung", price: 8999.99 * 2.6, storage: 128, ram: 8, image: "s24_fe.jpg" },
            { id: 129, name: "Samsung Galaxy Z Fold 6", brand: "Samsung", price: 11999.99 * 2.6, storage: 512, ram: 12, image: "z_fold_6.jpg" },
            { id: 130, name: "Samsung Galaxy Z Flip 6", brand: "Samsung", price: 5999.99 * 2.6, storage: 256, ram: 8, image: "z_flip_6.jpg" },
            { id: 131, name: "Samsung Galaxy Z Fold 5", brand: "Samsung", price: 4999.99 * 2.6, storage: 256, ram: 12, image: "z_fold_5.jpg" },
            { id: 132, name: "Samsung Galaxy Z Flip 5", brand: "Samsung", price: 3999.99 * 2.6, storage: 128, ram: 8, image: "z_flip_5.jpg" },
            { id: 133, name: "Samsung Galaxy A56 5G", brand: "Samsung", price: 9999.99 * 2.6, storage: 128, ram: 6, image: "a56_5g.jpg" },
            { id: 134, name: "Samsung Galaxy Note 20 Ultra", brand: "Samsung", price: 8999.99 * 2.6, storage: 256, ram: 12, image: "note_20_ultra.jpg" },
            { id: 135, name: "Samsung Galaxy A73", brand: "Samsung", price: 5499.99 * 2.6, storage: 128, ram: 6, image: "a73.jpg" },
            { id: 136, name: "Samsung Galaxy M54", brand: "Samsung", price: 4499.99 * 2.6, storage: 128, ram: 8, image: "m54.jpg" },
            
            // OPPO
            { id: 137, name: "OPPO Find X8 Pro", brand: "OPPO", price: 10999.99 * 2.6, storage: 256, ram: 12, image: "x8_pro.jpg" },
            { id: 138, name: "OPPO Find X7 Pro", brand: "OPPO", price: 8999.99 * 2.6, storage: 256, ram: 12, image: "x7pro.jpg" },
            { id: 139, name: "OPPO Find X6 Pro", brand: "OPPO", price: 6999.99 * 2.6, storage: 128, ram: 8, image: "x6_pro.jpg" },
            { id: 140, name: "OPPO Find X5 Pro", brand: "OPPO", price: 12999.99 * 2.6, storage: 512, ram: 12, image: "x5_pro.jpg" },
            { id: 141, name: "OPPO Find N5", brand: "OPPO", price: 7999.99 * 2.6, storage: 256, ram: 8, image: "n5.jpg" },
            { id: 142, name: "OPPO Find X3 Pro", brand: "OPPO", price: 5499.99 * 2.6, storage: 128, ram: 8, image: "x3_pro.jpg" },
            { id: 143, name: "OPPO N3 Flip", brand: "OPPO", price: 9499.99 * 2.6, storage: 256, ram: 8, image: "n3_flip.jpg" },
            { id: 144, name: "OPPO N2 Flip", brand: "OPPO", price: 7499.99 * 2.6, storage: 128, ram: 8, image: "n2_flip.jpg" },
            { id: 145, name: "OPPO Reno 13", brand: "OPPO", price: 8499.99 * 2.6, storage: 128, ram: 8, image: "reno_13.jpg" },
            { id: 146, name: "OPPO Reno 12", brand: "OPPO", price: 6499.99 * 2.6, storage: 128, ram: 6, image: "reno_12.jpg" },
            { id: 147, name: "OPPO Reno 11", brand: "OPPO", price: 9999.99 * 2.6, storage: 256, ram: 8, image: "reno_11.jpg" },
            { id: 148, name: "OPPO Reno 10", brand: "OPPO", price: 10999.99 * 2.6, storage: 256, ram: 12, image: "Reno_10.jpg" },
            
            // Xiaomi
            { id: 201, name: "Xiaomi 15 ULTRA", brand: "Xiaomi", price: 8999.99 * 2.6, storage: 512, ram: 12, image: "x15_ultra.jpg" },
            { id: 202, name: "Xiaomi 15", brand: "Xiaomi", price: 9999.99 * 2.6, storage: 256, ram: 8, image: "x15.jpg" },
            { id: 203, name: "Xiaomi 14T Pro", brand: "Xiaomi", price: 7999.99 * 2.6, storage: 256, ram: 8, image: "x14t_pro.jpg" },
            { id: 204, name: "Xiaomi 14 ULTRA", brand: "Xiaomi", price: 4999.99 * 2.6, storage: 128, ram: 6, image: "x14_ultra.jpg" },
            { id: 205, name: "Xiaomi MIX FLIP", brand: "Xiaomi", price: 6999.99 * 2.6, storage: 256, ram: 8, image: "mix_flip.jpg" },
            { id: 206, name: "Xiaomi Mix Fold 3", brand: "Xiaomi", price: 5999.99 * 2.6, storage: 512, ram: 12, image: "mix_fold3.jpg" },
            { id: 207, name: "Xiaomi 13T Pro", brand: "Xiaomi", price: 7499.99 * 2.6, storage: 256, ram: 8, image: "x13t_pro.jpg" },
            { id: 208, name: "Xiaomi 12T Pro Daniel Arsham Edition", brand: "Xiaomi", price: 6499.99 * 2.6, storage: 256, ram: 8, image: "x12t_arsham.jpg" },
            { id: 209, name: "POCO X7 Pro", brand: "Xiaomi", price: 9499.99 * 2.6, storage: 128, ram: 6, image: "poco_x7_pro.jpg" },
            { id: 210, name: "POCO M7 Pro 5G", brand: "Xiaomi", price: 10999.99 * 2.6, storage: 256, ram: 8, image: "poco_m7_pro.jpg" },
            { id: 211, name: "POCO F7 Ultra", brand: "Xiaomi", price: 5499.99 * 2.6, storage: 128, ram: 12, image: "poco_f7_ultra.jpg" },
            { id: 212, name: "Xiaomi Redmi 14C", brand: "Xiaomi", price: 4499.99 * 2.6, storage: 64, ram: 6, image: "redmi_14c.jpg" },
            
            // RedMagic
            { id: 301, name: "RedMagic 10s Pro", brand: "RedMagic", price: 8999.99 * 2.6, storage: 256, ram: 12, image: "redmagic_10s_pro.jpg" },
            { id: 302, name: "REDMAGIC Golden Saga Limited Edition", brand: "RedMagic", price: 7999.99 * 2.6, storage: 512, ram: 16, image: "redmagic_golden_saga.jpg" },
            { id: 303, name: "RedMagic 10", brand: "RedMagic", price: 7499.99 * 2.6, storage: 256, ram: 12, image: "redmagic_10.jpg" },
            { id: 304, name: "RedMagic 10 Air", brand: "RedMagic", price: 6999.99 * 2.6, storage: 128, ram: 8, image: "redmagic_10_air.jpg" },
            { id: 305, name: "RedMagic 9s pro", brand: "RedMagic", price: 6499.99 * 2.6, storage: 256, ram: 12, image: "redmagic_9s_pro.jpg" },
            { id: 306, name: "RedMagic 9", brand: "RedMagic", price: 5999.99 * 2.6, storage: 128, ram: 8, image: "redmagic_9.jpg" },
            { id: 307, name: "RedMagic 8s pro", brand: "RedMagic", price: 5499.99 * 2.6, storage: 256, ram: 12, image: "redmagic_8s_pro.jpg" },
            { id: 308, name: "RedMagic 8", brand: "RedMagic", price: 4999.99 * 2.6, storage: 128, ram: 8, image: "redmagic_8.jpg" },
            { id: 309, name: "RedMagic 7s pro", brand: "RedMagic", price: 9299.99 * 2.6, storage: 512, ram: 16, image: "redmagic_7s_pro.jpg" },
            { id: 310, name: "RedMagic 7", brand: "RedMagic", price: 6799.99 * 2.6, storage: 128, ram: 8, image: "redmagic_7.jpg" },
            { id: 311, name: "RedMagic 6s pro", brand: "RedMagic", price: 5799.99 * 2.6, storage: 256, ram: 12, image: "redmagic_6s_pro.jpg" },
            { id: 312, name: "RedMagic 6", brand: "RedMagic", price: 4499.99 * 2.6, storage: 128, ram: 8, image: "redmagic_6.jpg" },
            
            // Huawei
            { id: 313, name: "HUAWEI Mate XT ULTIMATE DESIGN", brand: "HUAWEI", price: 9999.99 * 2.6, storage: 512, ram: 12, image: "huawei_mate_xt.jpg" },
            { id: 314, name: "HUAWEI Mate X6", brand: "HUAWEI", price: 10999.99 * 2.6, storage: 512, ram: 12, image: "huawei_mate_x6.jpg" },
            { id: 315, name: "HUAWEI Mate X3", brand: "HUAWEI", price: 14999.99 * 2.6, storage: 1024, ram: 16, image: "huawei_mate_x3.jpg" },
            { id: 316, name: "HUAWEI Mate 50 Pro", brand: "HUAWEI", price: 6999.99 * 2.6, storage: 256, ram: 8, image: "huawei_mate_50_pro.jpg" },
            { id: 317, name: "HUAWEI Pura 70 Ultra", brand: "HUAWEI", price: 8999.99 * 2.6, storage: 512, ram: 12, image: "huawei_pura_70_ultra.jpg" },
            { id: 318, name: "HUAWEI Pura 70 Pro", brand: "HUAWEI", price: 2999.99 * 2.6, storage: 256, ram: 8, image: "huawei_pura_70_pro.jpg" },
            { id: 319, name: "HUAWEI nova 13 Pro", brand: "HUAWEI", price: 1999.99 * 2.6, storage: 128, ram: 8, image: "huawei_nova_13_pro.jpg" },
            { id: 320, name: "HUAWEI nova 12i", brand: "HUAWEI", price: 12999.99 * 2.6, storage: 256, ram: 8, image: "huawei_nova_12i.jpg" },
            { id: 321, name: "HUAWEI nova 12s", brand: "HUAWEI", price: 8999.99 * 2.6, storage: 256, ram: 12, image: "huawei_nova_12s.jpg" },
            { id: 322, name: "HUAWEI P60 Pro", brand: "HUAWEI", price: 9999.99 * 2.6, storage: 512, ram: 12, image: "huawei_p60_pro.jpg" },
            { id: 323, name: "HUAWEI nova 13", brand: "HUAWEI", price: 5999.99 * 2.6, storage: 128, ram: 8, image: "huawei_nova_13.jpg" },
            { id: 324, name: "HUAWEI nova Y72", brand: "HUAWEI", price: 4999.99 * 2.6, storage: 128, ram: 6, image: "huawei_nova_y72.jpg" },
            
            // Realme
            { id: 501, name: "Realme GT 7 Pro", brand: "Realme", price: 7999.99 * 2.6, storage: 256, ram: 12, image: "realme_gt7_pro.jpg" },
            { id: 502, name: "Realme GT 6", brand: "Realme", price: 6999.99 * 2.6, storage: 256, ram: 12, image: "realme_gt6.jpg" },
            { id: 503, name: "Realme GT 6T", brand: "Realme", price: 5999.99 * 2.6, storage: 128, ram: 8, image: "realme_gt6t.jpg" },
            { id: 504, name: "Realme GT NEO 3", brand: "Realme", price: 4999.99 * 2.6, storage: 128, ram: 8, image: "realme_gt_neo3.jpg" },
            { id: 505, name: "Realme GT NEO 3T", brand: "Realme", price: 3999.99 * 2.6, storage: 128, ram: 8, image: "realme_gt_neo3t.jpg" },
            { id: 506, name: "Realme Narzo 70 PRO 5G", brand: "Realme", price: 3499.99 * 2.6, storage: 128, ram: 6, image: "realme_narzo70_pro.jpg" },
            { id: 507, name: "Realme 14 Pro+ 5G", brand: "Realme", price: 4499.99 * 2.6, storage: 256, ram: 8, image: "realme_14_pro_plus.jpg" },
            { id: 508, name: "Realme 13 Pro+ 5G", brand: "Realme", price: 5499.99 * 2.6, storage: 256, ram: 12, image: "realme_13_pro_plus.jpg" },
            { id: 509, name: "Realme 12 PRO+ 5G", brand: "Realme", price: 5999.99 * 2.6, storage: 256, ram: 12, image: "realme_12_pro_plus.jpg" },
            { id: 510, name: "Realme C67", brand: "Realme", price: 4999.99 * 2.6, storage: 64, ram: 4, image: "realme_c67.jpg" },
            { id: 511, name: "Realme C75X", brand: "Realme", price: 2999.99 * 2.6, storage: 64, ram: 4, image: "realme_c75x.jpg" },
            { id: 512, name: "Realme NOTE 60X", brand: "Realme", price: 3999.99 * 2.6, storage: 128, ram: 6, image: "realme_note60x.jpg" },
            
            // OnePlus
            { id: 513, name: "OnePlus 13", brand: "OnePlus", price: 8999.99 * 2.6, storage: 512, ram: 16, image: "oneplus_13.jpg" },
            { id: 514, name: "OnePlus 13R", brand: "OnePlus", price: 7999.99 * 2.6, storage: 256, ram: 12, image: "oneplus_13r.jpg" },
            { id: 515, name: "OnePlus 12R", brand: "OnePlus", price: 6999.99 * 2.6, storage: 256, ram: 12, image: "oneplus_12r.jpg" },
            { id: 516, name: "OnePlus 11", brand: "OnePlus", price: 5999.99 * 2.6, storage: 256, ram: 12, image: "oneplus_11.jpg" },
            { id: 517, name: "OnePlus 11R", brand: "OnePlus", price: 4999.99 * 2.6, storage: 128, ram: 8, image: "oneplus_11r.jpg" },
            { id: 518, name: "OnePlus Nord N30", brand: "OnePlus", price: 4499.99 * 2.6, storage: 128, ram: 8, image: "oneplus_nord_n30.jpg" },
            { id: 519, name: "OnePlus 10 PRO", brand: "OnePlus", price: 10999.99 * 2.6, storage: 512, ram: 12, image: "oneplus_10_pro.jpg" },
            { id: 520, name: "OnePlus 10T", brand: "OnePlus", price: 6999.99 * 2.6, storage: 256, ram: 12, image: "oneplus_10t.jpg" },
            { id: 521, name: "OnePlus OPEN", brand: "OnePlus", price: 3999.99 * 2.6, storage: 256, ram: 12, image: "oneplus_open.jpg" },
            { id: 522, name: "OnePlus 9 PRO", brand: "OnePlus", price: 3499.99 * 2.6, storage: 256, ram: 12, image: "oneplus_9_pro.jpg" },
            { id: 523, name: "OnePlus 9", brand: "OnePlus", price: 999.99 * 2.6, storage: 128, ram: 8, image: "oneplus_9.jpg" },
            { id: 524, name: "OnePlus 8 PRO", brand: "OnePlus", price: 1499.99 * 2.6, storage: 128, ram: 8, image: "oneplus_8_pro.jpg" },
            
            // ASUS
            { id: 550, name: "ROG Phone 9 pro", brand: "ASUS", price: 12999.99 * 2.6, storage: 1024, ram: 24, image: "rog_phone9_pro.jpg" },
            { id: 551, name: "ROG Phone 9", brand: "ASUS", price: 11999.99 * 2.6, storage: 512, ram: 16, image: "rog_phone9.jpg" },
            { id: 552, name: "ROG Phone 8 pro", brand: "ASUS", price: 10999.99 * 2.6, storage: 512, ram: 16, image: "rog_phone8_pro.jpg" },
            { id: 553, name: "ROG Phone 8", brand: "ASUS", price: 9999.99 * 2.6, storage: 256, ram: 12, image: "rog_phone8.jpg" },
            { id: 554, name: "ROG Phone 7 ultimate", brand: "ASUS", price: 8999.99 * 2.6, storage: 512, ram: 16, image: "rog_phone7_ultimate.jpg" },
            { id: 555, name: "ROG Phone 7", brand: "ASUS", price: 7999.99 * 2.6, storage: 256, ram: 12, image: "rog_phone7.jpg" },
            { id: 556, name: "ROG Phone 6D Ultimate", brand: "ASUS", price: 11999.99 * 2.6, storage: 512, ram: 16, image: "rog_phone6d_ultimate.jpg" },
            { id: 557, name: "ZenFone 12 ULTRA", brand: "ASUS", price: 9999.99 * 2.6, storage: 512, ram: 16, image: "zenfone12_ultra.jpg" },
            { id: 558, name: "ZenFone 10", brand: "ASUS", price: 8999.99 * 2.6, storage: 256, ram: 12, image: "zenfone10.jpg" },
            { id: 559, name: "ZenFone 9", brand: "ASUS", price: 7999.99 * 2.6, storage: 256, ram: 12, image: "zenfone9.jpg" },
            { id: 560, name: "ZenFone 8", brand: "ASUS", price: 6999.99 * 2.6, storage: 128, ram: 8, image: "zenfone8.jpg" },
            
            // Nothing
            { id: 601, name: "Nothing Phone 1", brand: "Nothing", price: 6999.99 * 2.6, storage: 256, ram: 8, image: "nothing_phone1.jpg" },
            { id: 602, name: "Nothing Phone 2", brand: "Nothing", price: 7999.99 * 2.6, storage: 256, ram: 12, image: "nothing_phone2.jpg" },
            { id: 603, name: "Nothing phone 2a", brand: "Nothing", price: 999.99 * 2.6, storage: 128, ram: 6, image: "nothing_phone2a.jpg" },
            { id: 604, name: "Nothing phone 2a plus", brand: "Nothing", price: 1299.99 * 2.6, storage: 128, ram: 8, image: "nothing_phone2a_plus.jpg" },
            { id: 605, name: "Nothing Phone 2 pro", brand: "Nothing", price: 7499.99 * 2.6, storage: 512, ram: 12, image: "nothing_phone2_pro.jpg" },
            { id: 606, name: "Nothing phone 3", brand: "Nothing", price: 8999.99 * 2.6, storage: 256, ram: 12, image: "nothing_phone3.jpg" },
            { id: 607, name: "Nothing phone 3a", brand: "Nothing", price: 4999.99 * 2.6, storage: 128, ram: 8, image: "nothing_phone3a.jpg" },
            { id: 608, name: "Nothing phone 3a pro", brand: "Nothing", price: 5999.99 * 2.6, storage: 256, ram: 8, image: "nothing_phone3a_pro.jpg" },
            
            // Apple iPhone
            { id: 650, name: "iPhone 16 Pro Max", brand: "Apple", price: 12999.99 * 2.6, storage: 1024, ram: 8, image: "iphone16_pro_max.jpg" },
            { id: 651, name: "iPhone 16 Pro", brand: "Apple", price: 11999.99 * 2.6, storage: 512, ram: 8, image: "iphone16_pro.jpg" },
            { id: 652, name: "iPhone 16", brand: "Apple", price: 10999.99 * 2.6, storage: 256, ram: 6, image: "iphone16.jpg" },
            { id: 653, name: "iPhone 15 Pro Max", brand: "Apple", price: 9999.99 * 2.6, storage: 512, ram: 6, image: "iphone15_pro_max.jpg" },
            { id: 654, name: "iPhone 15 Pro", brand: "Apple", price: 8999.99 * 2.6, storage: 256, ram: 6, image: "iphone15_pro.jpg" },
            { id: 655, name: "iPhone 15", brand: "Apple", price: 7999.99 * 2.6, storage: 128, ram: 6, image: "iphone15.jpg" },
            { id: 656, name: "iPhone 14 Pro Max", brand: "Apple", price: 6999.99 * 2.6, storage: 256, ram: 6, image: "iphone14_pro_max.jpg" },
            { id: 657, name: "iPhone 14 Pro", brand: "Apple", price: 5999.99 * 2.6, storage: 128, ram: 6, image: "iphone14_pro.jpg" },
            { id: 658, name: "iPhone 14", brand: "Apple", price: 4999.99 * 2.6, storage: 128, ram: 6, image: "iphone14.jpg" },
            { id: 659, name: "iPhone 13 Pro Max", brand: "Apple", price: 3999.99 * 2.6, storage: 256, ram: 6, image: "iphone13_pro_max.jpg" },
            { id: 660, name: "iPhone 13 Pro", brand: "Apple", price: 3499.99 * 2.6, storage: 128, ram: 6, image: "iphone13_pro.jpg" },
            { id: 661, name: "iPhone 13", brand: "Apple", price: 2999.99 * 2.6, storage: 128, ram: 4, image: "iphone13.jpg" }
        ];


          // Variables for modal
        let currentPhoneId = null;
        let currentRating = 0;
        let currentPhoneName = '';
        const modal = document.getElementById('commentModal');
        const modalPhoneName = document.getElementById('modalPhoneName');
        const commentText = document.getElementById('commentText');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.querySelector('.cancel-btn');
        const submitBtn = document.querySelector('.submit-btn');
        const modalStars = document.querySelectorAll('.modal-rating .star');

        // Afficher tous les téléphones lors du chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            displayPhones(phones);
            
            // Ajouter un événement de clic pour les boutons de filtrage
            const brandButtons = document.querySelectorAll('.brand-btn');
            brandButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Supprimer la classe active de tous les boutons
                    brandButtons.forEach(btn => btn.classList.remove('active'));
                    // Ajouter la classe active au bouton sélectionné
                    this.classList.add('active');
                    
                    const brand = this.getAttribute('data-brand');
                    if (brand === 'all') {
                        displayPhones(phones);
                    } else {
                        const filteredPhones = phones.filter(phone => phone.brand === brand);
                        displayPhones(filteredPhones);
                    }
                });
            });
            
            // Ajouter un événement pour les évaluations par étoiles
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('star')) {
                    const stars = e.target.parentElement.querySelectorAll('.star');
                    const phoneCard = e.target.closest('.phone-card');
                    const phoneId = phoneCard.getAttribute('data-id');
                    const clickedIndex = Array.from(stars).indexOf(e.target);
                    const rating = clickedIndex + 1;
                    
                    stars.forEach((star, index) => {
                        if (index <= clickedIndex) {
                            star.classList.add('active');
                        } else {
                            star.classList.remove('active');
                        }
                    });
                    
                    // Envoyer la note au serveur
                    submitRating(phoneId, rating);
                }
            });
        });

       function displayPhones(phonesToDisplay) {
    const phoneGrid = document.getElementById('phoneGrid');
    phoneGrid.innerHTML = '';
    
    if (phonesToDisplay.length === 0) {
        phoneGrid.innerHTML = '<div class="no-phones">Aucun téléphone disponible pour cette marque</div>';
        return;
    }
    
    phonesToDisplay.forEach(phone => {
        const phoneCard = document.createElement('div');
        phoneCard.className = 'phone-card';
        phoneCard.setAttribute('data-id', phone.id);
        
        phoneCard.innerHTML = `
            <div class="phone-image-container">
                <img src="image/${phone.image}" alt="${phone.name}" class="phone-image" onerror="this.src='image/default-phone.jpg'">
                <div class="model-info-overlay">
                    <div class="model-info-title">${phone.name}</div>
                    <div class="model-info-details">
                        <div class="spec-item"><i class="fas fa-hdd"></i> Stockage: ${phone.storage}GB</div>
                        <div class="spec-item"><i class="fas fa-memory"></i> RAM: ${phone.ram}GB</div>
                        <div class="spec-item"><i class="fas fa-money-bill-wave"></i> Prix: ${phone.price.toFixed(2)} MAD</div>
                    </div>
                </div>
            </div>
            <div class="phone-info">
                <h3 class="phone-name">${phone.name}</h3>
                <div class="phone-price">${phone.price.toFixed(2)} MAD</div>
                <div class="rating">
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                </div>
            </div>
        `;
        
        phoneGrid.appendChild(phoneCard);
    });
}

        // Function to submit rating
        function submitRating(phoneId, rating) {
            fetch('submit_rating.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    produit_id: phoneId,
                    utilisateur_id: currentUserId, // Using the current user's ID
                    note: rating,
                    commentaire: '',
                    date_avis: new Date().toISOString().slice(0, 19).replace('T', ' ')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Évaluation enregistrée avec succès !');
                } else {
                    alert('Erreur : ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur s\'est produite lors de l\'envoi de l\'évaluation.');
            });
        }




  // Helper functions for stars
        function resetModalStars() {
            modalStars.forEach(star => {
                star.classList.remove('active');
            });
        }

        function highlightStars(stars, index) {
            stars.forEach((star, i) => {
                if (i <= index) {
                    star.classList.add('active');
                }
            });
        }


        
    </script>
<?php include 'footer.php'; ?>
</body>
</html>