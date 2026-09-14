<?php

require_once __DIR__ . '/config/Database.php';

$database = new Database();

$pdo = $database->getConnection();

echo 'Database connection successful!';