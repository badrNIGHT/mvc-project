<?php declare(strict_types=1); 
session_start();
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // التحقق من وجود المستخدم
    $stmt = $conn->prepare("SELECT id, mot_de_passe, role FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // التحقق من كلمة المرور
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            
            // إعادة التوجيه بناءً على الدور والصفحة السابقة
            if ($user['role'] === 'vendeur' && isset($_SESSION['redirect_to'])) {
                $redirect = $_SESSION['redirect_to'];
                unset($_SESSION['redirect_to']);
                header("Location: $redirect");
            } else {
                header("Location: homePage.php");
            }
            exit();
        } else {
            $error = "كلمة المرور غير صحيحة!";
        }
    } else {
        $error = "البريد الإلكتروني غير مسجل!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f5f5f7;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: 
                url('image/WhatsApp Image 2025-05-02 at 10.51.22 PM.jpeg') 
                no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
            border: 1px solid #d2d2d7;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #1d1d1f;
        }

        .login-container input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #d2d2d7;
            border-radius: 8px;
            transition: border 0.3s ease;
            background: #f5f5f7;
            color: #1d1d1f;
        }

        .login-container input:focus {
            border: 1px solid #86868b;
            outline: none;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background: #1d1d1f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .login-container button:hover {
            background: #0066cc;
        }

        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #86868b;
        }

        .register-link a {
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .login-container {
            animation: fadeIn 0.8s ease-in-out, float 6s ease-in-out infinite;
        }

        .policy-links {
    margin-top: 15px;
    font-size: 13px;
    color: #86868b;
}

.policy-links a {
    color: #0066cc;
    text-decoration: none;
    margin: 0 5px;
}

.policy-links a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
           <div class="policy-links">
        <a href="privacy_policy.php">Politique de confidentialité</a> |
        <a href="terms_conditions.php">Conditions générales</a>
    </div>
        </form>

        <div class="register-link">
            Don't have an account? <a href="register.php">Create one</a>
        </div>
    </div>
</body>
</html>