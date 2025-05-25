<?php
declare(strict_types=1);
session_start();

require 'config.php';

header('Content-Type: application/json');

// Function to log actions
function logAction($message) {
    $logFile = 'cart_logs.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    // التحقق من طلب POST ووجود بيانات المنتج
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

    // تحديد معرف المستخدم
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new Exception('يجب تسجيل الدخول أولاً');
    }

    // التحقق من توفر المنتج الترويجي في جدول promotions
    $currentDate = date('Y-m-d');
    $stmt = $conn->prepare("SELECT id, quantite, description, marque, image, stockage, ram FROM promotions 
                           WHERE id = ? AND disponible = 1 AND date_debut <= ? AND date_fin >= ?");
    if (!$stmt) {
        throw new Exception('خطأ في إعداد الاستعلام');
    }
    $stmt->bind_param('iss', $productId, $currentDate, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('المنتج الترويجي غير موجود أو العرض غير متاح');
    }
    
    $productData = $result->fetch_assoc();
    $availableQuantity = (int)$productData['quantite'];
    $description = $productData['description'] ?? '';
    $marque = $productData['marque'] ?? '';
    $image = $productData['image'] ?? '';
    $stockage = $productData['stockage'] ?? null;
    $ram = $productData['ram'] ?? null;
    
    if ($availableQuantity <= 0) {
        throw new Exception('الكمية غير متوفرة حالياً');
    }
    $stmt->close();

    // التحقق من وجود المنتج في سلة المستخدم
    $stmt = $conn->prepare("SELECT id, quantite FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
    if (!$stmt) {
        throw new Exception('خطأ في إعداد استعلام السلة');
    }
    $stmt->bind_param('ii', $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // المنتج موجود، تحديث الكمية
        $cartItem = $result->fetch_assoc();
        $currentQuantity = (int)$cartItem['quantite'];
        
        if ($currentQuantity >= $availableQuantity) {
            throw new Exception('لا يمكن إضافة المزيد، وصلت إلى الحد الأقصى للكمية المتوفرة');
        }
        
        $newQuantity = $currentQuantity + 1;
        $stmt->close();

        $updateStmt = $conn->prepare("UPDATE panier SET quantite = ? WHERE id = ?");
        if (!$updateStmt) {
            throw new Exception('خطأ في إعداد استعلام التحديث');
        }
        $updateStmt->bind_param('ii', $newQuantity, $cartItem['id']);
        $updateStmt->execute();
        $updateStmt->close();

        $message = 'تم تحديث كمية المنتج الترويجي في السلة';
        logAction("User $userId updated quantity of product $productId to $newQuantity in cart.");
    } else {
        // المنتج غير موجود، إضافة جديد
        $stmt->close();
        
        $insertStmt = $conn->prepare("INSERT INTO panier 
            (utilisateur_id, produit_id, nom_produit, description, marque, prix, quantite, image, stockage, ram) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        if (!$insertStmt) {
            throw new Exception('خطأ في إعداد استعلام الإدخال: ' . $conn->error);
        }
        
        $quantity = 1;
        $insertStmt->bind_param(
            'iisssdissi',
            $userId,
            $productId,
            $productName,
            $description,
            $marque,
            $productPrice,
            $quantity,
            $image,
            $stockage,
            $ram
        );
        
        if (!$insertStmt->execute()) {
            throw new Exception('خطأ في تنفيذ استعلام الإدخال: ' . $insertStmt->error);
        }
        
        $insertStmt->close();
        $message = 'تمت إضافة المنتج الترويجي إلى السلة بنجاح';
        logAction("User $userId added product $productId ($productName) to cart with quantity $quantity.");
    }

    // تحديث عدد العناصر في السلة
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

    // تحديث جلسة المستخدم
    $_SESSION['cart_count'] = $total;

    // إرجاع الاستجابة
    echo json_encode([
        'success' => true,
        'message' => $message,
        'cartCount' => $total
    ]);

} catch (Exception $e) {
    error_log('add_to_cart2 error: ' . $e->getMessage());
    logAction("Failed to add/update product $productId for user $userId: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}