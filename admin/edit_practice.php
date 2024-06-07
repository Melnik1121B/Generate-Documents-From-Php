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
    $year = $_POST['year'] ?? '';
    $practice_period = $_POST['practice_period'] ?? '';
    $name = $_POST['name'] ?? '';
    $order_number_and_date = $_POST['order_number_and_date'] ?? '';
    $institute = $_POST['institute'] ?? '';
    $course = $_POST['course'] ?? '';
    $group = $_POST['group'] ?? '';
    $supervisor_name = $_POST['supervisor_name'] ?? '';
    $supervisor_position = $_POST['supervisor_position'] ?? '';
    $contract_type = $_POST['contract_type'] ?? '';
    $paid_practice = isset($_POST['paid_practice']) ? 1 : 0;
    $ysu_practice_supervisor = $_POST['ysu_practice_supervisor'] ?? '';
    $organization_practice_supervisor = $_POST['organization_practice_supervisor'] ?? '';

    // Валидация данных
    if (empty($year) || empty($practice_period) || empty($name) || empty($order_number_and_date) || empty($institute) || empty($course) || empty($group) || empty($supervisor_name) || empty($supervisor_position) || empty($contract_type) || empty($ysu_practice_supervisor) || empty($organization_practice_supervisor)) {
        $errors[] = "Все поля обязательны для заполнения";
    }

    // Если нет ошибок, выполняем вставку данных в таблицу
    if (empty($errors)) {
        $sql = "INSERT INTO Practice (year, practice_period, name, order_number_and_date, institute, course, group_name, supervisor_name, supervisor_position, contract_type, paid_practice, ysu_practice_supervisor, organization_practice_supervisor) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Подготовка запроса
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $errors[] = "Ошибка подготовки запроса: " . $conn->error;
        } else {
            // Привязываем параметры
            $stmt->bind_param("issssssssssss", $year, $practice_period, $name, $order_number_and_date, $institute, $course, $group, $supervisor_name, $supervisor_position, $contract_type, $paid_practice, $ysu_practice_supervisor, $organization_practice_supervisor);

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
    <title>Редактировать практику</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
            overflow-y: scroll;
            max-height: 80vh;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактировать практику</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form">
            <div class="form-group">
                <label for="year">Год:</label>
                <input type="text" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="practice_period">Период практики:</label>
                <input type="text" id="practice_period" name="practice_period" required>
            </div>
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="order_number_and_date">Номер и дата приказа:</label>
                <input type="text" id="order_number_and_date" name="order_number_and_date" required>
            </div>
            <div class="form-group">
                <label for="institute">Институт:</label>
                <input type="text" id="institute" name="institute" required>
            </div>
            <div class="form-group">
                <label for="course">Направление подготовки (курс):</label>
                <input type="text" id="course" name="course" required>
            </div>
            <div class="form-group">
                <label for="group">Группа:</label>
                <input type="text" id="group" name="group" required>
            </div>
            <div class="form-group">
                <label for="supervisor_name">ФИО руководителя практики:</label>
                <input type="text" id="supervisor_name" name="supervisor_name" required>
            </div>
            <div class="form-group">
                <label for="supervisor_position">Должность руководителя практики:</label>
                <input type="text" id="supervisor_position" name="supervisor_position" required>
            </div>
            <div class="form-group">
                <label for="contract_type">Тип практики:</label>
                <select id="contract_type" name="contract_type" required>
                    <option value="учебная практика">Учебная практика</option>
                    <option value="производственная практика">Производственная практика</option>
                    <option value="преддипломная практика">Преддипломная практика</option>
                </select>
            </div>
            <div class="form-group">
                <label for="paid_practice">Оплачиваемая практика:</label>
                <input type="checkbox" id="paid_practice" name="paid_practice" value="1">
            </div>
            <div class="form-group">
                <label for="ysu_practice_supervisor">Руководитель практики ЮГУ:</label>
                <input type="text" id="ysu_practice_supervisor" name="ysu_practice_supervisor" required>
            </div>
            <div class="form-group">
                <label for="organization_practice_supervisor">Руководитель практики в организации:</label>
                <input type="text" id="organization_practice_supervisor" name="organization_practice_supervisor" required>
            </div>
            <button type="submit">Редактировать практику</button>
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
