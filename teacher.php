<?php
require_once 'auth.php';
requireRole(['teacher']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Teacher Panel</title>
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
        <h1>Teacher Dashboard</h1>
        <h3>Welcome
            <?= htmlspecialchars($_SESSION['name']) ?>
        </h3>
        <p>You can view, add, update and delete student records.</p>
        <a href="index.php">Manage Students</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>