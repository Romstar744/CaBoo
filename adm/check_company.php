<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['available' => false, 'message' => "Ошибка подключения к базе данных: " . $conn->connect_error]));
}

$companyName = $_GET['company_name'] ?? '';

if (empty($companyName)) {
    die(json_encode(['available' => false, 'message' => 'Название компании не может быть пустым.']));
}

$sql = "SELECT id FROM users WHERE company_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $companyName);
$stmt->execute();
$stmt->store_result();

$response = ['available' => $stmt->num_rows === 0, 'message' => ''];

if ($stmt->num_rows > 0) {
    $response['message'] = "Компания с таким названием уже зарегистрирована.";
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>