<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone']; // إضافة هذا الحقل إذا كان موجودًا في الجدول
    $age = $_POST['age']; // إضافة هذا الحقل إذا كان موجودًا في الجدول
    $gender = $_POST['gender']; // إضافة هذا الحقل إذا كان موجودًا في الجدول
    $role = 'acheteur';

    // التحقق من البريد الإلكتروني المكرر
    $check_email = $conn->prepare("SELECT email FROM utilisateurs WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    
    if ($check_email->get_result()->num_rows > 0) {
        $error = "البريد الإلكتروني مسجل مسبقًا!";
    } else {
        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // إدخال البيانات (تعديل الاستعلام حسب الحقول الموجودة في جدولك)
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role, phone, age, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nom, $email, $hashed_password, $role, $phone, $age, $gender);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء التسجيل: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background: #f5f5f7;
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: 
        url('image/WhatsApp Image 2025-05-02 at 11.38.36 PM.jpeg') 
        no-repeat center center fixed;
      background-size: cover;
      position: relative;
      padding: 40px 20px;
    }

    .register-container {
      background: rgba(245, 245, 247, 0.95);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      animation: 
        slideUp 0.7s ease-out,
        float 6s ease-in-out infinite 0.7s;
      border: 1px solid rgba(210, 210, 215, 0.8);
      backdrop-filter: blur(5px);
    }

    .register-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .register-container input,
    .register-container select {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0;
      border: 1px solid #d2d2d7;
      border-radius: 8px;
      transition: all 0.3s;
      background: rgba(255, 255, 255, 0.9);
      color: #333;
      font-size: 15px;
    }

    .register-container input:focus {
      border-color: #999;
      outline: none;
      box-shadow: 0 0 0 3px rgba(153, 153, 153, 0.2);
    }

    .gender-options {
      margin: 10px 0;
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    .gender-options label {
      flex: 1;
      background: rgba(255, 255, 255, 0.9);
      padding: 12px;
      text-align: center;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s;
      color: #555;
      border: 1px solid #d2d2d7;
      font-size: 14px;
    }

    .gender-options input {
      display: none;
    }

    .gender-options input:checked + label {
      background: #666;
      color: white;
      font-weight: 500;
      border-color: #666;
    }

    .register-container button {
      width: 100%;
      padding: 14px;
      background: #666;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
      transition: all 0.3s;
      border: 1px solid #666;
      font-weight: 500;
    }

    .register-container button:hover {
      background: #555;
      border-color: #555;
      transform: translateY(-2px);
    }

    .login-link {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
      color: #666;
    }

    .login-link a {
      color: #666;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
    }

    .login-link a:hover {
      text-decoration: underline;
      color: #444;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
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
        transform: translateY(-8px);
      }
    }
  </style>
</head>
<body>

  <div class="register-container">
    <h2>Create Account</h2>
    <?php if (!empty($error)): ?>
        <div style="color:red;text-align:center;padding:10px"><?php echo $error ?></div>
    <?php endif; ?>
    
    <form action="register.php" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="number" name="age" placeholder="Age" required min="1">

      <div class="gender-options">
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Male</label>

        <input type="radio" id="female" name="gender" value="female">
        <label for="female">Female</label>
      </div>

      <button type="submit">Sign Up</button>
    </form>
    <div class="login-link">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>
</body>
</html>