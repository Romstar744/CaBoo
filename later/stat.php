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
    <link rel="stylesheet" href="stat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="background"></div>
    <section class="info-blocks-container">
<div class="info-block">
  <h3><i class="fas fa-lightbulb"></i> Идея Проекта</h3>
    <p>Наша основная идея заключается в создании простого и эффективного продукта.</p>
</div>
<div class="info-block">
    <h3><i class="fas fa-chart-bar"></i> Статистика Проекта</h3>
    <p>
        <span>Количество пользователей:</span> <span class="stat-value">1200+</span><br>
        <span>Завершенных задач:</span> <span class="stat-value">150+</span><br>
        <span>Количество коммитов:</span> <span class="stat-value">750+</span>
    </p>
</div>
 <div class="info-block">
   <h3><i class="fas fa-handshake"></i> Награды</h3>
    <p>
      <span>Победитель хакатона</span> <span class="stat-value">2023</span><br>
      <span>Премия за инновации</span> <span class="stat-value">2022</span>
  </p>
</div>
<div class="info-block">
   <h3><i class="fas fa-bullseye"></i> Цели</h3>
    <p>
      <span>Цель на 2024:</span> <span class="stat-value">Достигнуть 10.000 пользователей</span>
   </p>
</div>
<div class="info-block">
   <h3><i class="fas fa-users"></i> Наши клиенты</h3>
     <p>
      <span>Мы помогаем компаниям:</span> <span class="stat-value">[Названия]</span>
   </p>
</div>
<div class="info-block">
   <h3><i class="fas fa-comments"></i> Отзывы</h3>
    <p>
       <span>Средняя оценка:</span> <span class="stat-value">4.8 из 5</span>
   </p>
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
               <li><a class="active" href="../license/license.php">Лицензионное соглашение</a></li>
               <li><a class="active" href="../license/politic.php">Политика конфиденциальности</a></li>  
               <li class="caboo"><a href="../index.php">CaBoo 2025 ©</a></li>
           </ul>
        </div>

    </div>
    </footer>
    <script src="../js/main.js"></script>
    <script src="../js/chavo.js"></script>
    <script src="../js/onas.js"></script>
    <script src="../js/slider.js"></script>
</body>
</html>