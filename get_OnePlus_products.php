<?php
declare(strict_types=1); 
header('Content-Type: application/json');
require_once 'config.php';

try {
    $sql = "SELECT * FROM produits2 WHERE marque = 'OnePlus' AND quantite > 0";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = 'image/' . $row['image']; // تأكد من وجود الصور في هذا المسار
        $products[] = $row;
    }
    
    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>