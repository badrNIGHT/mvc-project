<?php
session_start();
include 'config.php';

// التحقق من الصلاحيات
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// التحقق من أن المنتج يخص المستخدم
$stmt = $conn->prepare("SELECT * FROM produits WHERE id = ? AND vendeur_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: account.php");
    exit();
}

$product = $result->fetch_assoc();

// معالجة تحديث المنتج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $conn->real_escape_string($_POST['nom']);
    $description = $conn->real_escape_string($_POST['description']);
    $prix = floatval($_POST['prix']);

    $update_stmt = $conn->prepare("UPDATE produits SET nom = ?, description = ?, prix = ? WHERE id = ?");
    $update_stmt->bind_param("ssdi", $nom, $description, $prix, $product_id);
    
    if ($update_stmt->execute()) {
        header("Location: account.php?success=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f7;
            color: #1d1d1f;
            margin: 0;
            padding: 0;
        }
        
        .edit-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d2d2d7;
            border-radius: 8px;
            font-size: 16px;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .submit-btn {
            background: #0066cc;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        
        .submit-btn:hover {
            background: #004080;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="edit-container">
        <h1><i class="fas fa-edit"></i> Modifier Produit</h1>
        
        <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>">
            <div class="form-group">
                <label for="nom">Nom du produit</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($product['nom']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (DH)</label>
                <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?php echo htmlspecialchars($product['prix']); ?>" required>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
        </form>
    </div>
</body>
</html>