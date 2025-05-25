<?php declare(strict_types=1); 
session_start();
include 'config.php';

// التحقق من أن المستخدم مسجل دخوله وهو بائع (طالب)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = 'add_product.php';
    header("Location: login.php");
    exit();
}

// جلب معلومات المستخدم للتأكد من أنه بائع
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'vendeur') {
    header("Location: unauthorized.php");
    exit();
}

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // تنظيف البيانات المدخلة
    $nom = $conn->real_escape_string($_POST['nom']);
    $description = $conn->real_escape_string($_POST['description']);
    $prix = floatval($_POST['prix']);
    $vendeur_id = $_SESSION['user_id'];
    $error = null;
    
    // معالجة رفع الصورة
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "image/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        
        // التحقق من أن الملف صورة
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $image_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $image_name;
            
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error = "Une erreur s'est produite lors du téléchargement de l'image.";
            }
        } else {
            $error = "Le fichier n'est pas une image.";
        }
    } else {
        $error = "Veuillez sélectionner une image.";
    }
    
    // إدخال المنتج في قاعدة البيانات
    if (!isset($error)) {
        $sql = "INSERT INTO produits (vendeur_id, nom, description, prix, image) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issds", $vendeur_id, $nom, $description, $prix, $image_name);
        
        if ($stmt->execute()) {
            header("Location: used_products.php?success=1");
            exit();
        } else {
            $error = "Erreur lors de l'ajout du produit: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Produit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary-bg: #ffffff;
        --primary-text: #1d1d1f;
        --secondary-text: #86868b;
        --accent-blue: #0066cc;
        --light-gray: #f5f5f7;
        --border-color: #d2d2d7;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --success-color: #28a745;
        --danger-color: #dc3545;
    }

    /* Dark Mode Variables */
    [data-theme="dark"] {
        --primary-bg: #1e1e1e;
        --primary-text: #f1f1f1;
        --secondary-text: #aaaaaa;
        --light-gray: #2d2d2d;
        --border-color: #444444;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        background-color: var(--light-gray);
        color: var(--primary-text);
        line-height: 1.6;
        padding-top: 120px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .form-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 2.5rem;
        background: var(--primary-bg);
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .form-header h1 {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--primary-text);
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .form-header h1 i {
        color: var(--accent-blue);
    }

    .form-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--accent-blue);
        border-radius: 3px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--primary-text);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: all 0.3s ease;
        background-color: var(--primary-bg);
        color: var(--primary-text);
    }

    .form-control:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        outline: none;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .btn-submit {
        display: inline-block;
        background-color: var(--accent-blue);
        color: white;
        border: none;
        padding: 0.75rem 1.75rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
    }

    .btn-submit:hover {
        background-color: #004d99;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
    }

    .image-upload-container {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        background-color: var(--light-gray);
    }

    .image-upload-container:hover {
        border-color: var(--accent-blue);
        background-color: rgba(0, 102, 204, 0.05);
    }

    .image-upload-label {
        display: block;
        cursor: pointer;
        font-weight: 500;
        color: var(--secondary-text);
        transition: all 0.3s ease;
    }

    .image-upload-label:hover {
        color: var(--accent-blue);
    }

    .image-upload-label i {
        font-size: 2rem;
        color: var(--accent-blue);
        display: block;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    #image {
        display: none;
    }

    .image-preview {
        max-width: 100%;
        max-height: 300px;
        margin: 1rem auto 0;
        display: none;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        object-fit: contain;
    }

    .message {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
    }

    .success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .file-info {
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: var(--secondary-text);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1.5rem;
            margin: 1rem;
        }
        
        body {
            padding-top: 90px;
        }
        
        .form-header h1 {
            font-size: 1.5rem;
        }
    }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-mobile-alt"></i> Ajouter un Nouveau Téléphone</h1>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom du téléphone</label>
                <input type="text" id="nom" name="nom" class="form-control" required placeholder="Ex: iPhone 13 Pro">
            </div>
            
            <div class="form-group">
                <label for="description">Description détaillée</label>
                <textarea id="description" name="description" class="form-control" required 
                          placeholder="Décrivez l'état, les spécifications et les accessoires inclus"></textarea>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (DH)</label>
                <input type="number" id="prix" name="prix" class="form-control" step="0.01" min="0" required 
                       placeholder="Ex: 2999.99">
            </div>
            
            <div class="image-upload-container">
                <label for="image" class="image-upload-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Cliquez pour sélectionner une image
                    <div class="file-info">Formats: JPEG, PNG (Max 2MB)</div>
                </label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <img id="imagePreview" class="image-preview" alt="Aperçu de l'image">
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-plus-circle"></i> Ajouter le Produit
            </button>
        </form>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // التحقق من حجم الملف
                if (file.size > 2 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux (max 2MB)');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    
                    // تحديث معلومات الملف
                    const fileInfo = document.querySelector('.file-info');
                    fileInfo.textContent = `${file.name} (${(file.size/1024/1024).toFixed(2)}MB)`;
                    fileInfo.style.color = 'var(--accent-blue)';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>