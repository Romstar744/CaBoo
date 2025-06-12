<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
    exit();
}

$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Ошибка подключения к базе данных: ' . $conn->connect_error]);
    exit();
}

$user_id = $_SESSION["user_id"];

// Установка типа контента для JSON-ответа
header('Content-Type: application/json');

// Обработка добавления/удаления вакансии в избранное (для соискателей)
if (isset($_GET['vacancy_id'])) {
    $vacancy_id = intval($_GET['vacancy_id']);

    // Проверяем, находится ли вакансия уже в избранном
    $sql = "SELECT COUNT(*) FROM favorites_vacancies WHERE user_id = ? AND vacancy_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $vacancy_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];

    if ($count > 0) {
        // Удаляем из избранного
        $sql = "DELETE FROM favorites_vacancies WHERE user_id = ? AND vacancy_id = ?";
    } else {
        // Добавляем в избранное
        $sql = "INSERT INTO favorites_vacancies (user_id, vacancy_id) VALUES (?, ?)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $vacancy_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
// Обработка добавления/удаления соискателя в избранное (для работодателей)
elseif (isset($_GET['jobseeker_id'])) {
    $jobseeker_id = intval($_GET['jobseeker_id']);

    // Проверяем, находится ли соискатель уже в избранном
    $sql = "SELECT COUNT(*) FROM favorites_jobseekers WHERE employer_id = ? AND jobseeker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $jobseeker_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];

    if ($count > 0) {
        // Удаляем из избранного
        $sql = "DELETE FROM favorites_jobseekers WHERE employer_id = ? AND jobseeker_id = ?";
    } else {
        // Добавляем в избранное
        $sql = "INSERT INTO favorites_jobseekers (employer_id, jobseeker_id) VALUES (?, ?)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $jobseeker_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Не указан ID вакансии или соискателя']);
}

$conn->close();
?>