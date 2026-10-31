<?php
require_once 'db.php';
require_once 'auth.php';
requireRole(['student']);

$stmt = $pdo->prepare(
    'SELECT name, email, role, created_at FROM users WHERE id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Student Panel</title>
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
            max-width: 600px;
            margin: auto
        }

        a {
            display: inline-block;
            background: #2948a5;
            color: white;
            padding: 12px;
            margin: 8px;
            text-decoration: none;
            border-radius: 7px
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>Student Dashboard</h1>
        <h3>Welcome
            <?= htmlspecialchars($user['name']) ?>
        </h3>
        <p><b>Email:</b>
            <?= htmlspecialchars($user['email']) ?>
        </p>
        <p><b>Role:</b> Student</p>
        <p>You can access your own profile and account information.</p>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>