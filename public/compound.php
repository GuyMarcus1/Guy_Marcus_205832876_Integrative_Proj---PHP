<?php
function calculateCompoundInterest($principal, $rate, $years, $compoundings) {
    $finalAmount = $principal * pow((1 + ($rate / 100) / $compoundings), $compoundings * $years);
    return round($finalAmount, 2);
}

$result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $principal = $_POST["principal"];
    $rate = $_POST["rate"];
    $years = $_POST["years"];
    $compoundings = $_POST["compoundings"];
    $result = calculateCompoundInterest($principal, $rate, $years, $compoundings);
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <p style="text-align:center; margin-top: 20px;">
    <a href="info.php">⬅️ חזרה למידע הפיננסי</a>
    </p>

    <meta charset="UTF-8">
    <title>מחשבון ריבית דריבית</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            padding: 30px;
            background-color: #f5f5f5;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
        }
        button {
            padding: 10px;
            width: 100%;
            background-color: #007acc;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .result {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
        .youtube {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h2>מחשבון ריבית דריבית</h2>
    <form method="post">
        <label>סכום התחלתי:</label>
        <input type="number" name="principal" required>
        <label>ריבית שנתית (%):</label>
        <input type="number" name="rate" step="0.01" required>
        <label>מספר שנים:</label>
        <input type="number" name="years" required>
        <label>מספר חישובים לשנה:</label>
        <select name="compoundings">
            <option value="1">שנתי</option>
            <option value="2">חצי שנתי</option>
            <option value="4">רבעוני</option>
            <option value="12">חודשי</option>
        </select>
        <button type="submit">חשב</button>
    </form>

    <?php if ($result): ?>
        <div class="result">
            💰 סכום סופי אחרי ריבית דריבית: <strong><?= $result ?> ₪</strong>
        </div>
    <?php endif; ?>

    <div class="youtube">
        <div class="youtube">
        <p>⬇️ הסבר נוסף על ריבית דריבית:</p>
        <iframe width="100%" height="315"
            src="https://www.youtube.com/embed/94VjdAbnhCU"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
        </div>

    </div>
</body>
</html>
