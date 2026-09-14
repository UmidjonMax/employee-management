<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Models/Department.php';

$database = new Database();
$pdo = $database->getConnection();

$department = new Department($pdo);

$errors = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die('Invalid department ID');
}

$departmentData = $department->findById($id);
if (!$departmentData) {
    die('Department not found');
}

$name = $departmentData['NAME'];
$description = $departmentData['DESCRIPTION'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    if (empty($description)) {
        $errors[] = 'Description is required';
    }
    if (strlen($name) > 100) {
        $errors[] = "Name cannot be longer than 100 characters";
    }
    if (strlen($description) > 500) {
        $errors[] = "Description cannot be longer than 500 characters";
    }
    if (empty($errors)) {
        try {
            $department->update($id, $name, $description);
            header('Location: departments.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Department already exists" . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Department</title>

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
            max-width: 700px;
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
            margin-bottom: 8px;
        }

        .subtitle {
            margin-top: 0;
            margin-bottom: 25px;
            color: #777;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font: inherit;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #555;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
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
            display: flex;
            gap: 10px;
        }

        .btn {
            border: 0;
            padding: 11px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
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
    </style>
</head>

<body>

<div class="container">

    <a href="departments.php" class="back">
        ← Back to Departments
    </a>

    <div class="card">

        <h1>Edit Department</h1>

        <p class="subtitle">
            Department ID:
            <?= htmlspecialchars((string) $id) ?>
        </p>


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

            <div class="form-group">

                <label for="name">
                    Department Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="100"
                    value="<?= htmlspecialchars($name) ?>"
                >

            </div>


            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    maxlength="500"
                ><?= htmlspecialchars($description) ?></textarea>

            </div>


            <div class="actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>

                <a
                    href="departments.php"
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
