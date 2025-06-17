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

$result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
$users = [];

while ($row = $result->fetch_assoc()) {
  $users[] = $row;
}

echo json_encode($users);
$conn->close();
?>
