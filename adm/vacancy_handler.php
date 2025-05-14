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

$title = $_POST['title'];
$description = $_POST['description'];
$requirements = $_POST['requirements'];
$salary = $_POST['salary'];
$company_id = $_SESSION['user_id'];

// Валидация данных (пример)
$title = htmlspecialchars(strip_tags($title));
$description = htmlspecialchars(strip_tags($description));
$requirements = htmlspecialchars(strip_tags($requirements));
$salary = htmlspecialchars(strip_tags($salary));
$company_id = intval($company_id);

// Подготовленный запрос для защиты от SQL-инъекций
$sql = "INSERT INTO vacancies (company_id, title, description, requirements, salary) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $company_id, $title, $description, $requirements, $salary);

if ($stmt->execute()) {
    header("Location: profile.php?success=vacancy_created");
    exit();
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>