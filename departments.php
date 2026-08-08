<?php

require_once __DIR__ . '/config/database.php';
if (isset($pdo)) {
    $statement = $pdo->query('SELECT
        id,
        name,
        description,
        created_at
    FROM departments
    ORDER BY id');
}

$departments = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <style> body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 50px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #fafafa;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
        }

        .edit {
            color: #1565c0;
        }

        .delete {
            color: #c62828;
        } </style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Departments</h1> <a href="department-create.php" class="btn"> Add Department </a></div>
    <div class="card"> <?php if (count($departments) > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody> <?php foreach ($departments as $department): ?>
                    <tr>
                        <td> <?= htmlspecialchars($department['ID']) ?> </td>
                        <td> <?= htmlspecialchars($department['NAME']) ?> </td>
                        <td> <?= htmlspecialchars($department['DESCRIPTION'] ?? '') ?> </td>
                        <td> <?= htmlspecialchars($department['CREATED_AT']) ?> </td>
                        <td class="actions"><a href="department-edit.php?id=<?= $department['ID'] ?>" class="edit">
                                Edit </a> <a href="department-delete.php?id=<?= $department['ID'] ?>" class="delete">
                                Delete </a></td>
                    </tr> <?php endforeach; ?> </tbody>
            </table> <?php else: ?>
            <div class="empty"> No departments found.</div> <?php endif; ?> </div>
</div>
</body>
</html>
