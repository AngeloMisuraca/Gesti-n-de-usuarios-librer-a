<?php
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) && !isset($_SESSION["user"])) {
  echo json_encode(["logged" => false]);
  exit;
}

$username = $_SESSION["username"] ?? $_SESSION["user"] ?? "";

echo json_encode([
  "logged" => true,
  "userId" => $_SESSION["user_id"] ?? null,
  "username" => $username,
  "user" => $username
]);
