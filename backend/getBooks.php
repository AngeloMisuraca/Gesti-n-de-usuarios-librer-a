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
  $userId = (int) $_SESSION["user_id"];
  $sql = "SELECT id, title, author, year FROM books WHERE user_id = {$userId} ORDER BY id DESC";
  $result = $conn->query($sql);
  $books = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

  echo json_encode($books);
  $conn->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudieron obtener los libros"]);
}
