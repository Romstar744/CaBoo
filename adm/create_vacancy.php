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
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
    <div class="container">
        <div class="profile-form">
            <h1>Новая вакансия</h1>
            <form action="vacancy_handler.php" method="POST">
                <div class="form-group">
                    <label for="title">Название вакансии:</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea id="description" name="description" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="requirements">Требования:</label>
                    <textarea id="requirements" name="requirements" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="salary">Зарплата:</label>
                    <input type="text" id="salary" name="salary">
                </div>

                <button type="submit" class="btn">Опубликовать</button>
            </form>
        </div>
    </div>
</body>
</html>