<?php
require "config.php";

header("Content-Type: application/json");

try {
  $conn = getDbConnection();
  $data = json_decode(file_get_contents("php://input"));
  $id = $data->id ?? "";
  $title = $data->title ?? "";
  $author = $data->author ?? "";
  $year = $data->year ?? "";

  $safeId = $conn->real_escape_string((string) $id);
  $safeTitle = $conn->real_escape_string($title);
  $safeAuthor = $conn->real_escape_string($author);
  $safeYear = $conn->real_escape_string((string) $year);

  $sql = "UPDATE books SET title = '{$safeTitle}', author = '{$safeAuthor}', year = '{$safeYear}' WHERE id = '{$safeId}'";
  $success = $conn->query($sql);

  echo json_encode(["success" => (bool) $success]);
  $conn->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudo actualizar el libro"]);
}
