
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

// Обработка данных соискателя или работодателя
$firstName = $lastName = $city = $companyName = $companyDescription = $about = $skills = $desiredSalary = $birthdate = $socialLinks = $resume = $gender = $industry = $educationInstitution = $educationDegree = $educationStart = $educationEnd = $educationDescription = null;

if ($role === 'seeker') {
    $firstName = validate($_POST["firstName"]);
    $lastName = validate($_POST["lastName"]);
    $city = validate($_POST["city"]);
    $about = validate($_POST["about"]);
    $skills = validate($_POST["skills"]);
    $desiredSalary = validate($_POST["desired_salary"]);
    $birthdate = validate($_POST["birthdate"]);
    $socialLinks = validate($_POST["social_links"]);
    $gender = validate($_POST["gender"]);
    $educationInstitution = validate($_POST["educationInstitution"]);
    $educationDegree = validate($_POST["educationDegree"]);
    $educationStart = validate($_POST["educationStart"]);
    $educationEnd = validate($_POST["educationEnd"]);
    $educationDescription = validate($_POST["educationDescription"]);

    // Обработка загрузки резюме
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resumePath = uploadFile($_FILES['resume'], '../resumes/');
    }

} elseif ($role === 'employer') {
    $companyName = validate($_POST["companyName"]);
    $companyDescription = validate($_POST["companyDescription"]);
    $industry = validate($_POST["industry"]);
}

// Загрузка и обработка аватара и логотипа
$avatarPath = null;
$companyLogoPath = null;

// Загрузка аватара (если выбран файл)
if ($role === 'seeker' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $avatarPath = uploadFile($_FILES['avatar'], '../img/avatars/');
}

// Загрузка логотипа (если выбран файл)
if ($role === 'employer' && isset($_FILES['companyLogo']) && $_FILES['companyLogo']['error'] === UPLOAD_ERR_OK) {
    $companyLogoPath = uploadFile($_FILES['companyLogo'], '../img/company_logos/');
}

// Функция для загрузки файла
function uploadFile($file, $destination) {
    $uploadDir = $destination;
    $fileName = basename($file["name"]);
    $filePath = $uploadDir . uniqid() . "_" . $fileName;
    $imageFileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // Проверка типа файла
    $allowedExtensions = array("jpg", "jpeg", "png", "gif", "pdf", "doc", "docx");
    if (!in_array($imageFileType, $allowedExtensions)) {
        return null; // Неверный тип файла
    }

    // Перемещение файла
    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        return $filePath;
    } else {
        return null; // Ошибка при загрузке файла
    }
}

// Валидация данных
$errors = [];

if (strlen($password) < 8) {
    $errors["password"] = "Пароль должен быть не менее 8 символов.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Неверный формат email.";
}

// Проверка, существует ли пользователь с таким логином или email
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row && $row['username'] === $username) {
        $errors["username"] = "Логин уже занят.";
    } elseif ($row && $row['email'] === $email) {
        $errors["email"] = "Email уже зарегистрирован.";
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
$sql = "INSERT INTO users (username, email, password, email_verification_token, is_email_verified, role, first_name, last_name, city, company_name, company_description, avatar, company_logo, about, skills, desired_salary, birthdate, social_links, resume, gender, industry, educationInstitution, educationDegree, educationStart, educationEnd, educationDescription) VALUES (?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssssssssssssssss", $username, $email, $hashedPassword, $token, $role, $firstName, $lastName, $city, $companyName, $companyDescription, $avatarPath, $companyLogoPath, $about, $skills, $desiredSalary, $birthdate, $socialLinks, $resumePath, $gender, $industry, $educationInstitution, $educationDegree, $educationStart, $educationEnd, $educationDescription);

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
?>