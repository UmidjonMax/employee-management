<?php

session_start();

$pdo = require __DIR__ . '/config/database.php';

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
    $statement = $pdo->prepare('DELETE FROM departments WHERE id = :id');
    $statement->execute(['id' => $id]);
    $_SESSION['success'] = 'Department deleted successfully';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Department could not be deleted. It may contain employees.';
}

header('Location: /employee-management/departments.php');
exit;