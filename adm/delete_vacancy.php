<?php
session_start();

if ($_SESSION['role'] != 'employer') {
    die("Доступ запрещен");
}

// Подключение к БД (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем ID вакансии из GET-параметра
$vacancy_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($vacancy_id <= 0) {
    die("Некорректный ID вакансии.");
}

// Подготовленный запрос для удаления вакансии
$sql = "DELETE FROM vacancies WHERE id = ? AND company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vacancy_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: profile.php?success=vacancy_deleted");
    exit();
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>