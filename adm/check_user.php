<?php
// Подключение к базе данных (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['available' => false, 'message' => 'Логин занят']);
    } else {
        echo json_encode(['available' => true]);
    }
    $stmt->close();
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['available' => false, 'message' => 'Email занят']);
    } else {
        echo json_encode(['available' => true]);
    }
    $stmt->close();
} else{
    echo json_encode(['error' => 'invalid request']);
}

$conn->close();
?>