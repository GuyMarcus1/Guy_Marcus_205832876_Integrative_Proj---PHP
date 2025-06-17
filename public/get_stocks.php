<?php
header("Content-Type: application/json");
$conn = new mysqli("sql309.byethost17.com", "b17_39144683", "LAJZhd!@FRR8zTE", "b17_39144683_sadkaDB");
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Connection failed"]);
  exit();
}
$result = $conn->query("SELECT * FROM stocks");
$stocks = [];
while ($row = $result->fetch_assoc()) {
  $stocks[] = $row;
}
echo json_encode($stocks);
$conn->close();
?>
