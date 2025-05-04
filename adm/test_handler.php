<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    // Если не авторизован, перенаправляем на страницу логина
    header("Location: login.php");
    exit();
}

// Подключение к базе данных (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем данные из POST-запроса
$test_results = $_POST['test_results'];
$recommendations = $_POST['recommendations'];

// Получаем ID пользователя из сессии
$user_id = $_SESSION['user_id'];

// Подготавливаем SQL-запрос для обновления данных пользователя
$sql = "UPDATE users SET proforientation_test_results = ?, proforientation_recommendations = ? WHERE id = ?";

// Создаем подготовленный запрос
$stmt = $conn->prepare($sql);

// Привязываем параметры
$stmt->bind_param("ssi", $test_results, $recommendations, $user_id);

// Выполняем запрос
if ($stmt->execute()) {
    // Если обновление прошло успешно, отправляем сообщение об успехе
    $response = array('status' => 'success', 'message' => 'Результаты тестирования успешно сохранены.');
} else {
    // Если произошла ошибка, отправляем сообщение об ошибке
    $response = array('status' => 'error', 'message' => 'Ошибка при сохранении результатов тестирования: ' . $stmt->error);
}

// Закрываем запрос и соединение с базой данных
$stmt->close();
$conn->close();

// Отправляем JSON-ответ
header('Content-type: application/json');
echo json_encode($response);
?>