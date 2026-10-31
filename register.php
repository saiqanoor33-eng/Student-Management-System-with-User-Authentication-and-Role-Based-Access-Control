<?php
require_once 'db.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Enter a valid name.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
            );

            $stmt->execute([$name, $email, $hash, 'student']);

            header('Location: login.php?registered=1');
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'Email is already registered.';
            } else {
                $errors[] = 'Registration failed. Try again.';
                error_log($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2ff;
            display: grid;
            place-items: center;
            min-height: 100vh;
            margin: 0
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: min(360px, 85%)
        }

        input,
        button {
            width: 100%;
            padding: 12px;
            margin: 9px 0;
            box-sizing: border-box;
            border-radius: 7px
        }

        input {
            border: 1px solid #ccc
        }

        button {
            background: #2948a5;
            color: white;
            border: 0;
            cursor: pointer
        }

        .error {
            color: #b91c1c
        }

        a {
            color: #2948a5
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Create Account</h2>

                <?php foreach ($errors as $error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
                        </p>
        <?php endforeach; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
            <input name="name" placeholder="Full Name" maxlength="100" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password (8+ characters)" minlength="8" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>

        <p>Already registered? <a href="login.php">Login</a></p>
    </div>
</body>

</html>