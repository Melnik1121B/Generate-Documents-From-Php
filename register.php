<?php
include_once 'includes/db.php';
include_once 'includes/auth.php';

if (is_authenticated()) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
        exit();
    } elseif ($_SESSION['role'] === 'director_opop') {
        header("Location: director_opop/index.php");
        exit();
    } elseif ($_SESSION['role'] === 'student') {
        header("Location: student/index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Проверяем, существует ли пользователь с таким именем
    if (username_exists($username)) {
        $error = "User with this username already exists";
    } else {
        if (empty($username) || empty($password)) {
            $error = "Please enter both username and password";
        } else {
            // Попытка создания нового пользователя
            $result = register_student($username, $password);

            if ($result) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Failed to register user";
            }
        }
    }
}

function username_exists($username) {
    global $conn;

    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $num_rows = $stmt->num_rows;
    $stmt->close();

    return $num_rows > 0;
}

function register_student($username, $password) {
    global $conn;

    $sql = "INSERT INTO users (username, password, role, role_id) VALUES (?, ?, 'student', 4)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password_hash);

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 100px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Registration</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
