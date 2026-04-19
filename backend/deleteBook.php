<?php
require "config.php";

header("Content-Type: application/json");

try {
  $conn = getDbConnection();
  $data = json_decode(file_get_contents("php://input"));
  $id = $data->id ?? ($_POST["id"] ?? "");
  $safeId = $conn->real_escape_string((string) $id);

  $sql = "DELETE FROM books WHERE id = '{$safeId}'";
  $success = $conn->query($sql);

  echo json_encode(["success" => (bool) $success]);
  $conn->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudo eliminar el libro"]);
}
