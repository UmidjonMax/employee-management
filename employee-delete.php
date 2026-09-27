<?php

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Models/Employee.php';
require_once __DIR__ . '/app/Controllers/EmployeeController.php';

$database = new Database();
$pdo = $database->getConnection();

$employeeModel = new Employee($pdo);
$employeeController = new EmployeeController($employeeModel);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die('Invalid employee ID.');
}

try {
    $employeeController->delete($id);
    header('Location: employees.php');
    exit();
} catch (Exception $e) {
    die('Employee could not be deleted: ' . $e->getMessage());
}
