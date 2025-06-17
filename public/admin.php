<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  echo "<script>alert('אין לך הרשאה לצפות בדף זה'); window.location.href='dashboard.php';</script>";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && strpos($_SERVER['CONTENT_TYPE'] ?? '', "application/json") === 0) {
  header("Content-Type: application/json");

  $conn = new mysqli("sql309.byethost17.com", "b17_39144683", "LAJZhd!@FRR8zTE", "b17_39144683_sadkaDB");
  if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "שגיאת חיבור למסד הנתונים"]);
    exit();
  }

  $data = json_decode(file_get_contents("php://input"), true);
  $action = $data["action"] ?? "";

  switch ($action) {
    case "update_role":
      $user_id = $data["user_id"] ?? null;
      $new_role = $data["role"] ?? null;
      if ($user_id && $new_role) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        $success = $stmt->execute();
        echo json_encode(["success" => $success]);
      } else {
        echo json_encode(["success" => false, "message" => "חסר user_id או role"]);
      }
      break;

    case "delete_user":
      $user_id = $data["user_id"] ?? null;
      if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $success = $stmt->execute();
        echo json_encode(["success" => $success]);
      } else {
        echo json_encode(["success" => false, "message" => "חסר user_id למחיקה"]);
      }
      break;

    case "delete_recommendation":
      $rec_id = $data["rec_id"] ?? null;
      if ($rec_id) {
        $stmt = $conn->prepare("DELETE FROM recommendations WHERE id = ?");
        $stmt->bind_param("i", $rec_id);
        $success = $stmt->execute();
        echo json_encode(["success" => $success]);
      } else {
        echo json_encode(["success" => false, "message" => "חסר rec_id למחיקה"]);
      }
      break;

    default:
      echo json_encode(["success" => false, "message" => "לא סופקה פעולה תקפה"]);
  }

  $conn->close();
  exit();
}

?>
<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8">
  <title>ניהול האתר - אדמין</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f5f7f9;
      overflow-x: hidden;
    }
    html, body {
      height: 100%;
    }
    .form-container {
      max-width: 1300px;
      margin: auto;
      padding: 20px;
    }
    .admin-panel {
      max-width: 100%;
      margin: 40px auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      padding-bottom: 40px;
    }
    .admin-panel h3 { margin-top: 20px; color: #0e76a8; }
    .admin-flex-container {
      display: flex; gap: 40px; justify-content: space-between; flex-wrap: wrap; margin-top: 20px;
    }
    .admin-flex-container > div {
      flex: 1 1 45%; min-width: 400px; background-color: #fff;
      padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    table {
      table-layout: fixed;
    }
    .user-table, .rec-list {
      width: 100%; border-collapse: collapse; margin-top: 10px;
    }
    .user-table th, .user-table td,
    .rec-list th, .rec-list td {
      border-bottom: 1px solid #ccc; padding: 8px; text-align: right;
      word-break: break-word;
    }
    .user-table th, .rec-list th { background-color: #f0f0f0; }
    .action-btn {
      background-color: crimson; color: white; border: none;
      padding: 6px 12px; border-radius: 5px; cursor: pointer;
    }
    .action-btn:hover { background-color: darkred; }
    .role-select {
      padding: 4px; border-radius: 5px;
    }
    input[type="text"] {
      padding: 6px; width: 100%; margin-bottom: 10px; box-sizing: border-box;
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

  <main class="form-container admin-panel">
    <h2>ברוך הבא לפאנל ניהול</h2>

    <div class="admin-flex-container">
      <div class="rec-section">
        <h3>📌 סך כל ההמלצות: <span id="rec-count">0</span></h3>
        <input type="text" id="rec-user-filter" placeholder="סנן המלצות לפי שם משתמש...">
        <table class="rec-list">
          <thead>
            <tr><th>משתמש</th><th>מניה</th><th>המלצה</th><th>מחיקה</th></tr>
          </thead>
          <tbody id="rec-table-body"></tbody>
        </table>
      </div>

      <div class="user-section">
        <h3>👥 משתמשים רשומים</h3>
        <input type="text" id="user-search" placeholder="חפש לפי אימייל...">
        <table class="user-table">
          <thead>
            <tr><th>שם</th><th>אימייל</th><th>תפקיד</th><th>פעולה</th></tr>
          </thead>
          <tbody id="user-table-body"></tbody>
        </table>
      </div>
    </div>
  </main>

<script>
$(document).ready(function () {
  fetch("get_recommendations.php")
    .then(res => res.json())
    .then(data => {
      $("#rec-count").text(data.length);
      const table = $("#rec-table-body");
      table.empty();
      data.forEach(rec => {
        table.append(`
          <tr>
            <td>${rec.user_name}</td>
            <td>${rec.stock_symbol}</td>
            <td>${rec.comment}</td>
            <td><button class="action-btn delete-rec" data-id="${rec.id}">🗑</button></td>
          </tr>`);
      });
    });

  fetch("get_users.php")
    .then(res => res.json())
    .then(data => {
      const table = $("#user-table-body");
      table.empty();
      data.forEach(user => {
        table.append(`
          <tr>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td><select class="role-select" data-id="${user.id}">
              <option value="user" ${user.role === 'user' ? 'selected' : ''}>user</option>
              <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>admin</option>
            </select></td>
            <td><button class="action-btn delete-user" data-id="${user.id}">🗑</button></td>
          </tr>`);
      });
    });

  $(document).on("change", ".role-select", function () {
    const userId = $(this).data("id");
    const newRole = $(this).val();
    fetch("admin.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "update_role", user_id: userId, role: newRole })
    }).then(res => res.json()).then(data => {
      alert(data.success ? "התפקיד עודכן בהצלחה" : "שגיאה: " + data.message);
    });
  });

  $(document).on("click", ".delete-user", function () {
    const id = $(this).data("id");
    if (confirm("למחוק את המשתמש?")) {
      fetch("admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "delete_user", user_id: id })
      }).then(() => location.reload());
    }
  });

  $(document).on("click", ".delete-rec", function () {
    const id = $(this).data("id");
    if (confirm("למחוק המלצה?")) {
      fetch("admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "delete_recommendation", rec_id: id })
      }).then(() => location.reload());
    }
  });

  $("#user-search").on("input", function () {
    const val = $(this).val().toLowerCase();
    $("#user-table-body tr").each(function () {
      const email = $(this).find("td:nth-child(2)").text().toLowerCase();
      $(this).toggle(email.includes(val));
    });
  });

  $("#rec-user-filter").on("input", function () {
    const val = $(this).val().toLowerCase();
    $("#rec-table-body tr").each(function () {
      const user = $(this).find("td:first-child").text().toLowerCase();
      $(this).toggle(user.includes(val));
    });
  });
});
</script>
</body>
</html>

</body>
</html>
