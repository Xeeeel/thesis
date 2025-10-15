<?php
// config/db_config.php
function db(): PDO {
  static $pdo = null;
  if ($pdo instanceof PDO) return $pdo;

  // Quick local config
  $DB_HOST = 'localhost';
  $DB_NAME = 'cartsy';
  $DB_USER = 'root';
  $DB_PASS = '';

  $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
  $opts = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // real prepared stmts = SQL-Injection proof
  ];

  $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $opts);
  return $pdo;
}
