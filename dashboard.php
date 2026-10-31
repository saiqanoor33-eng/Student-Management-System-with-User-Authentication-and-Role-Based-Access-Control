<?php
require_once 'auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            margin: 0;
            color: #172033
        }

        header {
            background: #243b80;
            color: white;
            padding: 25px;
            text-align: center
        }

        main {
            max-width: 800px;
            margin: 35px auto;
            padding: 20px
        }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 20px
        }

        a {
            display: inline-block;
            background: #2948a5;
            color: white;
            padding: 12px 18px;
            border-radius: 7px;
            text-decoration: none;
            margin: 6px
        }

        .logout {
            background: #dc2626
        }
    </style>
</head>

<body>
    <header>
        <h1>Student Management System</h1>
        <p>Welcome,
            <?= htmlspecialchars($_SESSION['name']) ?>
        </p>
    </header>

    <main>
        <div class="panel">
            <h2>Dashboard</h2>
            <p><b>Name:</b>
                <?= htmlspecialchars($_SESSION['name']) ?>
            </p>
            <p><b>Email:</b>
                <?= htmlspecialchars($_SESSION['email']) ?>
            </p>
            <p><b>Role:</b>
                <?= htmlspecialchars(ucfirst($_SESSION['role'])) ?>
            </p>

            <a href="profile.php">My Profile</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php">Admin Panel</a>
                <a href="index.php">Manage Students</a>
            <?php elseif ($_SESSION['role'] === 'teacher'): ?>
                <a href="teacher.php">Teacher Panel</a>
                <a href="index.php">Manage Students</a>
            <?php else: ?>
                <a href="student.php">Student Panel</a>
            <?php endif; ?>

            <a href="logout.php" class="logout">Logout</a>
        </div>
    </main>
</body>

</html>