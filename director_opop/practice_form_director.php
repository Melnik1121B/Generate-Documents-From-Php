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
    $group = $_POST['group'] ?? '';
    $year = $_POST['year'] ?? '';
    $practice_date_from = $_POST['practice_date_from'] ?? '';
    $practice_date_to = $_POST['practice_date_to'] ?? '';
    $practice_name = $_POST['practice_name'] ?? '';
    $order_number_and_date = $_POST['order_number_and_date'] ?? '';
    $practice_type = $_POST['practice_type'] ?? '';
    $practice_location = $_POST['practice_location'] ?? '';
    $practice_supervisor_name = $_POST['practice_supervisor_name'] ?? '';
    $practice_supervisor_position = $_POST['practice_supervisor_position'] ?? '';
    $production_tasks_name = $_POST['production_tasks_name'] ??'';
    $production_tasks_date = $_POST['production_tasks_date'] ??'';
    $practice_from = $_POST['practice_from'] ?? '';

    // Валидация данных
    if (empty($group) || empty($year) || empty($practice_date_from) || empty($practice_date_to) || empty($practice_name) || empty($order_number_and_date) || empty($practice_type) || empty($practice_location) || empty($practice_supervisor_name) || empty($practice_supervisor_position) || empty($production_tasks_name) || empty($production_tasks_date) || empty($practice_from)) {
        $errors[] = "Все поля обязательны для заполнения";
    }

    // Если нет ошибок, выполняем вставку данных в таблицу
    if (empty($errors)) {
        $sql = "INSERT INTO PracticeDirector (group_name, year, practice_date_from, practice_date_to, practice_name, order_number_and_date, practice_type, practice_location, practice_supervisor_name, practice_supervisor_position, production_tasks_name, production_tasks_date, practice_from) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Подготовка запроса
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $errors[] = "Ошибка подготовки запроса: " . $conn->error;
        } else {
            // Привязываем параметры
            $stmt->bind_param("sssssssssssss", $group, $year, $practice_date_from, $practice_date_to, $practice_name, $order_number_and_date, $practice_type, $practice_location, $practice_supervisor_name, $practice_supervisor_position, $production_tasks_name, $production_tasks_date, $practice_from);

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
    <title>Форма практики</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #000;
            color: #fff;
            padding: 0;
            margin: 0;
            background-image: url('alien_isolation_background.jpg');
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
            color: #d3d3d3;
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
            color: #d3d3d3;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #00ff00;
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
            background-color: #00ff00;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #00dd00;
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
            overflow-y: scroll;
            max-height: 80vh;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Форма практики</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form">
            <div class="form-group">
                <label for="group">Группа:</label>
                <input type="text" id="group" name="group" required>
            </div>
            <div class="form-group">
                <label for="year">Год:</label>
                <input type="text" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="practice_date_from">Дата начала практики:</label>
                <input type="date" id="practice_date_from" name="practice_date_from" required>
            </div>
            <div class="form-group">
                <label for="practice_date_to">Дата окончания практики:</label>
                <input type="date" id="practice_date_to" name="practice_date_to" required>
            </div>
            <div class="form-group">
                <label for="practice_name">Название практики:</label>
                <input type="text" id="practice_name" name="practice_name" required>
            </div>
            <div class="form-group">
                <label for="production_tasks_name">Название производственных задач:</label>
                <input type="text" id="production_tasks_name" name="production_tasks_name" required>
            </div>
            <div class="form-group">
                <label for="production_tasks_date">Дата производственных задач:</label>
                <input type="date" id="production_tasks_date" name="production_tasks_date" required>
            </div>
            <div class="form-group">
                <label for="order_number_and_date">Номер и дата приказа:</label>
                <input type="text" id="order_number_and_date" name="order_number_and_date" required>
            </div>
            <div class="form-group">
                <label for="practice_type">Вид практики:</label>
                <select id="practice_type" name="practice_type" required>
                    <option value="учебная практика">Учебная практика</option>
                    <option value="производственная практика">Производственная практика</option>
                    <option value="преддипломная практика">Преддипломная практика</option>
                </select>
            </div>
            <div class="form-group">
                <label for="practice_location">Место практики:</label>
                <input type="text" id="practice_location" name="practice_location" required>
            </div>
            <div class="form-group">
                <label for="practice_supervisor_name">ФИО руководителя практики:</label>
                <input type="text" id="practice_supervisor_name" name="practice_supervisor_name" required>
            </div>
            <div class="form-group">
                <label for="practice_supervisor_position">Должность руководителя практики:</label>
                <input type="text" id="practice_supervisor_position" name="practice_supervisor_position" required>
            </div>
            <div class="form-group">
                <label for="practice_from">От кого:</label>
                <input type="text" id="practice_from" name="practice_from" required>
            </div>
            <button type="submit">Добавить практику</button>
            
        </form>
        <a href="index.php" class="btn btn-secondary">Назад к панели управления</a>

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
