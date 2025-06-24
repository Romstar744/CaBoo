<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
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

$user_id = $_SESSION["user_id"];
$chat_id = isset($_POST['chat_id']) ? intval($_POST['chat_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($chat_id <= 0 || empty($message)) {
    die("Некорректные данные.");
}

// Проверяем, имеет ли текущий пользователь доступ к этому чату
$sql = "SELECT * FROM chats WHERE id = ? AND (employer_id = ? OR seeker_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $chat_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("У вас нет доступа к этому чату.");
}

// Сохраняем сообщение в базе данных
$sql = "INSERT INTO messages (chat_id, sender_id, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $chat_id, $user_id, $message);

if ($stmt->execute()) {
    header("Location: chat.php?chat_id=" . $chat_id);
    exit();
} else {
    die("Ошибка при отправке сообщения: " . $conn->error);
}

$conn->close();
?>