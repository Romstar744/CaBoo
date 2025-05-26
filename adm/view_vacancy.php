<?php
session_start();

if (!isset($_SESSION["user_id"])) {
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

// Получаем ID вакансии из GET-параметра
$vacancy_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($vacancy_id <= 0) {
    die("Некорректный ID вакансии.");
}

// Получаем информацию о вакансии из базы данных
$sql = "SELECT v.*, u.company_name 
        FROM vacancies v 
        JOIN users u ON v.company_id = u.id 
        WHERE v.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vacancy_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $vacancy = $result->fetch_assoc();
} else {
    die("Вакансия не найдена.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($vacancy['title']); ?></title>
    <link rel="stylesheet" href="../css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <div class="profile-form">
            <h1><?php echo htmlspecialchars($vacancy['title']); ?></h1>
            <p>Компания: <?php echo htmlspecialchars($vacancy['company_name']); ?></p>
            <p>Зарплата: <?php echo htmlspecialchars($vacancy['salary']); ?> ₽</p>
            <p>Описание: <?php echo htmlspecialchars($vacancy['description']); ?></p>
            <p>Требования: <?php echo htmlspecialchars($vacancy['requirements']); ?></p>
            <a href="profile.php" class="back-link">Вернуться в личный кабинет</a>
        </div>
    </div>
</body>
</html>