<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Подключение к базе данных (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION['role'];

// Функция для валидации данных
function validate($data) {
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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

// Получение данных из POST-запроса
$username = validate($_POST["username"]);
$email = validate($_POST["email"]);

// Валидация данных
$errors = [];

if (empty($username)) {
    $errors["username"] = "Пожалуйста, введите логин.";
}

if (empty($email)) {
    $errors["email"] = "Пожалуйста, введите email.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Неверный формат email.";
}

if (!empty($errors)) {
    // Если есть ошибки, возвращаем на страницу редактирования профиля
    $encoded_errors = urlencode(json_encode($errors));
    header("Location: edit_profile.php?error=" . $encoded_errors);
    exit();
}

// Получаем текущие данные пользователя из базы
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Проверяем, что данные пользователя получены
if (!$user) {
    die("Ошибка: Не удалось получить данные пользователя из базы данных.");
}

// Обработка данных соискателя или работодателя
$firstName = (isset($_POST["firstName"]) && $_POST["firstName"] !== '') ? validate($_POST["firstName"]) : (($user['first_name'] !== null && $user['first_name'] !== '') ? $user['first_name'] : null);
$lastName = (isset($_POST["lastName"]) && $_POST["lastName"] !== '') ? validate($_POST["lastName"]) : (($user['last_name'] !== null && $user['last_name'] !== '') ? $user['last_name'] : null);
$city = (isset($_POST["city"]) && $_POST["city"] !== '') ? validate($_POST["city"]) : (($user['city'] !== null && $user['city'] !== '') ? $user['city'] : null);
$about = (isset($_POST["about"]) && $_POST["about"] !== '') ? validate($_POST["about"]) : (($user['about'] !== null && $user['about'] !== '') ? $user['about'] : null);
$skills = (isset($_POST["skills"]) && $_POST["skills"] !== '') ? validate($_POST["skills"]) : (($user['skills'] !== null && $user['skills'] !== '') ? $user['skills'] : null);

$desiredSalary = (isset($_POST["desired_salary"]) && $_POST["desired_salary"] !== '') ? validate($_POST["desired_salary"]) : (($user['desired_salary'] !== null && $user['desired_salary'] !== '') ? $user['desired_salary'] : null);
if (empty($desiredSalary)) {
    $desiredSalary = null;
}

$birthdate = (isset($_POST["birthdate"]) && $_POST["birthdate"] !== '') ? validate($_POST["birthdate"]) : (($user['birthdate'] !== null && $user['birthdate'] !== '') ? $user['birthdate'] : null);
if (empty($birthdate)) {
    $birthdate = null;
}

$socialLinks = (isset($_POST["social_links"]) && $_POST["social_links"] !== '') ? validate($_POST["social_links"]) : (($user['social_links'] !== null && $user['social_links'] !== '') ? $user['social_links'] : null);
$gender = (isset($_POST["gender"]) && $_POST["gender"] !== '') ? validate($_POST["gender"]) : (($user['gender'] !== null && $user['gender'] !== '') ? $user['gender'] : null);
$educationInstitution = (isset($_POST["educationInstitution"]) && $_POST["educationInstitution"] !== '') ? validate($_POST["educationInstitution"]) : (($user['educationInstitution'] !== null && $user['educationInstitution'] !== '') ? $user['educationInstitution'] : null);
$educationDegree = (isset($_POST["educationDegree"]) && $_POST["educationDegree"] !== '') ? validate($_POST["educationDegree"]) : (($user['educationDegree'] !== null && $user['educationDegree'] !== '') ? $user['educationDegree'] : null);
$educationStart = (isset($_POST["educationStart"]) && $_POST["educationStart"] !== '') ? validate($_POST["educationStart"]) : (($user['educationStart'] !== null && $user['educationStart'] !== '') ? $user['educationStart'] : null);
$educationEnd = (isset($_POST["educationEnd"]) && $_POST["educationEnd"] !== '') ? validate($_POST["educationEnd"]) : (($user['educationEnd'] !== null && $user['educationEnd'] !== '') ? $user['educationEnd'] : null);
$educationDescription = (isset($_POST["educationDescription"]) && $_POST["educationDescription"] !== '') ? validate($_POST["educationDescription"]) : (($user['educationDescription'] !== null && $user['educationDescription'] !== '') ? $user['educationDescription'] : null);

$companyName = (isset($_POST["companyName"]) && $_POST["companyName"] !== '') ? validate($_POST["companyName"]) : (($user['company_name'] !== null && $user['company_name'] !== '') ? $user['company_name'] : null);
$companyDescription = (isset($_POST["companyDescription"]) && $_POST["companyDescription"] !== '') ? validate($_POST["companyDescription"]) : (($user['company_description'] !== null && $user['company_description'] !== '') ? $user['company_description'] : null);
$industry = (isset($_POST["industry"]) && $_POST["industry"] !== '') ? validate($_POST["industry"]) : (($user['industry'] !== null && $user['industry'] !== '') ? $user['industry'] : null);

// Обработка загрузки резюме
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $resumePath = uploadFile($_FILES['resume'], '../resumes/');
} else {
    $resumePath = validate($_POST["oldResume"]);
}

// Загрузка и обработка аватара и логотипа
$avatarPath = null;
$companyLogoPath = null;

// Загрузка аватара (если выбран файл)
if ($role === 'seeker' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $avatarPath = uploadFile($_FILES['avatar'], '../img/avatars/');
} else {
    $avatarPath = $user['avatar']; // Сохраняем старый путь
}

// Загрузка логотипа (если выбран файл)
if ($role === 'employer' && isset($_FILES['companyLogo']) && $_FILES['companyLogo']['error'] === UPLOAD_ERR_OK) {
    $companyLogoPath = uploadFile($_FILES['companyLogo'], '../img/company_logos/');
} else {
    $companyLogoPath = $user['company_logo']; // Сохраняем старый путь
}

// SQL-запрос для обновления данных пользователя
$sql = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, city = ?, company_name = ?, company_description = ?, avatar = ?, company_logo = ?, about = ?, skills = ?, desired_salary = ?, birthdate = ?, social_links = ?, resume = ?, gender = ?, industry = ?, educationInstitution = ?, educationDegree = ?, educationStart = ?, educationEnd = ?, educationDescription = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssssssssssssi", $username, $email, $firstName, $lastName, $city, $companyName, $companyDescription, $avatarPath, $companyLogoPath, $about, $skills, $desiredSalary, $birthdate, $socialLinks, $resumePath, $gender, $industry, $educationInstitution, $educationDegree, $educationStart, $educationEnd, $educationDescription, $user_id);

if ($stmt->execute()) {
    // Перенаправляем на страницу профиля с сообщением об успехе
    header("Location: profile.php?message=" . urlencode("Профиль успешно обновлен!"));
    exit();
} else {
    // Если не удалось обновить профиль, выводим ошибку
    echo "Ошибка при обновлении профиля: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>