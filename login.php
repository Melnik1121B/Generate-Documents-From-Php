<?php
// Подключение к базе данных
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "SystemBase";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Защита от SQL-инъекций
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Шифрование пароля (если используется хэширование)
    // $password = md5($password);

    // Поиск пользователя в базе данных
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Пользователь найден
        $row = $result->fetch_assoc();
        $role = $row['role'];

        // Например, сохранение роли в сессии
        session_start();
        $_SESSION['role'] = $role;

        // Редирект на страницу в зависимости от роли
        if ($role === 'admin') {
            header("Location: admin/index.php");
        } elseif ($role === 'director_opop') {
            header("Location: director_opop/index.php");
        } else {
            echo "Неправильная роль пользователя.";
        }
    } else {
        // Пользователь не найден
        echo "Неправильное имя пользователя или пароль.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
