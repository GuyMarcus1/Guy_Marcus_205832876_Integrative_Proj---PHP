<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

$host = "sql309.byethost17.com";
$user = "b17_39144683";
$password = "LAJZhd!@FRR8zTE";
$database = "b17_39144683_sadkaDB";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$recommendations = [];
$sql = "SELECT r.id, r.stock_symbol, r.comment, r.rating, u.name AS user_name
        FROM recommendations r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.id DESC";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
  $recommendations[] = $row;
}
?>
<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8" />
  <title>המלצות משתמשים</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
  <h2>📣 המלצות שנכתבו על ידי משתמשים</h2>

  <div id="filter-bar">
    <label for="stock-input">סינון לפי מניה:</label>
    <input type="text" id="stock-input" placeholder="הקלד סימול מניה לדוגמה: AAPL" style="padding: 6px; margin-left: 10px;" />

    <label for="ownership-filter" style="margin-right: 30px;">האם הממליץ מחזיק בערך:</label>
    <select id="ownership-filter">
      <option value="all">הכל</option>
      <option value="yes">כן</option>
      <option value="no">לא</option>
    </select>
  </div>

  <ul id="recommendation-list" class="stock-list"></ul>
</div>

<script>
  const recommendations = <?php echo json_encode($recommendations); ?>;

  function renderRecommendations() {
    const stockInput = $("#stock-input").val().toLowerCase();
    const ownershipFilter = $("#ownership-filter").val();
    const $list = $("#recommendation-list");

    $list.empty();

    const filtered = recommendations.filter(rec => {
      const stockMatch = rec.stock_symbol.toLowerCase().includes(stockInput);
      const ownershipMatch = ownershipFilter === "all" || rec.rating === ownershipFilter;
      return stockMatch && ownershipMatch;
    });

    if (filtered.length === 0) {
      $list.append("<li>לא נמצאו המלצות התואמות לסינון</li>");
      return;
    }

    filtered.forEach(rec => {
      $list.append(`
        <li>
          <strong>${rec.stock_symbol}</strong> | נכתב על ידי: ${rec.user_name}<br>
          <em>${rec.rating === 'yes' ? 'מחזיק בערך' : 'לא מחזיק בערך'}</em><br>
          ${rec.comment}
        </li><hr>
      `);
    });
  }

  $(document).ready(function () {
    renderRecommendations();

    $("#stock-input, #ownership-filter").on("input change", renderRecommendations);
  });
</script>
</body>
</html>
