<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Настройки для подключения к базе данных
$servername = "127.0.0.1"; // Имя сервера базы данных
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль пользователя базы данных
$dbname = "SystemBase"; // Имя базы данных

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$errors = [];
$success = '';

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $institute = $_POST['institute'] ?? '';
    $direction = $_POST['direction'] ?? '';
    $course = $_POST['course'] ?? '';
    $group = $_POST['group'] ?? '';
    $supervisor_name = $_POST['supervisor_name'] ?? '';
    $supervisor_position = $_POST['supervisor_position'] ?? '';


    // Валидация данных
    if (empty($institute) || empty($direction) || empty($course) || empty($group) || empty($supervisor_name) || empty($supervisor_position)) {
        $errors[] = "Все поля обязательны для заполнения";
    }

    // Если нет ошибок, выполняем вставку данных в таблицу
    if (empty($errors)) {
        $sql = "INSERT INTO PracticeAdmin (institute, direction, course, group_name, supervisor_name, supervisor_position) 
        VALUES (?, ?, ?, ?, ?, ?)";

        // Подготовка запроса
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $errors[] = "Ошибка подготовки запроса: " . $conn->error;
        } else {
            // Привязываем параметры
            $stmt->bind_param("ssssss", $institute, $direction, $course, $group, $supervisor_name, $supervisor_position);

            // Выполняем запрос
            if ($stmt->execute()) {
                $success = "Практика успешно добавлена";
            } else {
                $errors[] = "Ошибка выполнения запроса: " . $stmt->error;
            }

            // Проверка успешного выполнения запроса
            if ($stmt->affected_rows <= 0) {
                $errors[] = "Ошибка: Не удалось добавить практику";
            }

            // Закрываем подготовленное выражение
            $stmt->close();
        }
    }
}

// Закрываем соединение с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить практику - Администратор</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000;
            color: #fff;
            padding: 0;
            margin: 0;
            background-image: url('resident-evil-background.jpg');
            background-size: 120%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            max-width: 500px;
            text-align: center;
        }

        h1 {
            color: #ff0000;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            font-weight: bold;
            font-size: 20px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ff0000;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.8);
            color: #000;
            outline: none;
        }

        input[type="checkbox"] {
            margin-top: 5px;
        }

        button {
            background-color: #ff0000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #d90000;
        }

        .error-message {
            color: #ff0000;
            font-size: 18px;
            margin-top: 10px;
        }

        .success-message {
            color: #00ff00;
            font-size: 18px;
            margin-top: 10px;
        }
        .container {
    /* Другие стили */
    overflow-y: scroll; /* Добавляем вертикальную прокрутку */
    max-height: 80vh; /* Устанавливаем максимальную высоту контейнера */
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Добавить практику - Администратор</h1>
        <!-- Форма для добавления практики -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form">
            <!-- Поля формы -->
            <div class="form-group">
                <label for="institute">Институт:</label>
                <input type="text" id="institute" name="institute" required>
            </div>
            <div class="form-group">
                <label for="direction">Направление подготовки:</label>
                <input type="text" id="direction" name="direction" required>
            </div>
            <div class="form-group">
                <label for="course">Курс:</label>
                <input type="text" id="course" name="course" required>
            </div>
            <div class="form-group">
                <label for="group">Группа:</label>
                <input type="text" id="group" name="group" required>
            </div>
            <div class="form-group">
                <label for="supervisor_name">ФИО руководителя образовательной программы:</label>
                <input type="text" id="supervisor_name" name="supervisor_name" required>
            </div>
            <div class="form-group">
                <label for="supervisor_position">Должность руководителя образовательной программы:</label>
                <input type="text" id="supervisor_position" name="supervisor_position" required>
            </div>
            <!-- Кнопка отправки формы -->
            <button type="submit">Добавить практику</button>
        </form>
        <a href="index.php" class="btn btn-secondary">Назад к панели управления</a>
        <!-- Вывод сообщений об ошибках и успешном добавлении -->
        <?php if (!empty($errors)) : ?>
            <div class="error-message">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <div class="success-message">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>