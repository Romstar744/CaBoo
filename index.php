<?php
session_start();

// Функция для проверки авторизации пользователя
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Функция для получения ссылки на авторизацию/регистрацию
function getAuthLink($isLoggedIn) {
    if ($isLoggedIn) {
         return '<a href="adm/profile.php" class="login-button">Личный кабинет</a>';
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
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="background"></div>
    <header>
        <div class="container">
            <a href="index.php" class="logo">CaBoo</a>
            <nav class="slider">
                <ul class="slider-nav">
                <li data-target="info" class="active">
                <a href="#info">Главная</a>
                </li>
                <li data-target="stat">
                <a href="#stat">Цифры</a>
                </li>
                <li data-target="chavo">
                <a href="#chavo">Вопросы</a>
                </li>
                <li data-target="manage">
                <a href="#manage">Возможности</a>
                </li>
                </ul>
            </nav>
            <nav>
                <?php echo getAuthLink($isLoggedIn); ?>
                <a href="adm/register.php" class="register-btn">Регистрация</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="info" id=info>
        <section class="hero">
            <div class="container hero-container">
                <div class="hero-text">
                    <h1>Найдите работу своей мечты</h1>
                    <p class="subtitle">А мы поможем вам на протяжении всего пути</p>
                        <div class="buttons-container">
                            <a href="adm/login.php" class="primary-button">Найти работу</a>
                            <a href="adm/login.php" class="secondary-button">Найти сотрудника</a>
                        </div>
                    </div>
                    <div class="boo">
                        <h1>Сa-bo-o</h1>
                    </div>
                    <div class="hero-image">
                        <img src="media/22.svg" alt="Изображение счастливого сотрудника">
                    </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2>Как начать?</h2>
                    <div class="steps">
                        <div class="step card-hidden" id="step1">
                            <h3>1. Зарегистрируйтесь</h3>
                                <p>Создайте свой профиль и начните свой путь к новой работе.</p>
                            </div>
                        <div class="step card-hidden" id="step2">
                <h3>2. Ищите вакансии</h3>
                <p>Используйте наши фильтры, чтобы найти подходящие предложения.</p>
            </div>
            <div class="step card-hidden" id="step3">
                <h3>3. Свяжитесь с работодателем</h3>
                <p>Отправьте отклик и начните диалог с работодателем.</p>
            </div>
        </div>
    </div>
</section>
</section>

<section class="numb" id="stat">
<section class="statistics">
    <div class="container">
        <div class="stats">
            <div class="stat card-hidden" id="stat1">
                <span class="number">70%</span>
                <p>Соискателей находят работу с нашей помощью</p>
            </div>
            <div class="stat card-hidden" id="stat2">
                <span class="number">1000+</span>
                <p>Успешных трудоустройств каждый месяц</p>
            </div>
            <div class="stat card-hidden" id="stat3">
                <span class="number">50000+</span>
                <p>Активных вакансий на сайте</p>
            </div>
        </div>
    </div>
</section>

        <section class="testimonials">
            <div class="container">
                    <div class="testimonial-slider">
                        <!-- Сюда будут добавлены отзывы из JavaScript -->
                    </div>
                <button class="slider-prev" aria-label="Предыдущий отзыв">&lt;</button>
                <button class="slider-next" aria-label="Следующий отзыв">&gt;</button>
            </div>
        </section>
</section>
</section>

<section class="chavo" id="chavo">
    <section class="faq">
    <div class="faq-container">
        <h1>Часто задаваемые вопросы</h1>
        <div class="faq-item">
            <div class="faq-question">
                <p>Как мне создать профиль кандидата?</p>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="faq-answer">
                <p>Для создания профиля кандидата, перейдите на страницу регистрации и заполните все необходимые поля. Убедитесь, что вы указали всю важную информацию о вашем опыте работы, навыках и образовании.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <p>Как я могу найти подходящие вакансии?</p>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="faq-answer">
                <p>Используйте фильтры поиска, чтобы найти вакансии, которые соответствуют вашим требованиям. Вы можете фильтровать по ключевым словам, местоположению, зарплате и другим параметрам.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <p>Могу ли я связаться с работодателем напрямую?</p>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="faq-answer">
                <p>В большинстве случаев, вы можете связаться с работодателем через форму обратной связи на странице вакансии. Однако, обратите внимание, что не всегда работодатели предоставляют такую возможность.</p>
            </div>
        </div>
        <div class="faq-item">
             <div class="faq-question">
                <p>Как опубликовать вакансию на платформе?</p>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="faq-answer">
                <p>Для публикации вакансии необходимо зарегистрироваться как работодатель. После регистрации вы сможете перейти в раздел публикации вакансий и заполнить необходимые данные.</p>
            </div>
        </div>
        <div class="faq-item">
             <div class="faq-question">
                <p>Сколько стоит публикация вакансии?</p>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="faq-answer">
                <p>Стоимость публикации вакансии зависит от выбранного тарифного плана. Ознакомиться с тарифными планами можно в разделе "Тарифы" в личном кабинете.</p>
            </div>
        </div>
        <div class="faq-item">
             <div class="faq-question">
                <p>Как я могу отслеживать отклики на мои вакансии?</p>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="faq-answer">
                <p>Все отклики на ваши вакансии можно отслеживать в личном кабинете в разделе "Отклики". Там вы сможете просматривать профили кандидатов и принимать решения.</p>
            </div>
        </div>
    </div>
    </div>
</section>
</section>

<section class="manage" id="manage">
<section class="rec-features">
        <div class="rec-container">
            <h2>Наши возможности</h2>
            <div class="rec-feature-container">
                <div class="rec-feature rec-card" data-feature="db">
                    <i class="fas fa-database"></i>
                    <h3>База данных кандидатов</h3>
                    <p>Доступ к базе данных резюме, отфильтрованных по сотням критериев.</p>
                </div>
                <div class="rec-feature rec-card" data-feature="sort">
                    <i class="fas fa-sort-amount-down"></i>
                    <h3>Интеллектуальная сортировка</h3>
                    <p>Автоматическая сортировка кандидатов по рейтингу, релевантности и опыту.</p>
                </div>
                 <div class="rec-feature rec-card" data-feature="resume">
                    <i class="fas fa-file-alt"></i>
                     <h3>Помощь в составлении резюме</h3>
                    <p>Инструменты для создания и анализа резюме, позволяющие выделить лучших.</p>
                </div>
               <div class="rec-feature rec-card" data-feature="test">
                  <i class="fas fa-vial"></i>
                   <h3>Инструменты для тестирования</h3>
                    <p>Проводите профессиональные тесты и оценки прямо на платформе.</p>
                </div>
                <div class="rec-feature rec-card" data-feature="interview">
                    <i class="fas fa-video"></i>
                    <h3>Интервью и коммуникации</h3>
                    <p>Встроенные инструменты для проведения онлайн-интервью и общения с кандидатами.</p>
                </div>
                 <div class="rec-feature rec-card" data-feature="analytics">
                   <i class="fas fa-chart-bar"></i>
                     <h3>Аналитика и отчетность</h3>
                    <p>Следите за процессом подбора, получайте отчеты и аналитику для оптимизации.</p>
                 </div>
            </div>
        </div>
    </section>
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
                <li><a class="active" href="license\license.php">Лицензионное соглашение</a></li>
                <li><a class="active" href="license\politic.php">Политика конфиденциальности</a></li>
                <li><a class="active" href="feedback\feedback.php">Обратная связь</a></li>  
                <li class="caboo">CaBoo 2025 ©</li>
           </ul>
        </div>

    </div>
    </footer>
    <script src="js/main.js"></script>
    <script src="js/chavo.js"></script>
    <script src="js/onas.js"></script>
    <script src="js/slider.js"></script>
</body>
</html>