<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

// Функция для замены полей в документе
function generate_docx_from_template($template_file, $data_sets, $output_dir) {
    if (!is_dir($output_dir)) {
        mkdir($output_dir, 0777, true);
    }

    $output_file = $output_dir . '/generated_document.docx';
    $templateProcessor = new TemplateProcessor($template_file);

    // Пример заполнения шаблона данными из объединенного массива данных
    $index = 0;
    foreach ($data_sets['practice'] as $data) {
        $index++;
        if ($index <= count($data_sets['practice'])) {
            // Заполнение первого шаблона
            $templateProcessor->setValue("practice_id", $data['practice_id']);
            $templateProcessor->setValue("year", $data['year']);
            $templateProcessor->setValue("student_name", $data['student_name']); // Изменено с student_id на student_name
            $templateProcessor->setValue("practice_date", $data['practice_date']);
            $templateProcessor->setValue("practice_period", $data['practice_period']);
            $templateProcessor->setValue("name", $data['name']);
            $templateProcessor->setValue("practice_address", $data['practice_address']);
            $templateProcessor->setValue("practice_type", $data['practice_type']);
            $templateProcessor->setValue("practice_place", $data['practice_place']);
            $templateProcessor->setValue("order_number_and_date", $data['order_number_and_date']);
            $templateProcessor->setValue("institute", $data['institute']);
            $templateProcessor->setValue("paid_practice", $data['paid_practice']);
            $templateProcessor->setValue("grade", $data['grade']);
            $templateProcessor->setValue("handling_difficulties", $data['handling_difficulties']);
            $templateProcessor->setValue("remarks", $data['remarks']);
            $templateProcessor->setValue("course", $data['course']);
            $templateProcessor->setValue("group_name", $data['group_name']);
            $templateProcessor->setValue("supervisor_name", $data['supervisor_name']);
            $templateProcessor->setValue("supervisor_position", $data['supervisor_position']);
            $templateProcessor->setValue("contract_type", $data['contract_type']);
            $templateProcessor->setValue("ysu_practice_supervisor", $data['ysu_practice_supervisor']);
            $templateProcessor->setValue("organization_practice_supervisor", $data['organization_practice_supervisor']);
            $templateProcessor->setValue("city", $data['city']);
            $templateProcessor->setValue("reason", $data['reason']);
        } else {
            // Заполнение второго шаблона
            $newIndex = $index - count($data_sets['practice']);
            $templateProcessor->setValue("group_name_$newIndex", $data['group_name']);
            $templateProcessor->setValue("year_$newIndex", $data['year']);
            $templateProcessor->setValue("practice_date_to_$newIndex", $data['practice_date_to']);
            $templateProcessor->setValue("practice_name_$newIndex", $data['practice_name']);
            $templateProcessor->setValue("order_number_and_date_$newIndex", $data['order_number_and_date']);
            $templateProcessor->setValue("practice_type_$newIndex", $data['practice_type']);
            $templateProcessor->setValue("practice_location_$newIndex", $data['practice_location']);
            $templateProcessor->setValue("practice_supervisor_name_$newIndex", $data['practice_supervisor_name']);
            $templateProcessor->setValue("practice_supervisor_position_$newIndex", $data['practice_supervisor_position']);
            $templateProcessor->setValue("practice_from_$newIndex", $data['practice_from']);
        }
    }

    // Сохраняем результат в новый документ
    $templateProcessor->saveAs($output_file);

    // Изменяем права доступа к файлу
    chmod($output_file, 0666);

    return $output_file;
}

// Подключение к базе данных
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "SystemBase";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Выполнение запроса для получения данных о практиках с присоединением данных студентов
    $stmt = $conn->query("
        SELECT 
            Practice.*, 
            Students.student_name 
        FROM Practice 
        JOIN Students ON Practice.student_id = Students.id
    ");
    $data_sets['practice'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output_dir = 'output_directory'; // Пример пути к папке для сохранения сгенерированных документов

    // Путь к шаблону первого документа
    $template_file_1 = 'shablonfile/c56e6326d3007be2.docx'; // Пример пути к первому шаблону
    // Путь к шаблону второго документа
    $template_file_2 = 'shablonfile/1121.docx'; // Пример пути ко второму шаблону

    // Вызов функции для генерации первого документа
    $generated_document_1 = generate_docx_from_template($template_file_1, $data_sets, $output_dir . '/generated_document1.docx');
    // Вызов функции для генерации второго документа
    $generated_document_2 = generate_docx_from_template($template_file_2, $data_sets, $output_dir . '/generated_document2.docx');

} catch(PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
} catch(Exception $e) {
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
    <title>Скачать сгенерированные документы</title>
</head>
<body>
    <h2>Скачать сгенерированные документы:</h2>
    <ul>
        <li><a href="<?php echo $generated_document_1; ?>" download>Скачать первый документ</a></li>
        <li><a href="<?php echo $generated_document_2; ?>" download>Скачать второй документ</a></li>
    </ul>
</body>
</html>
