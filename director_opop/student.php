<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ввод данных студента</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ввод данных студента</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form" id="studentForm">
            <div class="form-group">
                <label for="student_name">ФИО студента:</label>
                <input type="text" id="student_name" name="student_name" required>
            </div>
            <div class="form-group">
                <label for="grade">Оценка:</label>
                <input type="number" id="grade" name="grade" min="1" max="5" step="0.1" required>
            </div>
            <button type="submit">Сохранить студента</button>
            <a href="index.php" class="btn btn-secondary">Назад к панели управления</a>
        </form>

        <h2>Список студентов</h2>
        <table>
            <tr>
                <th>ФИО студента</th>
                <th>Оценка</th>
            </tr>
            <?php
            // Подключение к базе данных
            $servername = "127.0.0.1";
            $username = "root";
            $password = "";
            $dbname = "SystemBase";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['student_name']) && !empty($_POST['grade'])) {
                    // Получение данных из формы
                    $student_name = htmlspecialchars($_POST['student_name']);
                    $grade = htmlspecialchars($_POST['grade']);

                    // SQL-запрос для вставки данных студента
                    $stmt = $conn->prepare("INSERT INTO Students (student_name, grade) VALUES (:student_name, :grade)");
                    $stmt->bindParam(':student_name', $student_name);
                    $stmt->bindParam(':grade', $grade);
                    $stmt->execute();
                    
                    echo "Данные студента успешно добавлены.";
                }

                // Вывод списка студентов
                $stmt = $conn->query("SELECT * FROM Students");
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($students as $student) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($student['student_name']) . "</td>";
                    echo "<td>" . ($student['grade'] !== null ? htmlspecialchars($student['grade']) : 'Нет данных') . "</td>";
                    echo "</tr>";
                }
            } catch(PDOException $e) {
                echo "Ошибка: " . $e->getMessage();
            }

            // Закрытие соединения с базой данных
            $conn = null;
            ?>
        </table>
    </div>
</body>
</html>
