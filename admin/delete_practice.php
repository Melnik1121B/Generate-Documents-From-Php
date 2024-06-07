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

// Выбор всех практик из таблицы
$sql = "SELECT * FROM Practice";
$result = $conn->query($sql);

// Закрываем соединение с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список практик</title>
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
            max-width: 800px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Список практик</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Группа</th>
                    <th>Год</th>
                    <th>Дата прохождения</th>
                    <th>Название практики</th>
                    <th>Номер и дата приказа</th>
                    <th>Вид практики</th>
                    <th>Место практики</th>
                    <th>ФИО руководителя практики</th>
                    <th>Должность руководителя практики</th>
                    <th>От кого</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Выводим данные о каждой практике
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["practice_id"] . "</td>";
                        echo "<td>" . $row["group_name"] . "</td>";
                        echo "<td>" . $row["year"] . "</td>";
                        echo "<td>" . $row["practice_date"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["order_number_and_date"] . "</td>";
                        echo "<td>" . $row["practice_type"] . "</td>";
                        echo "<td>" . $row["practice_place"] . "</td>";
                        echo "<td>" . $row["supervisor_name"] . "</td>";
                        echo "<td>" . $row["supervisor_position"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Нет данных о практиках</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Назад к панели управления</a>
    </div>
</body>
</html>
