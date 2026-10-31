<?php
require_once 'db.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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
            margin: 10px 0;
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

        .success {
            color: green
        }

        a {
            color: #2948a5
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Welcome Back</h2>

        <?php if (isset($_GET['registered'])): ?>
            <p class="success">Registration successful! Please login.</p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>

</html>