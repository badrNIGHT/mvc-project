<?php declare(strict_types=1); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Shuriken Phone - Leader des téléphones neufs et d'occasion</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 400vh;
      overflow-x: hidden;
      color: white;
    }

    .video-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      z-index: -1;
      overflow: hidden;
    }

    .video-background video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .main-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
      position: relative;
    }

    .logo-container {
      transition: all 0.8s ease-out;
    }

    .logo {
      width: 300px;
      height: 300px;
      object-fit: contain;
      filter: drop-shadow(0 0 20px rgba(0, 195, 255, 0.8));
      transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .welcome-message {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, 100px);
      width: 100%;
      max-width: 800px;
      text-align: center;
      opacity: 0;
      transition: all 1s ease;
      z-index: 10;
    }

    .welcome-message h1 {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      text-shadow: 0 0 15px rgba(0, 195, 255, 0.7);
      background: linear-gradient(90deg, #00d2ff, #3a7bd5);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-weight: 700;
      line-height: 1.2;
    }

    .welcome-message p {
      font-size: 1.7rem;
      color: rgba(247, 245, 245, 0.9);
      line-height: 1.5;
      max-width: 600px;
      margin: 0 auto;
      font-weight: 800;
    }

    .scroll-indicator {
      position: absolute;
      bottom: 50px;
      left: 50%;
      transform: translateX(-50%);
      animation: bounce 2s infinite;
      opacity: 0.7;
      font-size: 1.2rem;
    }

    .enter-button-container {
      position: fixed;
      bottom: 50px;
      left: 0;
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: all 0.8s ease;
      z-index: 100;
      opacity: 0;
      transform: translateY(20px);
    }

    .enter-button-title {
      font-size: 1.7rem;
      margin-bottom: 15px;
      transition: all 0.5s ease;
    }

    .enter-button {
      padding: 15px 40px;
      font-size: 1.3rem;
      background: linear-gradient(45deg, #00c6ff, #0072ff);
      color: white;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.8s ease;
      box-shadow: 0 5px 15px rgba(0, 118, 255, 0.4);
      font-weight: 600;
      text-decoration: none;
    }

    .enter-button:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 8px 25px rgba(0, 118, 255, 0.6);
    }

    /* New styles for content sections */
    .content-sections {
      position: relative;
      margin-top: 100vh;
      padding: 50px 0 150px;
    }

    .content-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 10%;
      opacity: 0;
      transform: translateY(50px);
      transition: all 1s ease;
      position: relative;
    }

    .section-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      max-width: 1200px;
    }

    .reversed {
      flex-direction: row-reverse;
    }

    .section-image {
      flex: 1;
      padding: 0 50px;
    }

    .section-image img {
      width: 100%;
      max-width: 500px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      transition: transform 0.5s ease;
    }

    .section-image img:hover {
      transform: scale(1.03);
    }

    .section-text {
      flex: 1;
      padding: 0 50px;
    }

    .section-text h2 {
      font-size: 2.5rem;
      margin-bottom: 20px;
      background: linear-gradient(90deg, #00d2ff, #3a7bd5);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .section-text p {
      font-size: 1.2rem;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.9);
    }

    .scroll-progress {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: rgba(255, 255, 255, 0.1);
      z-index: 100;
    }

    .scroll-progress-bar {
      height: 100%;
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      width: 0%;
      transition: width 0.1s ease;
    }

    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
      40% { transform: translateY(-20px) translateX(-50%); }
      60% { transform: translateY(-10px) translateX(-50%); }
    }
  </style>
</head>
<body>
  <div class="scroll-progress">
    <div class="scroll-progress-bar" id="scrollProgress"></div>
  </div>

  <div class="video-background">
    <video muted loop id="bgVideo">
      <source src="video/ads25.mp4" type="video/mp4" />
      Votre navigateur ne supporte pas les vidéos.
    </video>
  </div>

  <div class="main-container" id="mainContainer">
    <div class="logo-container" id="logoContainer">
      <img src="image/ChatGPT Image 17 mai 2025, 15_59_29.png" alt="Logo Shuriken Phone" class="logo" id="logo" />
    </div>

    <div class="welcome-message" id="welcomeMessage">
      <h1>Bienvenue chez Shuriken Phone</h1>
      <p>Le leader n°1 de la vente de téléphones neufs et d'occasion au Maroc</p>
    </div>

    <div class="scroll-indicator">
      Défiler vers le bas
      <div style="text-align: center; margin-top: 10px;">↓</div>
    </div>
  </div>

  <!-- New content sections -->
  <div class="content-sections">
    <!-- Section 1 -->
   <section class="content-section" id="section1">
  <div class="section-content">
    <div class="section-image">
      <img src="image/snp8.png" alt="Smartphones prestige">
    </div>
    <div class="section-text">
      <h2>L'Excellence Technologique</h2>
      <p>Découvrez notre collection 2025 avec le A17 Pro 3nm d'Apple, le Snapdragon 8 Gen 4 et le Dimensity 9400+.</p>

    </div>
  </div>
</section>

    <!-- Section 2 -->
   <section class="content-section" id="section2">
  <div class="section-content reversed">
    <div class="section-image">
      <img src="image/144hz1.png" alt="Téléphones gaming professionnels">
    </div>
    <div class="section-text">
      <h2>L'univers du gaming dans votre poche</h2>
      <p>Découvrez nos téléphones gaming haut de gamme des marques ASUS ROG Phone, POCO et Red Magic. Dotés de processeurs Snapdragon Elite, écrans 144Hz, systèmes de refroidissement avancés et batteries haute capacité pour des sessions de jeu intensives.</p>
    </div>
  </div>
