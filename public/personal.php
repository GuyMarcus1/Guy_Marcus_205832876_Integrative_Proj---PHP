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
  die("שגיאה בחיבור למסד הנתונים: " . $conn->connect_error);
}

$userId = $_SESSION["user_id"];
$result = $conn->query("
  SELECT p.stock_symbol, s.name 
  FROM portfolio p
  JOIN stocks s ON p.stock_symbol = s.symbol
  WHERE p.user_id = $userId
");
$portfolio = [];
while ($row = $result->fetch_assoc()) {
  $portfolio[] = $row;
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8" />
  <title>התיק האישי שלי</title>
  <link rel="stylesheet" href="style.css" />
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
  <h2>📌 מניות במעקב</h2>
  <ul class="stock-list">
    <?php if (empty($portfolio)) : ?>
      <li>לא נוספו מניות למעקב</li>
    <?php else : ?>
      <?php foreach ($portfolio as $stock) : ?>
        <li class="stock-card">
          <h3><?= $stock['stock_symbol'] ?> - <?= $stock['name'] ?></h3>
          <form method="post" style="display:inline;">
            <input type="hidden" name="symbol" value="<?= $stock['stock_symbol'] ?>">
            <button type="submit" name="remove">🗑️ הסר</button>
          </form>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>

</body>
</html>

<?php
// טיפול במחיקה
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove"])) {
  $symbol = $conn->real_escape_string($_POST["symbol"]);
  $conn->query("DELETE FROM portfolio WHERE user_id = $userId AND stock_symbol = '$symbol'");
  header("Location: personal.php"); // רענון
  exit();
}
?>
