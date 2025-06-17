<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>חדשות כלכליות</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .news-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .news-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 20px;
      text-align: right;
    }
    .news-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 8px;
    }
    .category {
      font-weight: bold;
      color: #0073e6;
      margin-top: 8px;
    }
    .snippet {
      margin: 10px 0;
    }
    .date {
      color: #888;
      font-size: 0.9em;
    }
  </style>
</head>
<body>
<header>
  <img src="logo.png" alt="לוגו TradeMe" style="height: 60px;" />
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

<div class="form-container">
  <h2>📰 חדשות כלכליות מתחלפות</h2>
  <div id="news-grid" class="news-grid"></div>
</div>

<script>
$(document).ready(function () {
  fetch("get_news.php")
    .then(res => res.json())
    .then(data => renderNews(data))
    .catch(err => {
      console.error("שגיאה בטעינת חדשות:", err);
      $("#news-grid").html("<p>לא ניתן לטעון חדשות כעת</p>");
    });

  function renderNews(newsItems) {
    const container = $("#news-grid");
    container.empty();

    newsItems.forEach(news => {
      const card = `
        <div class="news-card">
          <img src="${news.image_url}" alt="תמונה" class="news-img">
          <h3>${news.title}</h3>
          <p class="category">${news.category}</p>
          <p class="snippet">${news.content.substring(0, 120)}...</p>
          <p class="date">${new Date(news.created_at).toLocaleDateString("he-IL")}</p>
        </div>`;
      container.append(card);
    });
  }
});
</script>

</body>
</html>
