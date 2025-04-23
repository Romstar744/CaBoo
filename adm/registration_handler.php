<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Подключение к базе данных (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Функция для валидации данных
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Функция для генерации уникального токена
function generate_token() {
    return bin2hex(random_bytes(32));
}

// Получение данных из POST-запроса
$username = validate($_POST["username"]);
$email = validate($_POST["email"]);
$password = $_POST["password"]; // Не валидируем пароль здесь, сделаем это позже
$role = $_POST["role"];

// Валидация данных
$errors = [];

if (strlen($password) < 8) {
    $errors["password"] = "Пароль должен быть не менее 8 символов.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Неверный формат email.";
}

// Проверка, существует ли пользователь с таким логином
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$username_exists = $stmt->num_rows > 0;
$stmt->close();

// Проверка, существует ли пользователь с таким email
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$email_exists = $stmt->num_rows > 0;
$stmt->close();

if ($username_exists) {
    $errors["username"] = "Логин уже занят.";
}

if ($email_exists) {
    $errors["email"] = "Email уже зарегистрирован.";
}

// Если есть ошибки, возвращаем на страницу регистрации
if (!empty($errors)) {
    $encoded_errors = urlencode(json_encode($errors));
    header("Location: register.php?error=" . $encoded_errors);
    exit();
}

// Хеширование пароля
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Генерация токена
$token = generate_token();

// SQL-запрос для добавления нового пользователя
$sql = "INSERT INTO users (username, email, password, email_verification_token, is_email_verified, role) VALUES (?, ?, ?, ?, 0, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $username, $email, $hashedPassword, $token, $role);

if ($stmt->execute()) {
    $userId = $conn->insert_id; // Получаем ID нового пользователя

    // Отправка письма с подтверждением (замените этот блок реальной отправкой)
    $verificationLink = "https://starostin.xn--80ahdri7a.site/CaBoo/verify_email.php?token=" . $token;
    $message = "Здравствуйте, " . $username . "! Для подтверждения вашей почты, перейдите по ссылке: " . $verificationLink;

    // Пример с mail()
    $from = "CaBoo@yandex.ru"; // Замените на свой email
    $to = $email; // Кому отправляем
    $subject = "Подтверждение email"; // Тема письма

    // Формируем заголовки
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Отправляем письмо
    $mailSuccess = mail($to,  "=?UTF-8?B?" . base64_encode($subject) . "?=", $message, $headers);

    if ($mailSuccess) {
        // Перенаправляем на страницу логина с сообщением об успехе
        header("Location: login.php?message=" . urlencode("На вашу почту отправлено письмо для подтверждения."));
        exit();
    } else {
        // Если не удалось отправить письмо, выводим ошибку
        $errors['message'] = "Ошибка при отправке email.";
        header("Location: register.php?error=" . urlencode(json_encode($errors)));
        exit();
    }
} else {
    $errors['message'] = "Ошибка при регистрации: " . $stmt->error;
    header("Location: register.php?error=" . urlencode(json_encode($errors)));
    exit();
}
$stmt->close();
$conn->close();
?>