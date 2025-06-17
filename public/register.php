<?php

$host = "sql309.byethost17.com";
$user = "b17_39144683"; 
$password = "LAJZhd!@FRR8zTE";
$database = "b17_39144683_sadkaDB";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  die("שגיאה בחיבור למסד הנתונים: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    $error = "האימייל כבר קיים במערכת";
  } else {

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $name, $email, $password);
    if ($stmt->execute()) {
      header("Location: login.php?success=1");
      exit();
    } else {
      $error = "שגיאה בהוספת משתמש";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>הרשמה</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <img src="logo.png" alt="לוגו TradeMe" style="height: 60px;">
    <nav>
      <ul>
        <li><a href="index.php">דף הבית</a></li>
        <li><a href="register.php">הרשמה</a></li>
        <li><a href="login.php">התחברות</a></li>
      </ul>
    </nav>
  </header>

  <div class="form-container">
    <h2>📝 הרשמה</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="name" placeholder="שם מלא" required><br>
      <input type="email" name="email" placeholder="אימייל" required><br>
      <input type="password" name="password" placeholder="סיסמה" required><br>
      <button type="submit" class="show-more">הרשמה</button>
    </form>
    <p style="margin-top: 15px;">כבר יש לך חשבון? <a href="login.php">להתחברות</a></p>
  </div>
</body>
</html>
