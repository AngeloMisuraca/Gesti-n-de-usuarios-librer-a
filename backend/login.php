<?php
session_start();
require "config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"] ?? "");
$password = $data["password"] ?? "";

if ($username === "" || $password === "") {
  echo json_encode(["success" => false, "message" => "Usuario y contraseña obligatorios"]);
  exit;
}

try {
  $connection = getDbConnection();
  $safeUsername = $connection->real_escape_string($username);
  $sql = "SELECT username, password FROM users WHERE username = '{$safeUsername}' LIMIT 1";
  $result = $connection->query($sql);
  $user = $result ? $result->fetch_assoc() : null;

  if ($user && $password === $user["password"]) {
    $_SESSION["user"] = $user["username"];
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
  }

  $connection->close();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "Error de conexión con la base de datos"]);
}
