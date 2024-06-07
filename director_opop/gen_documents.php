<?php
require_once '../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

// Функция для заполнения шаблона данными из таблицы
function fillTemplateWithData($templatePath, $data, $outputPath)
{
    $templateProcessor = new TemplateProcessor($templatePath);
    
    foreach ($data as $index => $row) {
        foreach ($row as $key => $value) {
            $templateProcessor->setValue($key . '_' . $index, $value);
        }
    }

    $templateProcessor->saveAs($outputPath);
}

// Подключение к базе данных
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "SystemBase";

try {
    // Подключение к базе данных
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Запрос к таблице PracticeAdmin
    $stmtAdmin = $conn->query("SELECT * FROM PracticeAdmin");
    $practiceAdminData = $stmtAdmin->fetchAll(PDO::FETCH_ASSOC);

    // Запрос к таблице PracticeDirector
    $stmtDirector = $conn->query("SELECT * FROM PracticeDirector");
    $practiceDirectorData = $stmtDirector->fetchAll(PDO::FETCH_ASSOC);
    
    // Запрос к таблице Students
    $stmtStudents = $conn->query("SELECT id, student_name FROM Students");
    $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

    // Проверяем, была ли форма отправлена
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Получение данных из формы
        $practice_id = $_POST['practice_id'];
        $year = $_POST['year'];
        $student_id = $_POST['student_id'];
        $practice_date = $_POST['practice_date'];
        $practice_period = $_POST['practice_period'];
        $name = $_POST['name'];
        $practice_address = $_POST['practice_address'];
        $practice_type = $_POST['practice_type'];
        $practice_place = $_POST['practice_place'];
        $order_number_and_date = $_POST['order_number_and_date'];
        $institute = $_POST['institute'];
        $paid_practice = $_POST['paid_practice'];
        $grade = $_POST['grade'];
        $handling_difficulties = $_POST['handling_difficulties'];
        $remarks = $_POST['remarks'];
        $course = $_POST['course'];
        $group_name = $_POST['group_name'];
        $supervisor_name = $_POST['supervisor_name'];
        $supervisor_position = $_POST['supervisor_position'];
        $contract_type = $_POST['contract_type'];
        $ysu_practice_supervisor = $_POST['ysu_practice_supervisor'];
        $organization_practice_supervisor = $_POST['organization_practice_supervisor'];
        $city = $_POST['city'];
        $reason = $_POST['reason'];

        // Выполняем запрос на вставку данных в базу данных
        $stmt = $conn->prepare("INSERT INTO Practice (practice_id, year, student_id, practice_date, practice_period, name, practice_address, practice_type, practice_place, order_number_and_date, institute, paid_practice, grade, handling_difficulties, remarks, course, group_name, supervisor_name, supervisor_position, contract_type, ysu_practice_supervisor, organization_practice_supervisor, city, reason) VALUES (:practice_id, :year, :student_id, :practice_date, :practice_period, :name, :practice_address, :practice_type, :practice_place, :order_number_and_date, :institute, :paid_practice, :grade, :handling_difficulties, :remarks, :course, :group_name, :supervisor_name, :supervisor_position, :contract_type, :ysu_practice_supervisor, :organization_practice_supervisor, :city, :reason)");
        $stmt->bindParam(':practice_id', $practice_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':practice_date', $practice_date);
        $stmt->bindParam(':practice_period', $practice_period);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':practice_address', $practice_address);
        $stmt->bindParam(':practice_type', $practice_type);
        $stmt->bindParam(':practice_place', $practice_place);
        $stmt->bindParam(':order_number_and_date', $order_number_and_date);
        $stmt->bindParam(':institute', $institute);
        $stmt->bindParam(':paid_practice', $paid_practice);
        $stmt->bindParam(':grade', $grade);
        $stmt->bindParam(':handling_difficulties', $handling_difficulties);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':group_name', $group_name);
        $stmt->bindParam(':supervisor_name', $supervisor_name);
        $stmt->bindParam(':supervisor_position', $supervisor_position);
        $stmt->bindParam(':contract_type', $contract_type);
        $stmt->bindParam(':ysu_practice_supervisor', $ysu_practice_supervisor);
        $stmt->bindParam(':organization_practice_supervisor', $organization_practice_supervisor);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':reason', $reason);

        $stmt->execute();

        echo "Данные успешно добавлены в базу данных.";
    }
} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

// Закрываем соединение с базой данных
$conn = null;
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Генератор документа</title>
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

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23444" viewBox="0 0 24 24" width="24" height="24"><path d="M7 10l5 5 5-5z"></path></svg>');
            background-repeat: no-repeat;
            background-position-x: 95%;
            background-position-y: center;
            padding-right: 30px;
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
    </style>
