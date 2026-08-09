<?php

$pdo = require __DIR__ . '/config/database.php';

$errors = [];

$name = '';
$description = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? '');
    $description = trim($_POST["description"] ?? '');

    if ($name == '') {
        $errors[] = "Name cannot be empty";
    }
    if ($description == '') {
        $errors[] = "Description cannot be empty";
    }
    if (strlen($name) > 100) {
        $errors[] = "Name cannot be longer than 100 characters";
    }
    if (strlen($description) > 500) {
        $errors[] = "Description cannot be longer than 500 characters";
    }
    if (empty($errors)) {
        try {
            $sql = "
        INSERT INTO departments (
            name,
            description
        )
        VALUES (
            :name,
            :description
        )
    ";

            $statement = $pdo->prepare($sql);

            $statement->execute([
                    "name" => $name,
                    "description" => $description
            ]);
            header("location: departments.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = $e->getMessage();
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Department</title>

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
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
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

        .btn {
            border: 0;
            background: #222;
            color: #fff;
            padding: 11px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #444;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="departments.php" class="back">
        ← Back to Departments
    </a>

    <div class="card">

        <h1>Create Department</h1>

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

            <button type="submit" class="btn">
                Create Department
            </button>

        </form>

    </div>

</div>

</body>
</html>

