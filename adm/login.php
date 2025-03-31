<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <form class="auth-form login-form" action="login_handler.php" method="post">
        <h1>Авторизация</h1>
        <?php
        if (isset($_GET['success'])) {
            $successMessage = htmlspecialchars($_GET['success']);
            echo '<div class="info-message">' . $successMessage . '</div>';
        }
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            echo '<div class="info-message">' . $message . '</div>';
        }
        ?>
        <?php
        session_start(); // Запускаем сессию в login.php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']); // Удаляем переменную из сессии, чтобы сообщение не появлялось снова
        }
        ?>
        <div class="form-group">
            <label for="username">Логин:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Войти</button>
            <a href="../index.php" class="btn btn-secondary">На главную</a>
        </div>
        <a href="register.php" class="login-link">Нет аккаунта?</a>
    </form>
</div>
</body>
</html>