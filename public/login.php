<?php
$host = "sql309.byethost17.com";
$user = "b17_39144683";
$password = "LAJZhd!@FRR8zTE";
$database = "b17_39144683_sadkaDB";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  die("שגיאה בחיבור למסד הנתונים: " . $conn->connect_error);
}

session_start();

// טיפול בטופס
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 1) {
    $stmt->bind_result($id, $name, $hashedPassword, $role);
    $stmt->fetch();
    if (password_verify($password, $hashedPassword)) {

      $_SESSION["user_id"] = $id;
      $_SESSION["user_name"] = $name;
      $_SESSION["user_email"] = $email;
      $_SESSION["user_role"] = $role;

      echo "<script>
        localStorage.setItem('userId', '$id');
        localStorage.setItem('userName', '$name');
        localStorage.setItem('userEmail', '$email');
        localStorage.setItem('userRole', '$role');
        alert('התחברת בהצלחה!');
        window.location.href = '" . ($role === 'admin' ? 'admin.php' : 'index.php') . "';
      </script>";
      exit();
    } else {
      $error = "סיסמה שגויה";
    }
  } else {
    $error = "משתמש לא נמצא";
  }
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>התחברות</title>
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
    <h2>🔐 התחברות</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($_GET["success"])) echo "<p style='color:green;'>נרשמת בהצלחה! עכשיו אפשר להתחבר</p>"; ?>
    <form method="post">
      <input type="email" name="email" placeholder="אימייל" required><br>
      <input type="password" name="password" placeholder="סיסמה" required><br>
      <button type="submit" class="show-more">התחבר</button>
    </form>
    <p style="margin-top: 15px;">אין לך משתמש? <a href="register.php">להרשמה</a></p>
  </div>
</body>
</html>
