<?php
session_start();

// Функция для проверки авторизации пользователя
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Функция для получения ссылки на авторизацию/регистрацию
function getAuthLink($isLoggedIn) {
    if ($isLoggedIn) {
         return '<a href="../adm/profile.php" class="login-button">Личный кабинет</a>';
     } else {
         return '<a href="../adm/login.php" class="login-button">Вход</a>';
     }
}

$isLoggedIn = isUserLoggedIn();

// Параметры для отправки письма (замените на свои)
$admin_email = "caboorus@gmail.com"; // Ваш email
$subject = "Обратная связь с сайта CaBoo";

$message_sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    // Валидация данных (простая)
    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {

        // Формируем сообщение
        $body = "Имя: $name\nEmail: $email\nСообщение:\n$message";

        // Отправляем письмо
        if (mail($admin_email, $subject, $body)) {
            $message_sent = true;
        } else {
            $error_message = "Ошибка отправки сообщения. Пожалуйста, попробуйте позже.";
        }
    } else {
        $error_message = "Пожалуйста, заполните все поля корректно.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь - CaBoo</title>
    <link rel="stylesheet" href="../css/index.css"> <!-- Подключаем общие стили -->
    <link rel="stylesheet" href="../css/feedback.css"> <!-- Подключаем стили для feedback -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="background"></div>
    <header>
        <div class="container">
            <a href="../index.php" class="logo">CaBoo</a>
            <nav class="slider">
                <ul class="slider-nav">
                <li data-target="info" class="active">
                <a href="../index.php#info">Главная</a>
                </li>
                <li data-target="stat">
                <a href="../index.php#stat">Цифры</a>
                </li>
                <li data-target="chavo">
                <a href="../index.php#chavo">Вопросы</a>
                </li>
                <li data-target="manage">
                <a href="../index.php#manage">Возможности</a>
                </li>
                </ul>
            </nav>
            <nav>
                <?php echo getAuthLink($isLoggedIn); ?>
                <a href="../adm/register.php" class="register-btn">Регистрация</a>
            </nav>
        </div>
    </header>

    <main class="feedback-page">
        <section class="feedback-form">
            <div class="container">
                <h1>Обратная связь</h1>
                <p>Пожалуйста, заполните форму ниже, чтобы связаться с нами.</p>

                <?php if ($message_sent): ?>
                    <div class="success-message">
                        Спасибо! Ваше сообщение отправлено.
                    </div>
                <?php elseif (isset($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="name">Ваше имя:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Ваш email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Сообщение:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Отправить сообщение</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="social">
                <a href="https://vk.com/lil_spal"><i class="fab fa-vk"></i></a>
                <a href="https://github.com/Romstar744"><i class="fab fa-github"></i></a>
                <a href="https://t.me/LIL_SPAL"><i class="fab fa-telegram-plane"></i></a>
            </div>
            <div class="footer-links">
                <ul>
                    <li><a class="active" href="https://journal.tinkoff.ru/flows/career/">Статьи</a></li>
                    <li><a class="active" href="../license/license.php">Лицензионное соглашение</a></li>
                    <li><a class="active" href="../license/politic.php">Политика конфиденциальности</a></li>
                    <li><a class="active" href="feedback.php">Обратная связь</a></li>
                    <li class="caboo">CaBoo 2025 ©</li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>