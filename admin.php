<?php
require_once 'db.php';
require_once 'auth.php';

requireRole(['admin']);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $id = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
    $role = $_POST['role'] ?? '';

    if ($id && in_array($role, ['admin', 'teacher', 'student'], true)) {
        if ($id === (int) $_SESSION['user_id'] && $role !== 'admin') {
            $message = 'You cannot remove your own admin role.';
        } else {
            $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
            $stmt->execute([$role, $id]);
            $message = 'User role updated successfully.';
        }
    } else {
        $message = 'Invalid user or role.';
    }
}

$users = $pdo->query(
    'SELECT id, name, email, role, created_at FROM users ORDER BY id DESC'
)->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            padding: 25px
        }

        .panel {
            background: white;
            padding: 20px;
            border-radius: 12px;
            max-width: 1000px;
            margin: auto
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px
        }

        td,
        th {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left
        }

        button {
            background: #2948a5;
            color: white;
            padding: 9px;
            border: 0;
            border-radius: 5px
        }

        select {
            padding: 8px
        }

        .wrap {
            overflow-x: auto
        }

        a {
            color: #2948a5
        }
    </style>
</head>

<body>
    <div class="panel">
        <h1>Admin Panel</h1>
        <p>Manage users and assign roles.</p>
        <p>
            <?= htmlspecialchars($message) ?>
        </p>
        <div class="wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?= (int) $user['id'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user['name']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user['email']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($user['role']) ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                    <select name="role">
                                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student
                                        </option>
                                        <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher
                                        </option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>