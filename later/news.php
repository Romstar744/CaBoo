<?php
session_start();

// Функция для проверки авторизации пользователя
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Функция для получения ссылки на авторизацию/регистрацию
function getAuthLink($isLoggedIn) {
    if ($isLoggedIn) {
         return '<a href="adm/login.php" class="login-button">Личный кабинет</a>';
     } else {
         return '<a href="adm/login.php" class="login-button">Вход</a>';
     }
}
$isLoggedIn = isUserLoggedIn();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaBoo - Прокачай свой навык поиска работы</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="news.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="background"></div>
    <section id="news">
<div class="news-widget reveal">
<h2>Новости</h2>
<div id="news-container">
   <!-- Новости будут вставлены сюда -->
</div>
</div>
</section>
<footer>
    <div class="container">
        <div class="social">
           <a href="#"><i class="fab fa-vk"></i></a>
           <a href="#"><i class="fab fa-github"></i></a>
           <a href="#"><i class="fab fa-telegram-plane"></i></a>
        </div>
        <div class="footer-links">
           <ul>
               <li><a class="active" href="https://journal.tinkoff.ru/flows/career/">Статьи</a></li>
               <li><a class="active" href="license.php">Лицензионное соглашение</a></li>
               <li><a class="active" href="politic.php">Политика конфиденциальности</a></li>  
               <li class="caboo">CaBoo 2025 ©</li>
           </ul>
        </div>

    </div>
    </footer>
    <script src="../js/main.js"></script>
    <script src="../js/chavo.js"></script>
    <script src="../js/onas.js"></script>
    <script src="../js/slider.js"></script>
    <script src="news.js"></script>
</body>
</html>