<?php
header("Content-Type: application/json");
$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
  http_response_code(400);
  echo json_encode(["error" => "Missing user_id"]);
  exit();
}
$conn = new mysqli("sql309.byethost17.com", "b17_39144683", "LAJZhd!@FRR8zTE", "b17_39144683_sadkaDB");
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Connection failed"]);
  exit();
}
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$portfolio = [];
while ($row = $result->fetch_assoc()) {
  $portfolio[] = $row;
}
echo json_encode($portfolio);
$conn->close();
?>