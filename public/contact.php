<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>צור קשר - TradeMe</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .contact-form {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      background-color: #f0f8ff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      direction: rtl;
    }
    .contact-form h2 {
      text-align: center;
    }
    .contact-form label {
      display: block;
      margin-top: 10px;
    }
    .contact-form input, .contact-form textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .contact-form button {
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #0066cc;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <img src="logo.png" alt="לוגו TradeMe" style="height: 60px;">
    <nav>
    <ul>
      <li><a href="index.php">דף הבית</a></li>
      <li><a href="dashboard.php">דאשבורד</a></li>
      <li><a href="recommendations.php">המלצות</a></li>
      <li><a href="info.php">מידע פיננסי</a></li>
      <li><a href="news.php">חדשות</a></li>
      <li><a href="personal.php">התיק האישי</a></li>
      <li><a href="profile.php">פרופיל</a></li>
      <li><a href="contact.php">צור קשר</a></li>
      <li><a href="logout.php">התנתק</a></li>
    </ul>
    </nav>
  </header>

  <div class="contact-form">
    <h2>📨 צור קשר</h2>
    <form action="mailto:guy.marcus@ruppin365.net" method="POST" enctype="text/plain">
      <label for="name">שם מלא:</label>
      <input type="text" id="name" name="name" required>

      <label for="email">אימייל:</label>
      <input type="email" id="email" name="email" required>

      <label for="message">הודעה:</label>
      <textarea id="message" name="message" rows="5" required></textarea>

      <button type="submit">שלח</button>
    </form>
  </div>

<footer class="site-footer">
  <p>צור קשר: guy.marcus@ruppin365.net</p>
</footer>

  <script>
    $(document).ready(function () {
      const userId = localStorage.getItem("user_id");
      const personalStocks = localStorage.getItem(`personalStocks_${userId}`);

      $(document).on("click", "#logout-btn", function () {
        localStorage.clear();
        if (userId && personalStocks) {
          localStorage.setItem(`personalStocks_${userId}`, personalStocks);
        }
        window.location.href = "login.html";
      });
    });

    $("#founder-btn").on("click", function () {
      window.location.href = "about_me.php";
    });

  </script>
</body>
</html>
