<?php
session_start();
$is_logged_in = isset($_SESSION["user_id"]);
$name = $is_logged_in ? $_SESSION["user_name"] : "";
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>TradeMe - דף פתיחה</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .hero-message {
      background-color: #f0f8ff;
      padding: 30px;
      margin: 30px auto;
      border-radius: 10px;
      text-align: center;
      max-width: 900px;
      font-size: 1.1em;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      line-height: 1.7;
    }

    .highlight {
      color: #0066cc;
      font-weight: bold;
    }

    .section-title {
      font-size: 1.4em;
      margin-top: 25px;
      color: #003366;
      font-weight: bold;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <header>
    <img src="logo.png" alt="לוגו TradeMe" style="height: 60px;">
    <nav>
      <ul id="nav-links">
        <?php if ($is_logged_in): ?>
          <li><a href="index.php">דף הבית</a></li>
          <li><a href="dashboard.php">דאשבורד</a></li>
          <li><a href="recommendations.php">המלצות</a></li>
          <li><a href="info.php">מידע פיננסי</a></li>
          <li><a href="news.php">חדשות</a></li>
          <li><a href="personal.php">התיק האישי</a></li>
          <li><a href="profile.php">פרופיל</a></li>
          <li><a href="contact.php">צור קשר</a></li>
          <li><a href="logout.php">התנתק</a></li>
        <?php else: ?>
          <li><a href="index.php">דף הבית</a></li>
          <li><a href="register.php">הרשמה</a></li>
          <li><a href="login.php">התחברות</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <main>
    <section class="intro" id="welcome-section">
      <?php if ($is_logged_in): ?>
        <h1>ברוך הבא חזרה, <?= htmlspecialchars($name) ?></h1>
        <p>התחל לעקוב אחרי מניות חדשות ולהתעדכן בפרופיל שלך.</p>
        <a href="dashboard.php"><button>מעבר לדאשבורד</button></a>
      <?php else: ?>
        <h1>ברוכים הבאים ל-<span class="highlight">TradeMe</span></h1>
        <p>המערכת שתעזור לך לנהל את הפרופיל המסחרי שלך בצורה חכמה ומותאמת אישית.</p>
      <?php endif; ?>

      <div class="hero-message">
        <div class="section-title">רקע</div>
        <p>
          בעידן של נגישות דיגיטלית – ההשקעות הפכו נגישות גם עבור אנשים מן השורה...
        </p>

        <div class="section-title">הבעיה</div>
        <p>מידע רב מדי, חוסר ידע במונחים פיננסיים...</p>

        <div class="section-title">מה אנחנו מציעים?</div>
        <p><strong>TradeMe</strong> הוא אתר ייחודי...</p>

        <div class="section-title">החזון שלנו</div>
        <p>להנגיש ידע פיננסי, לאפשר קבלת החלטות מושכלות...</p>

        <?php if ($is_logged_in): ?>
          <div class="section-title">מה מחכה לך כאן כמשתמש רשום?</div>
          <p>
            ✅ גישה לדף דאשבורד לניהול מניות <br>
            ✅ פרופיל אישי עם תחומי עניין <br>
            ✅ מילון מונחים, חדשות, המלצות ועוד
          </p>
        <?php endif; ?>
      </div>

      <button id="start-btn">לחץ כאן להתחלה</button>
    </section>
  </main>

  <footer>
    <p>צור קשר: guy.marcus@ruppin365.net</p>
    <button id="founder-btn">למעבר לדף המייסד</button>
  </footer>

  <script>
    $("#founder-btn").on("click", function () {
      window.location.href = "about_me.php";
    });

    $("#start-btn").on("click", function () {
      <?php if ($is_logged_in): ?>
        window.location.href = "dashboard.php";
      <?php else: ?>
        window.location.href = "login.php";
      <?php endif; ?>
    });
  </script>
</body>
</html>
