<?php declare(strict_types=1); 
// contact.php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $success = true;

    if ($success) {
        $feedback = "<div class='success-message'>Merci de nous avoir contactés ! Nous vous répondrons dans les plus brefs délais.</div>";
    } else {
        $feedback = "<div class='error-message'>Une erreur s'est produite lors de l'envoi de votre message. Veuillez réessayer.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Shuriken Phone Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Light mode variables */
        :root {
            --primary-dark: #ffffff;
            --primary-dark-light: #f5f5f7;
            --secondary-gold: #000000;
            --secondary-gold-light: #86868b;
            --accent-blue: #0066cc;
            --light-bg: #ffffff;
            --pure-white: #ffffff;
            --text-dark: #1d1d1f;
            --text-light: #86868b;
        }

        /* Dark mode variables */
        @media (prefers-color-scheme: dark) {
            :root {
                --primary-dark: #1d1d1f;
                --primary-dark-light: #2c2c2e;
                --secondary-gold: #f5f5f7;
                --secondary-gold-light: #a1a1a6;
                --accent-blue: #2997ff;
                --light-bg: #1d1d1f;
                --pure-white: #ffffff;
                --text-dark: #f5f5f7;
                --text-light: #a1a1a6;
            }
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-dark);
            transition: background-color 0.3s, color 0.3s;
        }

        .contact-container {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .contact-info, .contact-form {
            background: var(--primary-dark-light);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: background-color 0.3s;
        }

        .contact-info h2, .contact-form h2 {
            color: var(--accent-blue);
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .info-icon {
            font-size: 1.5rem;
            color: var(--accent-blue);
            margin-right: 15px;
            margin-top: 5px;
        }

        .info-content h3 {
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .info-content p {
            color: var(--text-light);
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--secondary-gold-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: var(--primary-dark-light);
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(41, 151, 255, 0.1);
            outline: none;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: var(--accent-blue);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background: #004d99;
            transform: translateY(-2px);
        }

        .success-message {
            background: #1e4620;
            color: #d4edda;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error-message {
            background: #5c1d23;
            color: #f8d7da;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .map-container {
            margin-top: 40px;
            grid-column: 1 / -1;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="contact-container">
        <div class="contact-info">
            <h2><i class="fas fa-info-circle"></i> Informations de contact</h2>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h3>Notre adresse</h3>
                    <p>IFMOTICA, Fes, Maroc</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="info-content">
                    <h3>Nos téléphones</h3>
                    <p>+212 656-704536</p>
                    <p>+212 624-017026</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Adresse email</h3>
                    <p>shurikenphone@gmail.com</p>
                    <p>shurikenphoneSupport@gmail.com</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>Heures d'ouverture</h3>
                    <p>Lundi à Vendredi : 09h00 - 18h00</p>
                    <p>Samedi : 10h00 - 16h00</p>
                </div>
            </div>
        </div>
        
        <div class="contact-form">
            <h2><i class="fas fa-paper-plane"></i> Envoyez-nous un message</h2>
            
            <?php if (isset($feedback)) echo $feedback; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Envoyer le message <i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
        
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=..." 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"></iframe>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>