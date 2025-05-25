<?php
declare(strict_types=1);
session_start();

require 'config.php';

// معرف المستخدم - عدله حسب نظامك
$userId = $_SESSION['user_id'] ?? 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['cart_count'] = 0;
    header('Location: cart.php');
    exit();
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $new_cart = [];
    $item_removed = false;

    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] !== $product_id) {
            $new_cart[] = $item;
        } else {
            $item_removed = true;
        }
    }

    // تحديث السلة في الجلسة
    $_SESSION['cart'] = $new_cart;
    $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));

    if ($item_removed) {
        // حذف المنتج من قاعدة البيانات في جدول panier
        if ($stmt = $conn->prepare("DELETE FROM panier WHERE utilisateur_id = ? AND produit_id = ?")) {
            $stmt->bind_param("ii", $userId, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['message'] = "تم حذف المنتج من السلة بنجاح";
    } else {
        $_SESSION['error'] = "المنتج غير موجود في السلة";
    }
}

header('Location: cart.php');
exit();
?>