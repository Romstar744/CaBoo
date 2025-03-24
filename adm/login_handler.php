<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация данных
    $username = trim($_POST["username"]); // Убираем пробелы
    $password = $_POST["password"];

    // Экранирование для предотвращения SQL-инъекций (используем prepared statements)

    // Проверка логина или email
    $stmt = $conn->prepare("SELECT id, username, password, is_email_verified FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
            // Проверка пароля
            if (password_verify($password, $user["password"])) {
                if ($user["is_email_verified"] == 1) {
                  $_SESSION["user_id"] = $user["id"];
                    header("Location: profile.php");
                    exit();
                } else {
                    // Email не подтвержден - используем сессию вместо cookie
                    $_SESSION['error'] = "Пожалуйста, подтвердите свой email.";
                    header("Location: login.php");
                    exit();
                  }
            } else {
                // Неверный пароль - используем сессию вместо URL
                $_SESSION['error'] = "Неверный данные для входа.";
                header("Location: login.php");
                 exit();
            }
    } else {
        // Пользователь не найден - используем сессию вместо URL
        $_SESSION['error'] = "Пользователь не найден.";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>