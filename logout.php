<?php
session_start();
session_unset(); // Удаление всех переменных сеанса
session_destroy(); // Уничтожение сеанса
header("Location: login.php"); // Редирект на страницу входа
exit;
?>