</head>
<body>
<div class="container">
        <h1>Выберите данные для документа</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form">
            <div class="form-group">
                <label for="student_id">Выберите студента:</label>
                <select id="student_id" name="student_id" required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['id'];  ?>"><?php echo $student['student_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Остальные поля формы -->
            <div class="form-group">
                <label for="institute">Институт:</label>
                <input type="text" id="institute" name="institute" required>
            </div>
            <div class="form-group">
                <label for="course">Курс:</label>
                <input type="text" id="course" name="course" required>
            </div>
            <div class="form-group">
                <label for="group_name">Группа:</label>
                <input type="text" id="group_name" name="group_name" required>
            </div>
            <div class="form-group">
                <label for="supervisor_name">ФИО руководителя образовательной программы:</label>
                <input type="text" id="supervisor_name" name="supervisor_name" required>
            </div>
            <div class="form-group">
                <label for="supervisor_position">Должность руководителя образовательной программы:</label>
                <input type="text" id="supervisor_position" name="supervisor_position" required>
            </div>
            <div class="form-group">
                <label for="group_director">Группа:</label>
                <input type="text" id="group_director" name="group_director" required>
            </div>
            <div class="form-group">
                <label for="year">Год:</label>
                <input type="text" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="practice_date">Дата практики:</label>
                <input type="date" id="practice_date" name="practice_date" required>
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
                <label for="practice_address">Адрес практики:</label>
                <input type="text" id="practice_address" name="practice_address" required>
            </div>
            <div class="form-group">
                <label for="practice_type_id">Тип практики:</label>
                <input type="text" id="practice_type_id" name="practice_type" required>
            </div>
            <div class="form-group">
                <label for="practice_place_id">Место практики:</label>
                <input type="text" id="practice_place" name="practice_place" required>
            </div>
            <div class="form-group">
                <label for="order_number_and_date">Номер и дата приказа:</label>
                <input type="text" id="order_number_and_date" name="order_number_and_date" required>
            </div>
            <div class="form-group">
                <label for="paid_practice">Оплачиваемая практика:</label>
                <input type="text" id="paid_practice" name="paid_practice" required>
            </div>
            <div class="form-group">
                <label for="grade">Оценка:</label>
                <input type="text" id="grade" name="grade" required>
            </div>
            <div class="form-group">
                <label for="handling_difficulties">Трудности:</label>
                <input type="text" id="handling_difficulties" name="handling_difficulties" required>
            </div>
            <div class="form-group">
                <label for="remarks">Примечания:</label>
                <input type="text" id="remarks" name="remarks" required>
            </div>
            <div class="form-group">
                <label for="contract_type">Тип контракта:</label>
                <input type="text" id="contract_type" name="contract_type" required>
            </div>
            <div class="form-group">
                <label for="ysu_practice_supervisor">Руководитель практики (УНИВЕРСИТЕТ):</label>
                <input type="text" id="ysu_practice_supervisor" name="ysu_practice_supervisor" required>
            </div>
            <div class="form-group">
                <label for="organization_practice_supervisor">Руководитель практики (ОРГАНИЗАЦИЯ):</label>
                <input type="text" id="organization_practice_supervisor" name="organization_practice_supervisor" required>
            </div>
            <div class="form-group">
                <label for="city">Город:</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="reason">Причина:</label>
                <input type="text" id="reason" name="reason" required>
            </div>
            <button type="submit">Сгенерировать документ</button>
            <a href="index.php" class="btn btn-secondary">Назад к панели управления</a>
        </form>
    </div>

    <script>
        window.onload = function() {
            <?php foreach ($practiceAdminData as $row): ?>
                document.getElementById('institute').value = '<?php echo htmlspecialchars($row['institute']); ?>';
                document.getElementById('direction').value = '<?php echo htmlspecialchars($row['direction']); ?>';
                document.getElementById('course').value = '<?php echo htmlspecialchars($row['course']); ?>';
                document.getElementById('group').value = '<?php echo htmlspecialchars($row['group_name']); ?>';
                document.getElementById('supervisor_name').value = '<?php echo htmlspecialchars($row['supervisor_name']); ?>';
                document.getElementById('supervisor_position').value = '<?php echo htmlspecialchars($row['supervisor_position']); ?>';
            <?php endforeach; ?>

            <?php foreach ($practiceDirectorData as $row): ?>
                document.getElementById('group_director').value = '<?php echo htmlspecialchars($row['group_name']); ?>';
                document.getElementById('year').value = '<?php echo htmlspecialchars($row['year']); ?>';
                document.getElementById('practice_date_from').value = '<?php echo htmlspecialchars($row['practice_date_from']); ?>';
                document.getElementById('practice_date_to').value = '<?php echo htmlspecialchars($row['practice_date_to']); ?>';
                document.getElementById('practice_name').value = '<?php echo htmlspecialchars($row['practice_name']); ?>';
                document.getElementById('order_number_and_date').value = '<?php echo htmlspecialchars($row['order_number_and_date']); ?>';
                document.getElementById('practice_type').value = '<?php echo htmlspecialchars($row['practice_type']); ?>';
                document.getElementById('practice_location').value = '<?php echo htmlspecialchars($row['practice_location']); ?>';
                document.getElementById('practice_supervisor_name').value = '<?php echo htmlspecialchars($row['practice_supervisor_name']); ?>';
                document.getElementById('practice_supervisor_position').value = '<?php echo htmlspecialchars($row['practice_supervisor_position']); ?>';
                document.getElementById('practice_from').value = '<?php echo htmlspecialchars($row['practice_from']); ?>';
            <?php endforeach; ?>
        };
    </script>
</body>
</html>
