<?php
header('Content-Type: application/json');


$conn = new mysqli("sql309.byethost17.com", "b17_39144683", "LAJZhd!@FRR8zTE", "b17_39144683_sadkaDB");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

$query = "SELECT title, content, category, image_url, created_at 
          FROM news_feed 
          ORDER BY RAND() 
          LIMIT 6";

$result = $conn->query($query);

$news = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}

echo json_encode($news, JSON_UNESCAPED_UNICODE);
$conn->close();
