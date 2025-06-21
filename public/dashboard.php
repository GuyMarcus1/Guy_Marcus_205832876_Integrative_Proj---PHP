<?php
session_start();
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["user_name"])) {
  echo "<script>alert('עליך להתחבר קודם'); window.location.href='login.php';</script>";
  exit;
}

$host = "sql309.byethost17.com";
$user = "b17_39144683";
$password = "LAJZhd!@FRR8zTE";
$database = "b17_39144683_sadkaDB";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stocks = [];
$result = $conn->query("SELECT * FROM stocks");
while ($row = $result->fetch_assoc()) {
  $stocks[] = $row;
}

$user_id = $_SESSION["user_id"];
$portfolio = [];
$stmt = $conn->prepare("SELECT stock_symbol FROM portfolio WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $portfolio[] = $row["stock_symbol"];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="he">
<head>
  <meta charset="UTF-8" />
  <title>לוח מחוונים - תיק השקעות</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    
    .form-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      padding: 20px;
    }
    .sector {
      flex: 1 1 300px;
      background: #f9f9f9;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .stock-list {
      list-style: none;
      padding: 0;
    }
    .stock-list li {
      margin-bottom: 8px;
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
<div class="form-container" id="dashboard-container">
  <input type="text" id="stock-search" placeholder="חפש מניה או סקטור..." style="width: 300px; padding: 8px; margin-bottom: 25px; border-radius: 8px; border: 1px solid #ccc; font-size: 1em; direction: rtl;">
  <h2 style="width: 100%; text-align: center;">📈 תיק השקעות לפי סקטורים</h2>
</div>
<script>
const allStocks = <?php echo json_encode($stocks); ?>;
let userPortfolio = <?php echo json_encode($portfolio); ?>;
const userId = "<?php echo $_SESSION['user_id']; ?>";

$(document).ready(function () {
  const stocksPerSectorPreview = 3;
  const sectorLists = {};

  allStocks.forEach(stock => {
    const sector = stock.sector;
    if (!sectorLists[sector]) sectorLists[sector] = [];
    sectorLists[sector].push(stock);
  });

  Object.keys(sectorLists).forEach(sector => {
    const id = sector.toLowerCase().replace(/[^a-z]/g, '-') + '-list';
    const sectorDiv = $(`
      <div class="sector">
        <h3>${sector}</h3>
        <ul class="stock-list" id="${id}"></ul>
        <button class="show-more" data-target="${id}">+ הצג עוד</button>
      </div>
    `);
    $(".form-container").append(sectorDiv);
  $("#stock-search").on("input", function () {
  const query = $(this).val().toLowerCase();

    $(".sector").each(function () {
      const $sectorDiv = $(this);
      const sectorName = $sectorDiv.find("h3").text().toLowerCase();
      let matchFound = false;

      $sectorDiv.find("li").each(function () {
        const text = $(this).text().toLowerCase();
        const matches = text.includes(query) || sectorName.includes(query);
        $(this).toggle(matches);
        if (matches) matchFound = true;
      });

      $sectorDiv.toggle(matchFound);
    });
  });

    const list = $("#" + id);
    sectorLists[sector].forEach((stock, i) => {
      const isInPortfolio = userPortfolio.includes(stock.symbol);
      const li = `<li class="${i >= stocksPerSectorPreview ? 'extra' : ''}" style="${i >= stocksPerSectorPreview ? 'display:none;' : ''}">
        ${stock.symbol} - ${stock.name}
        <span class="price">${(Math.random() * 100 + 50).toFixed(2)}</span>
        <button class="add-btn" data-symbol="${stock.symbol}" ${isInPortfolio ? 'disabled' : ''}>${isInPortfolio ? '✔' : '➕'}</button>
      </li>`;
      list.append(li);
    });
  });

  $(document).on("click", ".show-more", function () {
    const target = $(this).data("target");
    const $btn = $(this);
    const $list = $("#" + target);

    if ($btn.data("expanded")) {
      $list.find("li.extra").hide();
      $btn.text("+ הצג עוד").data("expanded", false);
    } else {
      $list.find("li.extra").show();
      $btn.text("– הסתר").data("expanded", true);
    }
  });

  // כפתור ➕ להוספת מניה לתיק האישי
  $(document).on("click", ".add-btn", function () {
    const $btn = $(this);
    const symbol = $btn.data("symbol");

    if (userPortfolio.includes(symbol)) {
      alert("המניה כבר קיימת בתיק האישי שלך");
      return;
    }

    fetch("add_to_portfolio.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: userId, stock_symbol: symbol })
    })
    .then(res => {
      if (!res.ok) throw new Error("שגיאה בשרת");
      userPortfolio.push(symbol);
      $btn.text("✔").attr("disabled", true);
      alert("המניה נוספה לתיק האישי בהצלחה!");
    })
    .catch(err => {
      console.error("שגיאה:", err);
      alert("אירעה שגיאה בהוספת המניה");
    });
  });

  setInterval(() => {
    $(".price").each(function () {
      const current = parseFloat($(this).text());
      const delta = current * (Math.random() * 0.02 - 0.01);
      $(this).text((current + delta).toFixed(2));
    });
  }, 4000);
});
</script>

</body>
</html>
