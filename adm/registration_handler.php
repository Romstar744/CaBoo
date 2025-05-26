<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$log_file = 'registration.log';
$log_message = date('Y-m-d H:i:s') . " - Registration attempt:\n" . print_r($_POST, true) . "\nErrors: " . print_r($errors, true) . "\n";
file_put_contents($log_file, $log_message, FILE_APPEND);

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
if (!preg_match('/[A-Z]/', $password)) {
    $errors["password"] = "Пароль должен содержать хотя бы одну заглавную букву.";
}
if (!preg_match('/[^a-zA-Z0-9\s]/', $password)) {
    $errors["password"] = "Пароль должен содержать хотя бы один спец. символ.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Неверный формат email.";
}
if (!preg_match('/@gmail\.com$/', $email)) {
    $errors["email"] = "Разрешена только Gmail почта.";
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

// Получаем и валидируем поля компании, если роль - работодатель
$company_name = null;
$industry = null;
if ($role == 'employer') {
    $company_name = validate($_POST["company_name"]);
    $industry = validate($_POST["industry"]);

    if (empty($company_name)) {
        $errors["company_name"] = "Название компании обязательно для заполнения.";
    }

    if (empty($industry)) {
        $errors["industry"] = "Индустрия обязательна для заполнения.";
    }
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
// Добавляем поля company_name и industry в запрос
$sql = "INSERT INTO users (username, email, password, email_verification_token, is_email_verified, role, company_name, industry) VALUES (?, ?, ?, ?, 0, ?, ?, ?)";
$stmt = $conn->prepare($sql);
// Привязываем параметры, включая company_name и industry
$stmt->bind_param("sssssss", $username, $email, $hashedPassword, $token, $role, $company_name, $industry);

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