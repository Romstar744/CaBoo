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

// Получаем данные из формы
$vacancy_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$title = $_POST['title'];
$description = $_POST['description'];
$requirements = $_POST['requirements'];
$salary = $_POST['salary'];

// Проверяем, что ID вакансии корректный
if ($vacancy_id <= 0) {
    die("Некорректный ID вакансии.");
}

// Подготовленный запрос для обновления данных о вакансии
$sql = "UPDATE vacancies SET title = ?, description = ?, requirements = ?, salary = ? WHERE id = ? AND company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $title, $description, $requirements, $salary, $vacancy_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: profile.php?success=vacancy_updated");
    exit();
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>