<?php
declare(strict_types=1);
session_start();

require 'config.php';

header('Content-Type: application/json');

try {
    // تحقق من طلب POST ووجود بيانات المنتج
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'], $_POST['product_name'], $_POST['product_price'])) {
        throw new Exception('طلب غير صالح: يجب استخدام POST وإرسال بيانات المنتج كاملة');
    }

    // تنظيف البيانات الواردة
    $productId = (int)$_POST['product_id'];
    $productName = trim($_POST['product_name']);
    $productPrice = (float)$_POST['product_price'];

    if ($productId <= 0 || empty($productName) || $productPrice <= 0) {
        throw new Exception('بيانات المنتج غير صالحة');
    }

    // تحديد معرف المستخدم (مثلاً من الجلسة أو رقم ثابت إذا لا يوجد نظام تسجيل)
    // في حال لم يكن لديك نظام مستخدم حدد رقم 0 أو 1 مؤقتاً
    $userId = $_SESSION['user_id'] ?? 1; // عدل حسب نظامك

    // التحقق من توفر المنتج في جدول produits2
    $stmt = $conn->prepare("SELECT quantite FROM produits2 WHERE id = ?");
    if (!$stmt) {
        throw new Exception('خطأ في إعداد الاستعلام');
    }
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('المنتج غير موجود');
    }
    $productData = $result->fetch_assoc();
    $availableQuantity = (int)$productData['quantite'];
    if ($availableQuantity <= 0) {
        throw new Exception('الكمية غير متوفرة حاليا');
    }
    $stmt->close();

    // تحقق من وجود المنتج في سلة المستخدم مسبقاً
    $stmt = $conn->prepare("SELECT quantite FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
    if (!$stmt) {
        throw new Exception('خطأ في إعداد استعلام السلة');
    }
    $stmt->bind_param('ii', $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // المنتج موجود، تحديث الكمية إذا ممكن
        $cartItem = $result->fetch_assoc();
        $currentQuantity = (int)$cartItem['quantite'];
        if ($currentQuantity >= $availableQuantity) {
            throw new Exception('لا يمكن إضافة المزيد، وصلت إلى الحد الأقصى للكمية المتوفرة');
        }
        $newQuantity = $currentQuantity + 1;
        $stmt->close();

        $updateStmt = $conn->prepare("UPDATE panier SET quantite = ? WHERE utilisateur_id = ? AND produit_id = ?");
        if (!$updateStmt) {
            throw new Exception('خطأ في إعداد استعلام التحديث');
        }
        $updateStmt->bind_param('iii', $newQuantity, $userId, $productId);
        $updateStmt->execute();
        $updateStmt->close();

        $message = 'تم تحديث كمية المنتج في السلة';
    } else {
        // المنتج غير موجود، إضافة جديد
        $stmt->close();
        $insertStmt = $conn->prepare("INSERT INTO panier (utilisateur_id, produit_id, nom_produit, prix, quantite) VALUES (?, ?, ?, ?, ?)");
        if (!$insertStmt) {
            throw new Exception('خطأ في إعداد استعلام الإدخال');
        }
        $quantity = 1;
        $insertStmt->bind_param('iisdi', $userId, $productId, $productName, $productPrice, $quantity);
        $insertStmt->execute();
        $insertStmt->close();

        $message = 'تمت إضافة المنتج إلى السلة بنجاح';
    }

    // تحديث عدد العناصر في السلة لجميع المنتجات للمستخدم
    $countStmt = $conn->prepare("SELECT SUM(quantite) AS total FROM panier WHERE utilisateur_id = ?");
    if (!$countStmt) {
        throw new Exception('خطأ في استعلام عدد العناصر');
    }
    $countStmt->bind_param('i', $userId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total = 0;
    if ($countResult->num_rows > 0) {
        $row = $countResult->fetch_assoc();
        $total = (int)$row['total'];
    }
    $countStmt->close();

    echo json_encode([
        'success' => true,
        'message' => $message,
        'cartCount' => $total
    ]);

} catch (Exception $e) {
    error_log('add_to_cart error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}