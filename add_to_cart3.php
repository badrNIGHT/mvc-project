<?php
declare(strict_types=1);
session_start();
include 'config.php';

// التحقق من تسجيل دخول المستخدم
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=يجب تسجيل الدخول أولاً');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    $userId = (int)$_SESSION['user_id'];
    $quantity = 1;
    $price = (float)$_POST['product_price'];
    $productName = $conn->real_escape_string($_POST['product_name']);
    $productDescription = $conn->real_escape_string($_POST['product_description'] ?? '');
    $productBrand = $conn->real_escape_string($_POST['product_brand'] ?? '');
    $productImage = $conn->real_escape_string($_POST['product_image'] ?? '');
    $storage = $conn->real_escape_string($_POST['storage'] ?? '');
    $ram = $conn->real_escape_string($_POST['ram'] ?? '');

    // تعطيل الالتزام التلقائي
    $conn->autocommit(false);

    $error = false;

    // 1. إضافة المنتج إلى السلة
    $query = "INSERT INTO panier (
                utilisateur_id, 
                produit_id, 
                nom_produit, 
                description, 
                marque, 
                prix, 
                quantite, 
                image, 
                stockage, 
                ram, 
                date_ajout
              ) VALUES (
                $userId, 
                $productId, 
                '$productName', 
                '$productDescription', 
                '$productBrand', 
                $price, 
                $quantity, 
                '$productImage', 
                '$storage', 
                '$ram', 
                NOW()
              ) ON DUPLICATE KEY UPDATE quantite = quantite + 1";
    
    if (!$conn->query($query)) {
        $error = true;
        error_log("Error in panier query: " . $conn->error);
    }

    // 2. تحديث سلة الجلسة
    if (!$error) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $productImage
            ];
        }

        $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
    }

    // تأكيد أو تراجع المعاملة
    if ($error) {
        $conn->rollback();
        header('Location: used_products.php?error=' . urlencode('حدث خطأ أثناء إضافة المنتج إلى السلة'));
    } else {
        $conn->commit();
        header('Location: used_products.php?success=تمت إضافة المنتج إلى السلة بنجاح');
    }

    $conn->autocommit(true);
    exit();
} else {
    header('Location: used_products.php?error=بيانات غير صالحة');
    exit();
}
?>