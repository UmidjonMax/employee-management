<?php

$pdo = require __DIR__ . '/config/database.php';

$statement = $pdo->query("
    SELECT
        e.id,
        e.first_name,
        e.last_name,
        e.email,
        e.phone,
        e.position,
        e.salary,
        e.hire_date,
        e.status,
        d.name AS department_name
    FROM employees e
    LEFT JOIN departments d
        ON d.id = e.department_id
    ORDER BY e.id
");

$employees = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employees</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }

        .container {
            width: 94%;
            max-width: 1400px;
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

        .nav {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 7px;
            text-decoration: none;
        }

        .btn-primary {
            background: #222;
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #333;
            border: 1px solid #ddd;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #fafafa;
            font-size: 14px;
            white-space: nowrap;
        }

        td {
            font-size: 14px;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .active {
            background: #e8f5e9;
            color: #1b5e20;
        }

        .inactive {
            background: #ffebee;
            color: #b71c1c;
        }

        .actions {
            white-space: nowrap;
        }

        .actions a {
            text-decoration: none;
            margin-right: 10px;
        }

        .edit {
            color: #1565c0;
        }

        .delete {
            color: #c62828;
        }

        .empty {
            padding: 40px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <h1>Employees</h1>

        <div class="nav">

            <a href="departments.php" class="btn btn-secondary">
                Departments
            </a>

            <a href="employee-create.php" class="btn btn-primary">
                Add Employee
            </a>

        </div>

    </div>

    <div class="card">

        <?php if (!empty($employees)): ?>

            <table>

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Hire Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php foreach ($employees as $employee): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars((string) $employee['ID']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $employee['FIRST_NAME'] . ' ' .
                                $employee['LAST_NAME']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($employee['EMAIL']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($employee['PHONE'] ?? '') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $employee['DEPARTMENT_NAME'] ?? 'No department'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($employee['POSITION'] ?? '') ?>
                        </td>

                        <td>
                            <?= number_format(
                                (float) $employee['SALARY'],
                                0,
                                '.',
                                ' '
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($employee['HIRE_DATE']) ?>
                        </td>

                        <td>

                            <?php
                            $status = $employee['STATUS'];
                            $statusClass =
                                $status === 'ACTIVE'
                                    ? 'active'
                                    : 'inactive';
                            ?>

                            <span class="status <?= $statusClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>

                        </td>

                        <td class="actions">

                            <a
                                href="employee-edit.php?id=<?= $employee['ID'] ?>"
                                class="edit"
                            >
                                Edit
                            </a>

                            <a
                                href="employee-delete.php?id=<?= $employee['ID'] ?>"
                                class="delete"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                No employees found.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
