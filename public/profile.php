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
$name = $_SESSION["user_name"];
$email = $_SESSION["user_email"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $newName = $conn->real_escape_string($_POST["name"]);
  $newEmail = $conn->real_escape_string($_POST["email"]);
  $bio = $conn->real_escape_string($_POST["bio"]);
  $expertise = $conn->real_escape_string($_POST["expertise"]);
  $field = $conn->real_escape_string($_POST["field"]);
  $password = $_POST["password"];

  if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET name='$newName', email='$newEmail', password='$hashedPassword', bio='$bio', expertise='$expertise', field='$field' WHERE id=$userId");
  } else {
    $conn->query("UPDATE users SET name='$newName', email='$newEmail', bio='$bio', expertise='$expertise', field='$field' WHERE id=$userId");
  }

  $_SESSION["user_name"] = $newName;
  $_SESSION["user_email"] = $newEmail;
  echo "<script>alert('הפרטים עודכנו בהצלחה ✅'); window.location.href='profile.php';</script>";
  exit();
}

$res = $conn->query("SELECT bio, expertise, field FROM users WHERE id = $userId");
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8" />
  <title>הפרופיל שלי</title>
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
  <h2>עדכון פרטי משתמש</h2>
  <form method="post" class="profile-form">
    <label for="name">שם מלא:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required />

    <label for="email">אימייל:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />

    <label for="password">סיסמה חדשה:</label>
    <input type="password" id="password" name="password" />

    <label for="bio">רקע אישי:</label>
    <textarea id="bio" name="bio" rows="5" placeholder="ספר קצת על עצמך..."><?php echo htmlspecialchars($row['bio'] ?? ''); ?></textarea>

    <label for="expertise">רמת מקצועיות:</label>
    <select id="expertise" name="expertise">
      <option value="">בחר...</option>
      <?php
      $levels = ["מתחיל", "בינוני", "מתקדם", "מקצוען"];
      foreach ($levels as $level) {
        $selected = ($row['expertise'] ?? '') == $level ? 'selected' : '';
        echo "<option value='$level' $selected>$level</option>";
      }
      ?>
    </select>

    <label for="field">תחום עיסוק:</label>
    <select id="field" name="field">
      <option value="">בחר...</option>
      <?php
      $fields = ["לא", "אנליסט", "סוחר עצמאי", "בית השקעות", "אחר"];
      foreach ($fields as $f) {
        $selected = ($row['field'] ?? '') == $f ? 'selected' : '';
        echo "<option value='$f' $selected>$f</option>";
      }
      ?>
    </select>

    <button type="submit">שמור שינוים</button>
  </form>

  <div style="margin-top: 30px; text-align: center;">
    <p>רוצה לשתף המלצה על מניה?</p>
    <a href="recommend.php">
      <button type="button">כתוב המלצה</button>
    </a>
  </div>
</div>
</body>
<footer class="site-footer">
  <p>צור קשר: guy.marcus@ruppin365.net</p>
</footer>
</html>
