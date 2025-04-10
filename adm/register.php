<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<div class="container">
    <form class="auth-form register-form" action="registration_handler.php" method="post" enctype="multipart/form-data" id="registerForm">
        <h1>Регистрация</h1>

        <fieldset>
            <legend>Основная информация</legend>
            <div class="form-group">
                <label for="username">Логин:<i class="fas fa-user"></i></label>
                <input type="text" id="username" name="username" required>
                <div class="error-message" id="usernameError"></div>
            </div>
            <div class="form-group">
                <label for="email">Email:<i class="fas fa-envelope"></i></label>
                <input type="email" id="email" name="email" id="email" required>
                <div class="error-message" id="emailError"></div>
            </div>
            <div class="form-group">
                <label for="password">Пароль:<i class="fas fa-lock"></i></label>
                <input type="password" id="password" name="password" required>
                <div class="error-message" id="passwordError"></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Выберите роль</legend>
             <div class="form-group role-selector">
                <label>Выберите роль:</label>
                <div class="role-cards">
                    <input type="radio" name="role" value="seeker" id="seekerRole" required>
                    <label class="role-card" for="seekerRole">
                        <i class="fa fa-user"></i>
                        <h2>Соискатель</h2>
                        <p>Ищу работу и готов к новым вызовам.</p>
                    </label>

                    <input type="radio" name="role" value="employer" id="employerRole" required>
                    <label class="role-card" for="employerRole">
                        <i class="fa fa-briefcase"></i>
                        <h2>Работодатель</h2>
                        <p>Ищу таланты и готов предоставить работу.</p>
                    </label>
                </div>
                <div class="error-message" id="roleError"></div>
            </div>
        </fieldset>

        <div class="form-group button-group">
            <button type="submit" class="btn">Зарегистрироваться</button>
            <a href="../index.php" class="btn btn-secondary">На главную</a>
        </div>
        <a href="login.php" class="login-link">Уже есть аккаунт?</a>
    </form>
</div>
<script src="../js/register.js"></script>
</body>
</html>