</section>

    <!-- Section 3 -->
   <section class="content-section" id="section3">
  <div class="section-content">
    <div class="section-image">
      <img src="image/Capture d'écran 2025-05-19 031349.png" alt="Technologie de batterie avancée">
    </div>
    <div class="section-text">
      <h2>Autonomie Revolutionnaire</h2>
      <p>Nos smartphones intègrent les dernières technologies de batteries à haute densité énergétique .</p>
 
      <p class="disclaimer">Capacités réelles peuvent varier selon l'utilisation. Tests effectués en laboratoire.</p>
    </div>
  </div>
</section>
  </div>

  <div class="enter-button-container" id="enterButtonContainer">
    <div class="enter-button-title">Entrer sur le site</div>
    <a href="homepage.php" class="enter-button">Découvrir</a>
  </div>

  <script>
    const video = document.getElementById('bgVideo');
    const logoContainer = document.getElementById('logoContainer');
    const logo = document.getElementById('logo');
    const welcomeMessage = document.getElementById('welcomeMessage');
    const scrollProgress = document.getElementById('scrollProgress');
    const mainContainer = document.getElementById('mainContainer');
    const enterButtonContainer = document.getElementById('enterButtonContainer');
    const enterButtonTitle = document.querySelector('.enter-button-title');
    const enterButton = document.querySelector('.enter-button');
    const sections = document.querySelectorAll('.content-section');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    let isVideoPlaying = false;
    let lastScrollPosition = 0;
    let isScrollingDown = false;

    function initVideo() {
      video.pause();
      video.currentTime = 0;
    }

    function handleScrollAnimations(scrollPos, maxScroll) {
      const scrollFraction = scrollPos / maxScroll;

      // Logo animation (fade out and move up)
      if (scrollPos > 100) {
        const logoOpacity = 1 - (scrollPos / 300);
        const logoTranslateY = -scrollPos / 3;
        logo.style.opacity = logoOpacity > 0 ? logoOpacity : 0;
        logo.style.transform = `translateY(${logoTranslateY}px)`;
        logoContainer.style.opacity = logoOpacity > 0 ? logoOpacity : 0;
      } else {
        logo.style.opacity = 1;
        logo.style.transform = 'translateY(0)';
        logoContainer.style.opacity = 1;
      }

      // Welcome message animation (appear after scrolling down a bit)
      if (scrollPos > 50 && scrollPos < 300) {
        const messageOpacity = (scrollPos - 50) / 250;
        welcomeMessage.style.opacity = messageOpacity;
        welcomeMessage.style.transform = `translate(-50%, ${100 - (scrollPos / 3)}px)`;
      } 
      else if (scrollPos >= 300 && scrollPos < 600) {
        const messageOpacity = 1 - ((scrollPos - 300) / 300);
        welcomeMessage.style.opacity = messageOpacity > 0 ? messageOpacity : 0;
        welcomeMessage.style.transform = `translate(-50%, ${100 - (scrollPos / 3)}px)`;
      }
      else if (scrollPos >= 600) {
        welcomeMessage.style.opacity = 0;
      }
      else {
        welcomeMessage.style.opacity = 0;
      }

      // Scroll indicator
      if (scrollPos > 100) {
        scrollIndicator.style.opacity = 0;
      } else {
        scrollIndicator.style.opacity = 0.7;
      }

      // Sections animation
      sections.forEach((section, index) => {
        const sectionTop = section.getBoundingClientRect().top;
        const triggerPoint = window.innerHeight * 0.7;
        
        if (sectionTop < triggerPoint) {
          section.style.opacity = '1';
          section.style.transform = 'translateY(0)';
        }
      });

      // Enter button animation (show only after last section)
      const lastSection = sections[sections.length - 1];
      const lastSectionBottom = lastSection.getBoundingClientRect().bottom;
      
      if (lastSectionBottom < window.innerHeight * 0.8) {
        enterButtonContainer.style.opacity = '1';
        enterButtonContainer.style.transform = 'translateY(0)';
      } else {
        enterButtonContainer.style.opacity = '0';
        enterButtonContainer.style.transform = 'translateY(20px)';
      }

      // Progress bar
      scrollProgress.style.width = `${scrollFraction * 100}%`;
    }

    function handleVideoOnScroll() {
      const scrollPosition = window.scrollY;
      const documentHeight = document.body.scrollHeight;
      const windowHeight = window.innerHeight;
      const maxScroll = documentHeight - windowHeight;

      isScrollingDown = scrollPosition > lastScrollPosition;
      lastScrollPosition = scrollPosition;

      if (scrollPosition <= 0) {
        video.pause();
        video.currentTime = 0;
        isVideoPlaying = false;
      } else if (!isVideoPlaying) {
        video.play();
        isVideoPlaying = true;
      }

      handleScrollAnimations(scrollPosition, maxScroll);
    }

    document.addEventListener('DOMContentLoaded', () => {
      initVideo();

      window.addEventListener('scroll', handleVideoOnScroll);

      function resizeVideo() {
        video.style.height = window.innerHeight + 'px';
        mainContainer.style.height = window.innerHeight + 'px';
      }

      window.addEventListener('resize', resizeVideo);
      resizeVideo();
    });
  </script>
</body>
</html>