<?php

function getDbConnection(): mysqli
{
  $host = "localhost";
  $dbName = "books_db";
  $dbUser = "root";
  $dbPassword = "";

  $connection = new mysqli($host, $dbUser, $dbPassword, $dbName);

  if ($connection->connect_error) {
    throw new RuntimeException("Error de conexion a la base de datos");
  }

  $connection->set_charset("utf8mb4");

  return $connection;
}
