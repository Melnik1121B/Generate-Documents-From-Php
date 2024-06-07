<?php

include_once 'db.php'; // Подключаем файл с настройками базы данных

function authenticate_user($username, $password) {
    global $conn;

    // Запрос на проверку имени пользователя и пароля в базе данных
    $sql = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        // Проверка, соответствует ли хешированный пароль в базе данных введенному паролю
        if (password_verify($password, $hashed_password)) {
            // Если пароли совпадают, возвращаем данные пользователя
            return $user;
        }
    }

    return false; // Если аутентификация не удалась, возвращаем false
}

function is_authenticated() {
    return isset($_SESSION['user_id']);
}

function logout() {
    session_unset();
    session_destroy();
}

// Добавляем функцию для получения роли пользователя
function get_user_role($username) {
    global $conn;

    $sql = "SELECT role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        return $user['role']; // Возвращаем роль пользователя
    }

    return null; // Если пользователь не найден, возвращаем null
}
