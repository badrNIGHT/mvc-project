<?php
declare(strict_types=1); 
header('Content-Type: application/json');
require_once 'config.php';

try {
    $current_date = date('Y-m-d');
    $sql = "SELECT * FROM promotions 
            WHERE disponible = 1 
            AND date_debut <= '$current_date' 
            AND date_fin >= '$current_date'
            ORDER BY pourcentage DESC";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // لا تضف مساراً مسبقاً هنا، دعه للصفحة الرئيسية
        $row['image'] = $row['image']; // أو basename($row['image'])
        $products[] = $row;
    }
    
    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>