<?php
require_once 'db.php';
require_once 'auth.php';
requireLogin();

$stmt = $pdo->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2ff;
            padding: 30px
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            max-width: 500px;
            margin: auto
        }

        a {
            color: #2948a5
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>My Profile</h1>
        <p><b>User ID:</b>
            <?= (int) $user['id'] ?>
        </p>
        <p><b>Name:</b>
            <?= htmlspecialchars($user['name']) ?>
        </p>
        <p><b>Email:</b>
            <?= htmlspecialchars($user['email']) ?>
        </p>
        <p><b>Role:</b>
            <?= htmlspecialchars(ucfirst($user['role'])) ?>
        </p>
        <p><b>Registered:</b>
            <?= htmlspecialchars($user['created_at']) ?>
        </p>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>