<?php
var_dump($_POST);

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "SystemBase";

try {
    // Подключение к базе данных
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получение данных из формы
    $student_name = $_POST['student_name'];
    $grade = $_POST['grade']; // Добавлено получение данных об оценке

    // SQL-запрос для вставки данных студента с оценкой
    $stmt = $conn->prepare("INSERT INTO Students (student_name, grade) VALUES (:student_name, :grade)");
    $stmt->bindParam(':student_name', $student_name);
    $stmt->bindParam(':grade', $grade); // Привязываем значение оценки
    $stmt->execute();

    echo "Данные студента успешно добавлены.";

    // Дополнительный запрос для отображения всех студентов
    $stmt = $conn->query("SELECT * FROM Students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($students);
    echo "</pre>";

} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

// Закрытие соединения с базой данных
$conn = null;
?>
