<?php
declare(strict_types=1);
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT nom, email, role, phone, age, gender, image, cover_image FROM utilisateurs WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Handle cover photo upload
$upload_dir = 'uploads/cover_images/';
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_cover'])) {
    if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] === UPLOAD_ERR_OK) {
        // Verify file type
        $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        $file_info = new finfo(FILEINFO_MIME_TYPE);
        $file_type = $file_info->file($_FILES['cover_file']['tmp_name']);
        
        if (array_key_exists($file_type, $allowed_types)) {
            // Create upload directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $ext = $allowed_types[$file_type];
            $new_filename = 'cover_' . $user_id . '_' . time() . '.' . $ext;
            $target_path = $upload_dir . $new_filename;

            // Move uploaded file
            if (move_uploaded_file($_FILES['cover_file']['tmp_name'], $target_path)) {
                // Update database
                $update_stmt = $conn->prepare("UPDATE utilisateurs SET cover_image = ? WHERE id = ?");
                $update_stmt->bind_param("si", $target_path, $user_id);
                
                if ($update_stmt->execute()) {
                    $user['cover_image'] = $target_path;
                    $_SESSION['success_message'] = "Cover photo updated successfully!";
                    header("Location: account.php");
                    exit();
                } else {
                    $upload_error = "Database update failed: " . $conn->error;
                    unlink($target_path); // Remove uploaded file if DB update failed
                }
            } else {
                $upload_error = "Failed to move uploaded file. Check directory permissions.";
            }
        } else {
            $upload_error = "Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.";
        }
    } else {
        $upload_error = "No file selected or upload error occurred. Error code: " . $_FILES['cover_file']['error'];
    }
}

// Get user's products (without images)
$products_stmt = $conn->prepare("SELECT id, nom, description, prix FROM produits WHERE vendeur_id = ?");
$products_stmt->bind_param("i", $user_id);
$products_stmt->execute();
$products_result = $products_stmt->get_result();

