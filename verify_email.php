<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение email</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<div class="background"></div>
<div class="container">
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

if (isset($_GET['token'])) {
    $token = $_GET['token'];

        $stmt = $conn->prepare("SELECT id, is_email_verified FROM users WHERE email_verification_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $user = $result->fetch_assoc();
            if($user["is_email_verified"] == 0){
                $stmtUpdate = $conn->prepare("UPDATE users SET is_email_verified = 1, email_verification_token = null  WHERE id = ?");
                $stmtUpdate->bind_param("i", $user["id"]);
                $stmtUpdate->execute();
                if($stmtUpdate->affected_rows > 0){
                     echo '<div class="success-message">Почта успешно подтверждена! Теперь вы можете <a href="adm/login.php">войти</a>.</div>';
                }else{
                    echo '<div class="error-message">Ошибка при подтверждении почты!</div>';
                }
            } else{
                echo '<div class="success-message">Почта уже подтверждена! Вы можете <a href="login.php">войти</a>.</div>';
            }
        } else{
            echo '<div class="error-message">Неверная ссылка подтверждения!</div>';
        }

    $stmt->close();
    $conn->close();

} else {
    echo '<div class="error-message">Неверная ссылка подтверждения!</div>';
}
?>
</div>
</body>
</html>