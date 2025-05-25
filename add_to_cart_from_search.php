<?php declare(strict_types=1); 
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = (int) $_SESSION['user_id'];

if (isset($_POST['product_id'], $_POST['source'])) {
    $productId = (int) $_POST['product_id'];
    $source = $_POST['source'];

    try {
        $conn = new PDO("mysql:host=localhost;dbname=multi_vendeurs", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // تحديد الجدول
        $table = match ($source) {
            'produits' => 'produits',
            'produits2' => 'produits2',
            'promotions' => 'promotions',
            default => null
        };

        if (!$table) {
            die("Source invalide.");
        }

        // جلب بيانات المنتج
        $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die("Produit introuvable.");
        }

        // التحقق من وجود المنتج مسبقاً في السلة
        $checkStmt = $conn->prepare("SELECT id, quantite FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
        $checkStmt->execute([$userId, $productId]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // تحديث الكمية
            $newQty = $existing['quantite'] + 1;
            $updateStmt = $conn->prepare("UPDATE panier SET quantite = ? WHERE id = ?");
            $updateStmt->execute([$newQty, $existing['id']]);
        } else {
            // إدخال المنتج الجديد في السلة
            $insertStmt = $conn->prepare("INSERT INTO panier (utilisateur_id, produit_id, nom_produit, description, marque, prix, quantite, image, stockage, ram)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->execute([
                $userId,
                $product['id'],
                $product['nom'] ?? '',
                $product['description'] ?? '',
                $product['marque'] ?? '',
                $product['prix'],
                1,
                $product['image'] ?? '',
                $product['stockage'] ?? '',
                $product['ram'] ?? ''
            ]);
        }

        header("Location: cart.php");
        exit();

    } catch (PDOException $e) {
        echo "Erreur base de données: " . $e->getMessage();
    }
} else {
    echo "Paramètres manquants.";
}
?>
