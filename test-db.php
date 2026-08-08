<?php

require_once __DIR__ . '/config/database.php';

if (isset($pdo)) {
    echo "Database connected successfully\n";
}

$oracleUser = $pdo->query("SELECT
    USER AS username,
    SYS_CONTEXT('USERENV', 'CON_NAME') AS container_name,
    SYSDATE AS database_time
FROM dual")->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oracle Connection Test</title>
    <style> body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 80px auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            color: #222;
        }

        .status {
            background: #e8f5e9;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #222;
        } </style>
</head>
<body>
<div class="container">
    <div class="card"><h1>Oracle Connection Information</h1>
        <div class="status"> Database connection successful</div>
        <div class="info-row"><span class="label">User</span> <span
                    class="value"> <?= htmlspecialchars($oracleUser['USERNAME']) ?> </span></div>
        <div class="info-row"><span class="label">Database</span> <span
                    class="value"> <?= htmlspecialchars($oracleUser['CONTAINER_NAME']) ?> </span></div>
        <div class="info-row"><span class="label">Database Time</span> <span
                    class="value"> <?= htmlspecialchars($oracleUser ['DATABASE_TIME']) ?> </span></div>
    </div>
</div>
</body>
</html>