<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Подключение к базе данных (ЗАМЕНИТЕ НА СВОИ ДАННЫЕ)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employer_id = $_SESSION["user_id"];
$seeker_id = isset($_GET['seeker_id']) ? intval($_GET['seeker_id']) : 0;

if ($seeker_id <= 0) {
    die("Некорректный ID соискателя.");
}

// Проверяем, существует ли уже чат между работодателем и соискателем
$sql = "SELECT id FROM chats WHERE employer_id = ? AND seeker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $employer_id, $seeker_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Чат уже существует, получаем ID чата
    $chat = $result->fetch_assoc();
    $chat_id = $chat['id'];
} else {
    // Чат не существует, создаем новый чат
    $sql = "INSERT INTO chats (employer_id, seeker_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $employer_id, $seeker_id);
    if ($stmt->execute()) {
        $chat_id = $conn->insert_id;
    } else {
        die("Ошибка при создании чата: " . $conn->error);
    }
}

header("Location: chat.php?chat_id=" . $chat_id);
exit();
?>