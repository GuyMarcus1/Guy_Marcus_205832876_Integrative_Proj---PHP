<?php
$host = "sql309.byethost17.com";
$user = "b17_39144683";
$password = "LAJZhd!@FRR8zTE";
$database = "b17_39144683_sadkaDB";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Connection failed"]);
  exit();
}

$sql = "SELECT r.id, r.stock_symbol, r.comment, u.name AS user_name
        FROM recommendations r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.id DESC";

$result = $conn->query($sql);
$recs = [];

while ($row = $result->fetch_assoc()) {
  $recs[] = $row;
}

echo json_encode($recs);
$conn->close();
?>
