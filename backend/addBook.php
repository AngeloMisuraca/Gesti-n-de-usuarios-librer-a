<?php
session_start();
require "config.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
  http_response_code(401);
  echo json_encode(["success" => false, "message" => "Sesion no valida"]);
  exit;
}

try {
  $conn = getDbConnection();
  $data = json_decode(file_get_contents("php://input"), true);
  $title = $conn->real_escape_string(trim($data["title"] ?? ""));
  $author = $conn->real_escape_string(trim($data["author"] ?? ""));
  $year = (int) ($data["year"] ?? 0);
  $userId = (int) $_SESSION["user_id"];

  if ($title === "" || $author === "" || $year === 0) {
    echo json_encode(["success" => false, "message" => "Completa todos los campos"]);
    $conn->close();
    exit;
  }

  $sql = "INSERT INTO books (user_id, title, author, year) VALUES ({$userId}, '{$title}', '{$author}', {$year})";
  $success = $conn->query($sql);

  echo json_encode(["success" => (bool) $success]);
  $conn->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudo guardar el libro"]);
}
