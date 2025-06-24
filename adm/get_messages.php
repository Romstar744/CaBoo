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
$chat_id = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;
$last_message_id = isset($_GET['last_message_id']) ? intval($_GET['last_message_id']) : 0;

if ($chat_id <= 0) {
    echo json_encode([]);
    exit();
}


// Проверяем, имеет ли текущий пользователь доступ к этому чату
$sql = "SELECT * FROM chats WHERE id = ? AND (employer_id = ? OR seeker_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $chat_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode([]);
    exit();
}


$sql = "SELECT m.*, u.username FROM messages m JOIN users u ON m.sender_id = u.id WHERE chat_id = ? AND m.id > ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $chat_id, $last_message_id);
$stmt->execute();
$messages = $stmt->get_result();
$messages_arr = array();
while($row = $messages->fetch_assoc()) {
    $messages_arr[] = $row;
}
header('Content-Type: application/json');
echo json_encode($messages_arr);
$conn->close();
?>