// Handle role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    $new_role = $user['role'] === 'vendeur' ? 'acheteur' : 'vendeur';
    $update_stmt = $conn->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_role, $user_id);
    if ($update_stmt->execute()) {
        $user['role'] = $new_role;
        $_SESSION['role'] = $new_role;
        $_SESSION['success_message'] = "Role changed successfully!";
        header("Location: account.php");
        exit();
    } else {
        $upload_error = "Failed to change role: " . $conn->error;
    }
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $delete_stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $delete_stmt->bind_param("i", $user_id);
    if ($delete_stmt->execute()) {
        session_destroy();
        header("Location: goodbye.php");
        exit();
    } else {
        $upload_error = "Failed to delete account: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - <?= htmlspecialchars($user['nom']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1877f2;
            --secondary: #f0f2f5;
            --text: #050505;
            --text-light: #65676b;
            --white: #ffffff;
            --danger: #e41e3f;
            --success: #28a745;
            --radius: 8px;
            --shadow: 0 1px 2px rgba(0,0,0,0.1);
            --shadow-hover: 0 2px 8px rgba(0,0,0,0.15);
            --transition: all 0.3s ease;
            
            /* Dark mode variables */
            --primary-dark: #121212;
            --primary-dark-light: #1e1e1e;
            --pure-white: #ffffff;
            --text-dark: #f1f1f1;
            --text-light-dark: #a0a0a0;
            --border-dark: #333;
        }
        
        [data-theme="dark"] {
            --secondary: var(--primary-dark);
            --white: var(--primary-dark-light);
            --text: var(--text-dark);
            --text-light: var(--text-light-dark);
            background-color: var(--primary-dark);
            color: var(--text-dark);
        }
        
        [data-theme="dark"] .profile-content,
        [data-theme="dark"] .products-section,
        [data-theme="dark"] .product-item {
            background-color: var(--primary-dark-light);
            border-color: var(--border-dark);
            color: var(--text-dark);
        }
        
        [data-theme="dark"] .btn-secondary {
            background: #333;
            color: var(--text-dark);
        }
        
        [data-theme="dark"] .detail-item {
            color: var(--text-light-dark);
        }
        
        [data-theme="dark"] .product-description {
            color: var(--text-light-dark);
        }
        
        [data-theme="dark"] .no-products {
            color: var(--text-light-dark);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
        }
        
        body {
            background-color: var(--secondary);
            color: var(--text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .profile-header {
            position: relative;
            height: 350px;
            background: <?= !empty($user['cover_image']) ? "url('".htmlspecialchars($user['cover_image'])."?".time()."')" : 'linear-gradient(to right, #4e54c8, #8f94fb)' ?>;
            background-size: cover;
            background-position: center;
        }
        
        .cover-upload {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        
        .cover-upload-btn {
            background: rgba(255,255,255,0.9);
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .cover-upload-btn:hover {
            background: var(--white);
            box-shadow: var(--shadow-hover);
        }
        
        .profile-picture {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid var(--white);
            object-fit: cover;
            left: 30px;
            bottom: -75px;
            box-shadow: var(--shadow-hover);
        }
        
        .profile-container {
            max-width: 1000px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }
        
        .profile-content {
            background: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow);
            margin-top: 90px;
            transition: background-color 0.3s ease;
        }
        
        .profile-name {
            font-size: 32px;
            margin-bottom: 15px;
        }
        
        .profile-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            transition: color 0.3s ease;
        }
        
        .role-badge {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .action-buttons {
            display: flex;
            gap: 12px;
            margin: 25px 0;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 16px;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #166fe5;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #e4e6eb;
            color: var(--text);
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .products-section {
            background: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow);
            margin-top: 30px;
            transition: background-color 0.3s ease;
        }
        
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .products-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .product-item {
            padding: 20px;
            border-radius: var(--radius);
            background: #f7f9fc;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .product-item:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }
        
        .product-title {
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .product-description {
            color: var(--text-light);
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }
        
        .product-price {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 12px;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
        }
        
        .no-products {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
            transition: color 0.3s ease;
        }
        
        .add-product-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .add-product-btn:hover {
            background: #166fe5;
            transform: translateY(-2px);
        }
        
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 12px 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-hover);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .toast.success {
            background: var(--success);
        }
        
        .toast.error {
            background: var(--danger);
        }
        
        @media (max-width: 768px) {
            .profile-header {
                height: 250px;
            }
            
            .profile-picture {
                width: 120px;
                height: 120px;
                bottom: -60px;
                left: 20px;
            }
            
            .profile-content {
                margin-top: 70px;
                padding: 20px;
            }
            
            .profile-name {
                font-size: 24px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Cover Photo Section -->
    <div class="profile-header">
        <form method="post" enctype="multipart/form-data" class="cover-upload">
            <input type="file" name="cover_file" id="cover_file" accept="image/*" style="display: none;" 
                   onchange="this.form.submit()">
            <button type="button" class="cover-upload-btn" onclick="document.getElementById('cover_file').click()">
                <i class="fas fa-camera"></i> Change Cover
            </button>
            <input type="hidden" name="upload_cover" value="1">
        </form>
    </div>
    
    <!-- Profile Picture -->
    <img src="<?= htmlspecialchars($user['image'] ?: 'default-avatar.png') ?>" alt="Profile Picture" class="profile-picture">
    
    <!-- Profile Content -->
    <div class="profile-container">
        <div class="profile-content">
            <h1 class="profile-name"><?= htmlspecialchars($user['nom']) ?></h1>
            <span class="role-badge">
                <i class="fas fa-user-tag"></i> <?= htmlspecialchars($user['role']) ?>
            </span>
            
            <div class="profile-details">
                <div class="detail-item">
                    <i class="fas fa-envelope"></i>
                    <span><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-phone"></i>
                    <span><?= htmlspecialchars($user['phone']) ?></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-birthday-cake"></i>
                    <span><?= (int)$user['age'] ?> ans</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-venus-mars"></i>
                    <span><?= htmlspecialchars($user['gender']) ?></span>
                </div>
            </div>
            
            <div class="action-buttons">
                <form method="post">
                    <button type="submit" name="change_role" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Change Role
                    </button>
                </form>
                
                <a href="edit_profile.php" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
                
                <form method="post" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                    <button type="submit" name="delete_account" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Products Section -->
        <div class="products-section">
            <h2 class="section-title">
                <i class="fas fa-box-open"></i> My Products
            </h2>
            
            <?php if ($products_result->num_rows > 0): ?>
                <div class="products-list">
                    <?php while ($product = $products_result->fetch_assoc()): ?>
                        <div class="product-item">
                            <h3 class="product-title"><?= htmlspecialchars($product['nom']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="product-price"><?= htmlspecialchars($product['prix']) ?> €</p>
                            <div class="product-actions">
                                <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <p>You haven't added any products yet.</p>
                    <a href="add_product.php" class="add-product-btn">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>
    
    <script>
        // Show toast messages
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast show ' + type;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Show messages from PHP
        <?php if (!empty($upload_error)): ?>
            showToast("<?= addslashes($upload_error) ?>", 'error');
        <?php elseif (isset($_SESSION['success_message'])): ?>
            showToast("<?= addslashes($_SESSION['success_message']) ?>", 'success');
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </script>
    
    <?php include 'footer.php'; ?>
</body>
</html>