<?php
session_start();
if ($_SESSION['role'] != 'employer') {
    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Создание вакансии</title>
    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Подключаем Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Подключаем файл CSS -->
    <link rel="stylesheet" href="../css/create_vacancy.css">
</head>
<body>
    <h1><i class="fas fa-plus-circle"></i> Новая вакансия</h1>  <!-- Заголовок вне .profile-form -->
    <div class="container">
        <div class="profile-form">

            <form action="vacancy_handler.php" method="POST">
                <div class="form-group">
                    <label for="title"><i class="fas fa-briefcase"></i> Название вакансии:</label>
                    <input type="text" id="title" name="title" placeholder="Например, Менеджер по продажам" required>
                </div>

                <div class="form-group">
                    <label for="description"><i class="fas fa-file-alt"></i> Описание:</label>
                    <textarea id="description" name="description" rows="5" placeholder="Опишите обязанности и требования к кандидату" required></textarea>
                </div>

                <div class="form-group">
                    <label for="requirements"><i class="fas fa-list-ul"></i> Требования:</label>
                    <textarea id="requirements" name="requirements" rows="5" placeholder="Перечислите ключевые навыки и опыт" required></textarea>
                </div>

                <div class="form-group">
                    <label for="salary"><i class="fas fa-dollar-sign"></i> Зарплата:</label>
                    <input type="text" id="salary" name="salary" placeholder="Укажите предлагаемую зарплату">
                </div>

                <button type="submit"><i class="fas fa-check"></i> Опубликовать</button>
                <a href="profile.php" class="back-link"><i class="fas fa-arrow-left"></i> Назад в личный кабинет</a>
            </form>
        </div>
    </div>
</body>
</html>