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
  $id = (int) ($data["id"] ?? 0);
  $userId = (int) $_SESSION["user_id"];

  $sql = "DELETE FROM books WHERE id = {$id} AND user_id = {$userId}";
  $success = $conn->query($sql);

  echo json_encode(["success" => (bool) $success]);
  $conn->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudo eliminar el libro"]);
}
