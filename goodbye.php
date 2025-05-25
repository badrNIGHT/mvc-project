<?php declare(strict_types=1); 
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Au revoir 👋</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #0f0f0f, #3a3a3a);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }

    .card {
      background: rgba(255, 255, 255, 0.05);
      padding: 50px 40px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(8px);
      max-width: 450px;
      animation: fadeIn 1s ease-in-out;
    }

    .card h1 {
      font-size: 3em;
      margin-bottom: 20px;
      color: #ffffff;
    }

    .card p {
      font-size: 1.2em;
      margin-bottom: 30px;
      color: #dddddd;
    }

    .card a {
      text-decoration: none;
      background: linear-gradient(to right, #555555, #222222);
      padding: 12px 30px;
      border-radius: 30px;
      color: white;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .card a:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .wave {
      font-size: 3em;
      animation: wave 2s infinite;
      display: inline-block;
    }

    @keyframes wave {
      0% { transform: rotate(0deg); }
      15% { transform: rotate(14deg); }
      30% { transform: rotate(-8deg); }
      40% { transform: rotate(14deg); }
      50% { transform: rotate(-4deg); }
      60% { transform: rotate(10deg); }
      70% { transform: rotate(0deg); }
      100% { transform: rotate(0deg); }
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="wave">👋</div>
    <h1>Au revoir !</h1>
    <p>Merci pour votre visite. Nous espérons vous revoir très bientôt !</p>
    <a href="login.php">Retour à l'accueil</a>
  </div>
</body>
</html>
