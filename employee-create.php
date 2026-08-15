<?php

$pdo = require __DIR__ . '/config/database.php';

$errors = [];

$firstName = '';
$lastName = '';
$email = '';
$phone = '';
$departmentId = '';
$position = '';
$salary = '';
$hireDate = date('Y-m-d');
$status = 'ACTIVE';

$departments = $pdo ->query("SELECT id, name FROM departments order by id")->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $departmentId = trim($_POST['department_id'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $salary = trim($_POST['salary'] ?? '');
    $hireDate = date('Y-m-d');
    $status = trim($_POST['status'] ?? '');

    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';

    if ($firstName === '') {
        $errors[] = 'First name is required.';
    }

    if ($lastName === '') {
        $errors[] = 'Last name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    }

    if ($position === '') {
        $errors[] = 'Position is required.';
    }
    if (
        $email !== '' &&
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($salary !== '') {

        if (!is_numeric($salary)) {
            $errors[] = 'Salary must be a number.';
        } elseif ((float)$salary < 0) {
            $errors[] = 'Salary cannot be negative.';
        }
    }
    $allowedStatuses = [
        'ACTIVE',
        'INACTIVE'
    ];

    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = 'Invalid employee status.';
    }
    if ($departmentId === '') {

        $departmentId = null;

    } elseif (
        filter_var($departmentId, FILTER_VALIDATE_INT) === false ||
        (int)$departmentId < 1
    ) {

        $errors[] = 'Invalid department.';

    } else {

        $departmentId = (int)$departmentId;
    }
    if (empty($errors)) {

        try {

            $sql = "
            INSERT INTO employees (
                first_name,
                last_name,
                email,
                phone,
                department_id,
                position,
                salary,
                hire_date,
                status
            )
            VALUES (
                :first_name,
                :last_name,
                :email,
                :phone,
                :department_id,
                :position,
                :salary,
                TO_DATE(:hire_date, 'YYYY-MM-DD'),
                :status
            )
        ";

            $statement = $pdo->prepare($sql);

            $statement->execute([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone !== '' ? $phone : null,
                'department_id' => $departmentId,
                'position' => $position,
                'salary' => $salary !== '' ? $salary : null,
                'hire_date' => $hireDate,
                'status' => $status
            ]);

            header('Location: employees.php');
            exit;

        } catch (PDOException $e) {

            $errors[] = 'Employee could not be created.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Employee</title>

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
            width: 90%;
            max-width: 850px;
            margin: 50px auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #555;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font: inherit;
            background: white;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #555;
        }

        .errors {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            color: #b71c1c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .errors ul {
            margin: 0;
            padding-left: 20px;
        }

        .actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .btn {
            border: none;
            padding: 11px 18px;
            border-radius: 7px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: #222;
            color: white;
        }

        .btn-secondary {
            background: #eee;
            color: #333;
        }

        @media (max-width: 650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <a href="employees.php" class="back">
        ← Back to Employees
    </a>

    <div class="card">

        <h1>Create Employee</h1>

        <?php if (!empty($errors)): ?>

            <div class="errors">

                <ul>

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label for="first_name">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        maxlength="100"
                        value="<?= htmlspecialchars($firstName) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="last_name">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        maxlength="100"
                        value="<?= htmlspecialchars($lastName) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="255"
                        value="<?= htmlspecialchars($email) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="phone">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        maxlength="30"
                        placeholder="+998..."
                        value="<?= htmlspecialchars($phone) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="department_id">
                        Department
                    </label>

                    <select
                        id="department_id"
                        name="department_id"
                    >

                        <option value="">
                            No Department
                        </option>

                        <?php foreach ($departments as $department): ?>

                            <option
                                value="<?= $department['ID'] ?>"
                                <?= (string) $departmentId ===
                                (string) $department['ID']
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars($department['NAME']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="form-group">

                    <label for="position">
                        Position
                    </label>

                    <input
                        type="text"
                        id="position"
                        name="position"
                        maxlength="150"
                        value="<?= htmlspecialchars($position) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="salary">
                        Salary
                    </label>

                    <input
                        type="number"
                        id="salary"
                        name="salary"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string) $salary) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="hire_date">
                        Hire Date
                    </label>

                    <input
                        type="date"
                        id="hire_date"
                        name="hire_date"
                        value="<?= htmlspecialchars($hireDate) ?>"
                    >

                </div>


                <div class="form-group full-width">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                    >

                        <option
                            value="ACTIVE"
                            <?= $status === 'ACTIVE' ? 'selected' : '' ?>
                        >
                            Active
                        </option>

                        <option
                            value="INACTIVE"
                            <?= $status === 'INACTIVE' ? 'selected' : '' ?>
                        >
                            Inactive
                        </option>

                    </select>

                </div>

            </div>


            <div class="actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Employee
                </button>

                <a
                    href="employees.php"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
