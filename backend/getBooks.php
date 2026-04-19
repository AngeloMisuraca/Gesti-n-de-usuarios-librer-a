<?php
require "config.php";

header("Content-Type: application/json");

try {
  $conn = getDbConnection();
  $sql = "SELECT * FROM books";
  $result = $conn->query($sql);
  $books = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

  echo json_encode($books);
  $conn->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudieron obtener los libros"]);
}
