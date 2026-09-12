<?php

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/app/Models/Department.php';


$database = new Database();

$pdo = $database->getConnection();


$departmentModel = new Department($pdo);


$departments = $departmentModel->getAll();


echo '<pre>';

print_r($departments);

echo '</pre>';