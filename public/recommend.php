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
$userName = $_SESSION["user_name"];
$success = false;
$error = "";

// משיכת כל המניות וכל הפורטפוליו של המשתמש
$stockSymbols = [];
$userPortfolio = [];

$stocksQuery = $conn->query("SELECT symbol FROM stocks");
while ($row = $stocksQuery->fetch_assoc()) {
  $stockSymbols[] = $row["symbol"];
}

$portfolioStmt = $conn->prepare("SELECT stock_symbol FROM portfolio WHERE user_id = ?");
$portfolioStmt->bind_param("i", $userId);
$portfolioStmt->execute();
$portfolioResult = $portfolioStmt->get_result();
while ($row = $portfolioResult->fetch_assoc()) {
  $userPortfolio[] = strtoupper($row["stock_symbol"]);
}
$portfolioStmt->close();

// טיפול בשליחת הטופס
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $stock = strtoupper(trim($_POST["stock"] ?? ""));
  $ownership = $_POST["ownership"] ?? "";
  $comment = trim($_POST["recommendText"] ?? "");

  if (!$stock || !$ownership || !$comment) {
    $error = "יש למלא את כל השדות";
  } elseif ($ownership === "yes" && !in_array($stock, $userPortfolio)) {
    $error = "לא ניתן לסמן שאתה מחזיק אם המניה לא בתיק האישי שלך";
  } else {
    $stmt = $conn->prepare("INSERT INTO recommendations (user_id, stock_symbol, comment, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $stock, $comment, $ownership);
    if ($stmt->execute()) {
      $success = true;
    } else {
      $error = "שגיאה בשליחה: " . $conn->error;
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8" />
  <title>כתיבת המלצה</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
</head>
<body>
  <header>
    <div>כתיבת המלצה</div>
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
    <h2>📝 המלצה על ערך</h2>

    <?php if ($error): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
      <p style="color: green;">✅ ההמלצה נשלחה בהצלחה</p>
      <script>setTimeout(() => window.location.href = "recommendations.php", 1500);</script>
    <?php endif; ?>

    <form method="post">
      <label for="stock">בחר מניה מהרשימה:</label>
      <input type="text" id="stock" name="stock" placeholder="הקלד שם מניה..." autocomplete="off" />

      <label>האם אתה מחזיק במניה הזו בתיק האישי?</label><br>
      <input type="radio" id="ownYes" name="ownership" value="yes">
      <label for="ownYes">כן</label>
      <input type="radio" id="ownNo" name="ownership" value="no">
      <label for="ownNo">לא</label><br><br>

      <label for="recommendText">כתוב את ההמלצה שלך:</label><br>
      <textarea id="recommendText" name="recommendText" rows="6"></textarea><br><br>

      <button type="submit">שלח המלצה</button>
    </form>
  </div>

  <script>
    const stockList = <?php echo json_encode($stockSymbols); ?>;
    $(function () {
      $("#stock").autocomplete({ source: stockList });
    });
  </script>
</body>
</html>
