<?php

function redirect($url) {
    header("Location: $url");
    exit();
}

function display_error($error) {
    echo "<div class='alert alert-danger'>$error</div>";
}

function display_success($message) {
    echo "<div class='alert alert-success'>$message</div>";
}

function generate_random_password($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

function sanitize_input($input) {
    return htmlspecialchars(trim($input));
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function encrypt_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hashed_password) {
    return password_verify($password, $hashed_password);
}

function is_authenticated() {
    return isset($_SESSION['user_id']);
}

function logout() {
    session_unset();
    session_destroy();
}

include_once 'db.php';

function add_practice($conn, $year, $practice_period, $name, $order_number_and_date, $practice_type_id, $practice_place_id, $contract_type_id, $paid_practice, $YSU_practice_supervisor_id, $enterprise_practice_supervisor_id, $organization_practice_supervisor_id) {
    $stmt = $conn->prepare("INSERT INTO Practice (year, practice_period, name, order_number_and_date, practice_type_id, practice_place_id, contract_type_id, paid_practice, YSU_practice_supervisor_id, enterprise_practice_supervisor_id, organization_practice_supervisor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiiiiiiii", $year, $practice_period, $name, $order_number_and_date, $practice_type_id, $practice_place_id, $contract_type_id, $paid_practice, $YSU_practice_supervisor_id, $enterprise_practice_supervisor_id, $organization_practice_supervisor_id);
    $stmt->execute();
    $stmt->close();
}

function get_all_practices($conn) {
    $result = $conn->query("SELECT * FROM Practice");
    $practices = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $practices[] = $row;
        }
    }
    return $practices;
}

function get_students_with_practices($conn) {
    $result = $conn->query("SELECT * FROM Student_Practice");
    $students_with_practices = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students_with_practices[] = $row;
        }
    }
    return $students_with_practices;
}

function add_student($conn, $full_name) {
    $stmt = $conn->prepare("INSERT INTO Student (full_name) VALUES (?)");
    $stmt->bind_param("s", $full_name);
    $stmt->execute();
    $stmt->close();
}

function get_all_students($conn) {
    $result = $conn->query("SELECT * FROM Student");
    $students = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    return $students;
}

function add_student_to_practice($conn, $practice_id, $student_id) {
    $stmt = $conn->prepare("INSERT INTO Student_Practice (practice_id, student_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $practice_id, $student_id);
    $stmt->execute();
    $stmt->close();
}

function get_students_by_practice($conn, $practice_id) {
    $stmt = $conn->prepare("SELECT * FROM Student_Practice WHERE practice_id = ?");
    $stmt->bind_param("i", $practice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
    return $students;
}

function get_student_practices($conn, $student_id) {
    $stmt = $conn->prepare("SELECT * FROM Student_Practice WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $practices = [];
    while ($row = $result->fetch_assoc()) {
        $practices[] = $row;
    }
    $stmt->close();
    return $practices;
}

function add_to_list($conn, $name) {
    $stmt = $conn->prepare("INSERT INTO List (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
}

function get_all_from_list($conn) {
    $result = $conn->query("SELECT * FROM List");
    $items = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

function add_practice_type($conn, $name) {
    $stmt = $conn->prepare("INSERT INTO Practice_Type (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
}

function get_all_practice_types($conn) {
    $result = $conn->query("SELECT * FROM Practice_Type");
    $practice_types = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $practice_types[] = $row;
        }
    }
    return $practice_types;
}

function add_practice_place($conn, $name, $address) {
    $stmt = $conn->prepare("INSERT INTO Practice_Place (name, address) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $address);
    $stmt->execute();
    $stmt->close();
}

function get_all_practice_places($conn) {
    $result = $conn->query("SELECT * FROM Practice_Place");
    $practice_places = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $practice_places[] = $row;
        }
    }
    return $practice_places;
}
function is_practice_supervisor($conn, $supervisor_id) {
    $stmt = $conn->prepare("SELECT * FROM YSU_Practice_Supervisor WHERE id = ?");
    $stmt->bind_param("i", $supervisor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}


function is_teacher() {
    // Здесь реализация проверки, имеет ли пользователь статус преподавателя
    // Например, если у вас есть таблица users с полем role, где 'teacher' обозначает статус преподавателя
    global $conn; // Подключение к базе данных
    $user_id = $_SESSION['user_id']; // Получаем ID текущего пользователя из сессии
    $query = "SELECT role FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return ($user['role'] == 'teacher');
}

function variableExists($templateProcessor, $variableName) {
    // Получаем все переменные из шаблона
    $variables = $templateProcessor->getVariables();

    // Проверяем, существует ли переменная в массиве переменных
    return in_array($variableName, $variables);
}
// Проверка существования практики по ID
function practiceExists($conn, $practice_id) {
    $sql = "SELECT id FROM practice WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $practice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Проверка существования студента по ID
function studentExists($conn, $student_id) {
    $sql = "SELECT id FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Проверка, добавлен ли студент к практике
function isStudentAddedToPractice($conn, $practice_id, $student_id) {
    $sql = "SELECT * FROM practice_students WHERE practice_id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $practice_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Добавьте другие функции, если необходимо

?>
