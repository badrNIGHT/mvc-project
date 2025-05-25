<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>عجلة الحظ - شوريكن</title>
  <style>
    body {
      text-align: center;
      background: #fcefdc;
      font-family: Arial;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    #container {
      position: relative;
      width: 300px;
      height: 300px;
      margin: 0 auto;
    }
    #shuriken {
      position: absolute;
      width: 150px;
      height: 150px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      transform-origin: center;
      transition: transform 4s cubic-bezier(0.33, 1, 0.68, 1);
      z-index: 5;
    }
    #pointer {
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 15px solid transparent;
      border-right: 15px solid transparent;
      border-bottom: 20px solid red;
      z-index: 10;
    }
    .product {
      position: absolute;
      width: 60px;
      font-size: 12px;
      text-align: center;
      transform: translate(-50%, -50%);
    }
    #message {
      margin-top: 20px;
      font-size: 20px;
      color: green;
    }
    button {
      padding: 10px 20px;
      font-size: 16px;
      margin-top: 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h2>عجلة الحظ - شوريكن النينجا</h2>

<div id="container">
  <img id="shuriken" src="image/shuriken1-Photoroom.png" alt="Shuriken">
  <div id="pointer"></div>

  <!-- المنتجات -->
  <div class="product" style="top:0;left:150px;">منتج 1</div>
  <div class="product" style="top:30px;left:260px;">منتج 2</div>
  <div class="product" style="top:150px;left:300px;">منتج 3</div>
  <div class="product" style="top:270px;left:260px;">منتج 4</div>
  <div class="product" style="top:300px;left:150px;">منتج 5</div>
  <div class="product" style="top:270px;left:40px;">منتج 6</div>
  <div class="product" style="top:150px;left:0;">منتج 7</div>
  <div class="product" style="top:30px;left:40px;">منتج 8</div>
</div>

<button onclick="spin()">اضغط لتدوير الشوريكن</button>
<div id="message"></div>

<script>
  const shuriken = document.getElementById('shuriken');
  const message  = document.getElementById('message');

  const products = ["منتج 1", "منتج 2", "منتج 3", "منتج 4",
                    "منتج 5", "منتج 6", "منتج 7", "منتج 8"];

  const presetResults = [1, 4, 7, 2]; // يمكنك تغيير ترتيب النتائج هنا
  let currentSpin = 0;
  let angle = 0;

  // دالة لحساب الزاوية المطلوبة لرأس الشوريكن ليتجه نحو المنتج
  function angleForProduct(index) {
    return 360 - (index * 45);
  }

  function spin() {
    const targetIdx = presetResults[currentSpin % presetResults.length];
    const targetAngle = angleForProduct(targetIdx);
    const fullTurns = 5 * 360;
    angle += fullTurns + targetAngle;

    shuriken.style.transform = `translate(-50%, -50%) rotate(${angle}deg)`;
    message.textContent = '';

    setTimeout(() => {
      message.textContent = `🎉 مبروك! لقد ربحت: ${products[targetIdx]}`;
      currentSpin++;
    }, 4000);
  }
</script>

</body>
</html>
