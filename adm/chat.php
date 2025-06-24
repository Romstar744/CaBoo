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

if ($chat_id <= 0) {
    die("Некорректный ID чата.");
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

// Получаем информацию о чате
$chat = $result->fetch_assoc();
$employer_id = $chat['employer_id'];
$seeker_id = $chat['seeker_id'];

// Определяем ID собеседника (того, с кем текущий пользователь ведет чат)
$собеседник_id = ($user_id == $employer_id) ? $seeker_id : $employer_id;

// Получаем имя собеседника
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $собеседник_id);
$stmt->execute();
$result = $stmt->get_result();
$собеседник = $result->fetch_assoc();
$собеседник_имя_пользователя = $собеседник['username'];

// Получаем историю сообщений
$sql = "SELECT m.*, u.username FROM messages m JOIN users u ON m.sender_id = u.id WHERE chat_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат с <?php echo htmlspecialchars($собеседник_имя_пользователя); ?></title>
    <link rel="stylesheet" href="../css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        const userId = <?php echo json_encode($user_id); ?>;
        const chatId = <?php echo json_encode($chat_id); ?>;
    </script>
</head>
<body>
    <div class="chat-container">
        <h1>Чат с <?php echo htmlspecialchars($собеседник_имя_пользователя); ?></h1>
        <div class="messages"></div>

        <form id="messageForm" action="send_message.php" method="post">
            <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
            <textarea id="messageInput" name="message" placeholder="Введите сообщение"></textarea>
            <button type="submit">Отправить</button>
        </form>
    </div>
    <a href="profile.php">Вернуться в личный кабинет</a>
    <script src="../js/chat.js"></script>
</body>
</html>
