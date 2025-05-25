<?php declare(strict_types=1);
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nom, email, phone, age, gender, image FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    if ($nom === '') $errors[] = "Le nom est requis.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if ($phone === '') $errors[] = "Le téléphone est requis.";
    if ($age === '' || !is_numeric($age) || (int)$age <= 0) $errors[] = "Âge invalide.";
   if (!in_array($gender, ['homme', 'femme'])) {
    $errors[] = "Genre invalide.";
}

    $imagePath = $user['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgName = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $newName = uniqid('img_', true) . "." . $ext;
            $target = "uploads/" . $newName;
            if (move_uploaded_file($tmp, $target)) {
                $imagePath = $target;
            } else {
                $errors[] = "Échec du téléchargement de l'image.";
            }
        } else {
            $errors[] = "Format d'image non autorisé.";
        }
    }

    if (empty($errors)) {
    $update = $conn->prepare("UPDATE utilisateurs SET nom = ?, email = ?, phone = ?, age = ?, gender = ?, image = ? WHERE id = ?");
    $age_int = (int)$age;
    $gender_int = $gender === 'homme' ? 0 : 1;
    $update->bind_param("sssissi", $nom, $email, $phone, $age_int, $gender_int, $imagePath, $user_id);



        if ($update->execute()) {
            $success = "Informations mises à jour avec succès.";
            $user['nom'] = $nom;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['age'] = $age_int;
            $user['gender'] = $gender;
            $user['image'] = $imagePath;
        } else {
            $errors[] = "Erreur lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Modifier Profil - <?php echo htmlspecialchars($user['nom']); ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --secondary: #f8f9fa;
        --text: #212529;
        --text-light: #6c757d;
        --white: #ffffff;
        --danger: #e63946;
        --success: #2a9d8f;
        --border: #dee2e6;
        --shadow: 0 4px 6px rgba(0,0,0,0.1);
        --radius: 12px;
        --transition: all 0.3s ease;
        
        --primary-dark: #121212;
        --primary-dark-light: #1e1e1e;
        --text-dark: #f1f1f1;
        --text-light-dark: #a0a0a0;
        --border-dark: #333;
    }

    [data-theme="dark"] {
        --secondary: var(--primary-dark);
        --text: var(--text-dark);
        --text-light: var(--text-light-dark);
        --border: var(--border-dark);
        background-color: var(--primary-dark);
        color: var(--text-dark);
    }

    body {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background: linear-gradient(145deg, #e2e8f0, #ffffff);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        transition: var(--transition);
    }

    [data-theme="dark"] body {
        background: linear-gradient(145deg, #121212, #1e1e1e);
    }

    .container {
        max-width: 600px;
        margin: 60px auto;
        background: var(--white);
        padding: 40px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    [data-theme="dark"] .container {
        background-color: var(--primary-dark-light);
        box-shadow: 0 12px 25px rgba(0,0,0,0.3);
    }

    h2 {
        text-align: center;
        color: var(--primary);
        margin-bottom: 30px;
        font-size: 28px;
        position: relative;
        padding-bottom: 15px;
    }

    h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--primary);
        border-radius: 3px;
    }

    label {
        display: block;
        font-weight: 600;
        margin-top: 20px;
        color: var(--text);
        transition: var(--transition);
    }

    .input-group {
        position: relative;
        margin-top: 8px;
    }

    .input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
    }

    .input-group input,
    .input-group select {
        width: 100%;
        padding: 14px 14px 14px 45px;
        border: 2px solid var(--border);
        border-radius: var(--radius);
        font-size: 15px;
        transition: var(--transition);
        background-color: var(--white);
        color: var(--text);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100%;
        box-sizing: border-box;
    }

    [data-theme="dark"] .input-group input,
    [data-theme="dark"] .input-group select {
        background-color: #2a2a2a;
        border-color: #444;
        color: var(--text-dark);
    }

    .input-group input:focus,
    .input-group select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        outline: none;
    }

    ::placeholder {
        color: var(--text-light);
        opacity: 1;
    }
    
    [data-theme="dark"] ::placeholder {
        color: var(--text-light-dark);
    }

    select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 1em;
    }

    [data-theme="dark"] select {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23f1f1f1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    }

    select option {
        color: var(--text);
        background-color: var(--white);
    }

    [data-theme="dark"] select option {
        color: var(--text-dark);
        background-color: var(--primary-dark-light);
    }

    .current-image {
        margin-top: 20px;
        text-align: center;
    }

    .current-image img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--border);
        transition: var(--transition);
    }

    .file-upload {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .file-upload-label {
        display: inline-block;
        padding: 10px 15px;
        background: var(--primary);
        color: white;
        border-radius: var(--radius);
        cursor: pointer;
        transition: var(--transition);
        font-size: 14px;
    }

    .file-upload-label:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }

    #file-name {
        color: var(--text-light);
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
    }

    button {
        width: 100%;
        padding: 15px;
        margin-top: 30px;
        font-size: 16px;
        background: var(--primary);
        color: var(--white);
        border: none;
        border-radius: var(--radius);
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    button:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .messages {
        margin-bottom: 25px;
    }

    .error, .success {
        padding: 15px;
        border-radius: var(--radius);
        font-weight: bold;
        margin-bottom: 15px;
        transition: var(--transition);
    }

    .error {
        background: #ffebee;
        border: 1px solid var(--danger);
        color: #b71c1c;
    }

    [data-theme="dark"] .error {
        background: #2a1a1a;
        border-color: #5c2c2c;
        color: #ff8a8a;
    }

    .success {
        background: #e8f5e9;
        border: 1px solid var(--success);
        color: #1b5e20;
        text-align: center;
    }

    [data-theme="dark"] .success {
        background: #1a2a2a;
        border-color: #2c5c5c;
        color: #8affb3;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 25px;
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .back-link:hover {
        color: var(--primary-light);
        text-decoration: none;
        transform: translateX(-5px);
    }

    @media (max-width: 768px) {
        .container {
            margin: 30px 15px;
            padding: 25px;
        }
        
        h2 {
            font-size: 24px;
        }
        
        #file-name {
            max-width: 150px;
        }
    }
</style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h2><i class="fas fa-user-edit"></i> Modifier Profil</h2>

        <div class="messages">
            <?php if ($errors): ?>
                <div class="error">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="post" enctype="multipart/form-data">
            <label for="nom">Nom complet</label>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nom" id="nom" required 
                       value="<?php echo htmlspecialchars($user['nom']); ?>"
                       placeholder="Entrez votre nom complet">
            </div>

            <label for="email">Adresse e-mail</label>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" required 
                       value="<?php echo htmlspecialchars($user['email']); ?>"
                       placeholder="Entrez votre adresse email">
            </div>

            <label for="phone">Téléphone</label>
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" id="phone" required 
                       value="<?php echo htmlspecialchars($user['phone']); ?>"
                       placeholder="Entrez votre numéro de téléphone">
            </div>

            <label for="age">Âge</label>
            <div class="input-group">
                <i class="fas fa-birthday-cake"></i>
                <input type="number" name="age" id="age" min="1" max="120" required 
                       value="<?php echo htmlspecialchars((string)$user['age']); ?>"
                       placeholder="Entrez votre âge">
            </div>

            <label for="gender">Genre</label>
            <div class="input-group">
                <i class="fas fa-venus-mars"></i>
                <select name="gender" id="gender" required>
    <option value="">-- Sélectionnez --</option>
    <option value="homme" <?php if ($user['gender'] === 'homme') echo 'selected'; ?>>Homme</option>
    <option value="femme" <?php if ($user['gender'] === 'femme') echo 'selected'; ?>>Femme</option>
</select>

            </div>

            <label>Photo de profil</label>
            <div class="file-upload">
                <label for="image" class="file-upload-label">
                    <i class="fas fa-upload"></i> Choisir une image
                </label>
                <span id="file-name">Aucun fichier sélectionné</span>
                <input type="file" name="image" id="image" accept="image/*" style="display: none;">
            </div>
            
            <div class="current-image">
                <p style="margin-bottom: 10px; color: var(--text-light);">Image actuelle:</p>
                <?php if (!empty($user['image']) && file_exists($user['image'])): ?>
                    <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="Photo actuelle">
                <?php else: ?>
                    <img src="default-avatar.png" alt="Pas de photo">
                <?php endif; ?>
            </div>

            <button type="submit"><i class="fas fa-save"></i> Enregistrer les modifications</button>
        </form>

        <a class="back-link" href="account.php"><i class="fas fa-arrow-left"></i> Retour au profil</a>
    </div>

    <script>
        // Show selected file name
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
            document.getElementById('file-name').textContent = fileName;
        });

        // Ensure text visibility and proper overflow handling
        document.querySelectorAll('input, select').forEach(element => {
            // Initialize colors based on theme
            const updateColors = () => {
                const html = document.documentElement;
                if (html.getAttribute('data-theme') === 'dark') {
                    element.style.color = getComputedStyle(html).getPropertyValue('--text-dark');
                } else {
                    element.style.color = getComputedStyle(html).getPropertyValue('--text');
                }
            };
            
            // Handle text overflow
            const handleOverflow = () => {
                if (element.scrollWidth > element.clientWidth) {
                    element.title = element.value;
                } else {
                    element.removeAttribute('title');
                }
            };
            
            // Set up event listeners
            element.addEventListener('input', function() {
                this.style.color = '';
                handleOverflow();
            });
            
            // Initial setup
            updateColors();
            handleOverflow();
            
            // Watch for theme changes
            const observer = new MutationObserver(updateColors);
            observer.observe(document.documentElement, { 
                attributes: true, 
                attributeFilter: ['data-theme'] 
            });
        });

        // Animate messages on load
        <?php if ($errors || $success): ?>
            setTimeout(() => {
                const messages = document.querySelector('.messages');
                if (messages) {
                    messages.style.opacity = '1';
                    messages.style.transform = 'translateY(0)';
                }
            }, 100);
        <?php endif; ?>
    </script>
</body>
</html>