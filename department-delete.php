<?php

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Models/Department.php';

$database = new Database();
$pdo = $database->getConnection();

$department = new Department($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /departments.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <1){
    header('Location: /departments.php');
    exit;
}
try {
    $department->delete($id);
    $_SESSION['success'] = 'Department deleted successfully';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Department could not be deleted. It may contain employees.';
}

header('Location: /employee-management/departments.php');
exit;