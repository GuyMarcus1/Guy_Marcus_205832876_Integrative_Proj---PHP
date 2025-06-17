<?php
session_start();

// בדיקת התחברות
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$userName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>אודות המייסד</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .about-container {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 10px;
      text-align: center;
    }

    .about-photo {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
      border: 3px solid #0e76a8;
    }

    .about-text {
      font-size: 1.1em;
      line-height: 1.6;
      color: #333;
    }

    #skills-btn {
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #0e76a8;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #skills-list {
      margin-top: 15px;
      display: none;
      text-align: right;
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

<main class="about-container">
  <img src="About_Guy.jpg" alt="תמונת מייסד" class="about-photo">
  <h2>גיא מרקוס</h2>
  <p class="about-text">
    שלום, שמי גיא מרקוס ואני יזם בתחום הטכנולוגיה והפיננסים. את האתר TradeMe הקמתי מתוך רצון אמיתי לאפשר לאנשים גישה ברורה, נוחה ומובנית לעולם שוק ההון.
  </p>

  <button id="skills-btn">הצג את תחומי המומחיות שלי</button>
  <ul id="skills-list">
    <li>בוגר תואר בהנדסת מחשבים במכללת רופין</li>
    <li>ניתוח שווקים והבנת מגמות השקעה</li>
    <li>פיתוח מערכות Full Stack</li>
    <li>עיצוב חוויית משתמש (UX)</li>
    <li>עבודה עם מסדי נתונים וממשקי API</li>
    <li>הובלת פרויקטים וחשיבה יצירתית</li>
  </ul>
</main>

<footer>
  <p>צור קשר: guy.marcus@ruppin365.net</p>
</footer>

<script>
  $(document).ready(function () {
    $("#skills-btn").on("click", function () {
      $("#skills-list").slideToggle();
      $(this).text($("#skills-list").is(":visible") ? "הסתר מומחיות" : "הצג את תחומי המומחיות שלי");
    });
  });
</script>

</body>
</html>
