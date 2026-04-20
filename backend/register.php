<?php
require "config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data["username"] ?? "");
$password = $data["password"] ?? "";

if ($username === "" || $password === "") {
  echo json_encode(["success" => false, "message" => "Usuario y contrasena obligatorios"]);
  exit;
}

try {
  $connection = getDbConnection();
  $safeUsername = $connection->real_escape_string($username);
  $safePassword = $connection->real_escape_string($password);

  $checkSql = "SELECT id FROM users WHERE username = '{$safeUsername}' LIMIT 1";
  $checkResult = $connection->query($checkSql);
  $exists = $checkResult ? $checkResult->fetch_assoc() : null;

  if ($exists) {
    echo json_encode(["success" => false, "message" => "El usuario ya existe"]);
    $connection->close();
    exit;
  }

  $insertSql = "INSERT INTO users (username, password) VALUES ('{$safeUsername}', '{$safePassword}')";
  $connection->query($insertSql);

  echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
  $connection->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "No se pudo registrar el usuario"]);
}
