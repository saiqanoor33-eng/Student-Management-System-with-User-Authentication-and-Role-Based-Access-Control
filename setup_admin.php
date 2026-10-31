<?php
require_once 'db.php';
require_once 'auth.php';

$count = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (int) $count === 0) {
    verifyCsrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $message = 'Enter valid details. Password must be at least 8 characters.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                "INSERT INTO users (name,email,password,role) VALUES (?,?,?,'admin')"
            );
            $stmt->execute([$name, $email, $hash]);

            $message = 'Admin created successfully. Delete setup_admin.php now and login.';
            $count = 1;
        } catch (PDOException $e) {
            $message = 'Unable to create admin. Email may already exist.';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Create First Admin</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2ff;
            display: grid;
            place-items: center;
            min-height: 100vh
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: min(350px, 85%)
        }

        input,
        button {
            width: 100%;
            padding: 12px;
            margin: 9px 0;
            box-sizing: border-box
        }

        button {
            background: #243b80;
            color: white;
            border: 0;
            cursor: pointer
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>First Admin Setup</h2>
        <p>
            <?= htmlspecialchars($message) ?>
        </p>
        <?php if ((int) $count === 0): ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
                <input name="name" placeholder="Admin Name" required>
                <input type="email" name="email" placeholder="Admin Email" required>
                <input type="password" name="password" placeholder="Password (8+ characters)" minlength="8" required>
                <button type="submit">Create Admin</button>
            </form>
        <?php else: ?>
            <p>An admin already exists. Setup is disabled.</p>
            <a href="login.php">Go to Login</a>
        <?php endif; ?>
    </div>
</body>

</html>