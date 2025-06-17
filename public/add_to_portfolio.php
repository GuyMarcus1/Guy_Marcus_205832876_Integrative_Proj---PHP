<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data["user_id"] ?? null;
$stock_symbol = $data["stock_symbol"] ?? null;

if (!$user_id || !$stock_symbol) {
  http_response_code(400);
  echo json_encode(["error" => "Missing data"]);
  exit();
}

$conn = new mysqli("sql309.byethost17.com", "b17_39144683", "LAJZhd!@FRR8zTE", "b17_39144683_sadkaDB");
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "DB connection failed"]);
  exit();
}

$stmt = $conn->prepare("SELECT id FROM portfolio WHERE user_id = ? AND stock_symbol = ?");
$stmt->bind_param("is", $user_id, $stock_symbol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  http_response_code(409);
  echo json_encode(["error" => "Stock already exists in portfolio"]);
  exit();
}

$stmt = $conn->prepare("INSERT INTO portfolio (user_id, stock_symbol) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $stock_symbol);
if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Insert failed"]);
}
$conn->close();
?>
