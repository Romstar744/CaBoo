<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../css/register.css">
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

        <fieldset class="seeker-fields hidden">
            <legend>Информация о соискателе</legend>
            <div class="form-group">
                <label for="firstName">Имя:<i class="fas fa-signature"></i></label>
                <input type="text" id="firstName" name="firstName">
                <div class="error-message" id="firstNameError"></div>
            </div>
            <div class="form-group">
                <label for="lastName">Фамилия:<i class="fas fa-signature"></i></label>
                <input type="text" id="lastName" name="lastName">
                <div class="error-message" id="lastNameError"></div>
            </div>
            <div class="form-group">
                <label for="birthdate">Дата рождения:<i class="fas fa-calendar-alt"></i></label>
                <input type="date" id="birthdate" name="birthdate">
                <div class="error-message" id="birthdateError"></div>
            </div>
            <div class="form-group">
                <label for="city">Город:<i class="fas fa-city"></i></label>
                <input type="text" id="city" name="city">
                <div class="error-message" id="cityError"></div>
            </div>
            <div class="form-group">
                <label for="gender">Пол:<i class="fas fa-user"></i></label>
                <select id="gender" name="gender">
                    <option value="not_specified">Не указано</option>
                    <option value="male">Мужской</option>
                    <option value="female">Женский</option>
                    <option value="other">Другой</option>
                </select>
                <div class="error-message" id="genderError"></div>
            </div>
            <div class="form-group">
                <label for="desired_salary">Желаемая зарплата:<i class="fas fa-money-bill-wave"></i></label>
                <input type="number" id="desired_salary" name="desired_salary" min="0" value="1">
                <div class="error-message" id="desired_salaryError"></div>
            </div>
            <div class="form-group">
                <label for="skills">Навыки:<i class="fas fa-tools"></i></label>
                <textarea id="skills" name="skills" placeholder="Перечислите ваши навыки через запятую"></textarea>
                <div class="error-message" id="skillsError"></div>
            </div>
            <div class="form-group">
                <label for="social_links">Профили в соц. сетях (через пробел):<i class="fab fa-linkedin"></i></label>
                <textarea id="social_links" name="social_links" placeholder='Укажите ссылки через пробел'></textarea>
                <small>Укажите ссылки через пробел</small>
                <div class="error-message" id="social_linksError"></div>
            </div>
            <div class="form-group">
                <label for="about">О себе:<i class="fas fa-comment-dots"></i></label>
                <textarea id="about" name="about"></textarea>
                <div class="error-message" id="aboutError"></div>
            </div>

            <fieldset>
                <legend>Информация об образовании</legend>
                <div class="form-group">
                    <label for="educationInstitution">Учебное заведение:<i class="fas fa-graduation-cap"></i></label>
                    <input type="text" id="educationInstitution" name="educationInstitution">
                    <div class="error-message" id="educationInstitutionError"></div>
                </div>
                <div class="form-group">
                    <label for="educationDegree">Степень:<i class="fas fa-graduation-cap"></i></label>
                    <select id="educationDegree" name="educationDegree">
                        <option value="">Выберите степень</option>
                        <option value="спо">Среднее профессиональное образование (СПО)</option>
                        <option value="бакалавр">Бакалавр</option>
                        <option value="специалист">Специалист</option>
                        <option value="магистр">Магистр</option>
                        <option value="кандидат_наук">Кандидат наук</option>
                        <option value="доктор_наук">Доктор наук</option>
                    </select>
                    <div class="error-message" id="educationDegreeError"></div>
                </div>
                <div class="form-group">
                    <label for="educationStart">Начало обучения:<i class="fas fa-graduation-cap"></i></label>
                    <input type="month" id="educationStart" name="educationStart" class="styled-month-input">
                    <div class="error-message" id="educationStartError"></div>
                </div>
                <div class="form-group">
                    <label for="educationEnd">Окончание обучения :<i class="fas fa-graduation-cap"></i></label>
                    <input type="month" id="educationEnd" name="educationEnd" class="styled-month-input">
                    <div class="error-message" id="educationEndError"></div>
                </div>
                <div class="form-group">
                    <label for="educationDescription">Описание:<i class="fas fa-graduation-cap"></i></label>
                    <textarea  id="educationDescription" name="educationDescription"></textarea>
                    <div class="error-message" id="educationDescriptionError"></div>
                </div>
            </fieldset>

            <div class="form-group">
                <label for="resume">Резюме:<i class="fas fa-file-pdf"></i></label>
                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                <div class="error-message" id="resumeError"></div>
            </div>
            <div class="form-group">
                <label for="avatar">Аватар:<i class="fas fa-image"></i></label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
                <div class="preview" id="avatarPreview">
                    <img src="" alt="Предпросмотр аватара" style="max-width: 100px; max-height: 100px; display: none;">
                </div>
                <div class="error-message" id="avatarError"></div>
            </div>
        </fieldset>

        <fieldset class="employer-fields hidden">
            <legend>Информация о работодателе</legend>
            <div class="form-group">
                <label for="companyName">Название компании:<i class="fas fa-building"></i></label>
                <input type="text" id="companyName" name="companyName">
                <div class="error-message" id="companyNameError"></div>
            </div>
            <div class="form-group">
                    <label for="industry">Индустрия:<i class="fas fa-industry"></i></label>
                    <input type="text" id="industry" name="industry">
                    <div class="error-message" id="industryError"></div>
                </div>
            <div class="form-group">
                <label for="companyDescription">Описание компании:<i class="fas fa-comment-dots"></i></label>
                <textarea id="companyDescription" name="companyDescription"></textarea>
                <div class="error-message" id="companyDescriptionError"></div>
            </div>
            <div class="form-group">
                <label for="companyLogo">Логотип компании:<i class="fas fa-image"></i></label>
                <input type="file" id="companyLogo" name="companyLogo" accept="image/*">
                <div class="preview" id="companyLogoPreview">
                    <img src="" alt="Предпросмотр логотипа" style="max-width: 100px; max-height: 100px; display: none;">
                </div>
                <div class="error-message" id="companyLogoError"></div>
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