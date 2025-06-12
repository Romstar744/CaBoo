<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Подключение к базе данных (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    // Сохраняем роль пользователя в сессии
    $_SESSION['role'] = $user['role'];
} else {
    die("Пользователь не найден!");
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать профиль</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<div class="container">
    <form class="profile-form" action="edit_profile_handler.php" method="post" enctype="multipart/form-data">
        <h1>Редактировать профиль</h1>
        <div class="profile-avatar">
            <?php if ($user['role'] == 'seeker' && !empty($user['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Аватар" class="avatar">
                <p>Текущий аватар загружен</p>
            <?php elseif ($user['role'] == 'employer' && !empty($user['company_logo'])): ?>
                <img src="<?php echo htmlspecialchars($user['company_logo']); ?>" alt="Логотип компании" class="avatar">
                 <p>Текущий логотип загружен</p>
            <?php else: ?>
                <img src="https://adm-hasyn.gosuslugi.ru/netcat_files/128/2202/scale_1200.jpg" alt="Аватар" class="avatar">
                <p>Аватар отсутствует</p>
            <?php endif; ?>
             <?php if ($user['role'] == 'seeker'): ?>
                    <div class="form-group">
                         <label for="avatar">Аватар:<i class="fas fa-image"></i></label>
                         <input type="file" id="avatar" name="avatar" accept="image/*">
                     </div>
            <?php elseif ($user['role'] == 'employer'): ?>
                 <div class="form-group">
                    <label for="companyLogo">Логотип компании:<i class="fas fa-image"></i></label>
                    <input type="file" id="companyLogo" name="companyLogo" accept="image/*">
                 </div>
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <section class="info-block">
                <h2>Основная информация</h2>
               <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-user"></i> Логин:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="error-message" id="usernameError"></div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                     <div class="error-message" id="emailError"></div>
                </div>
            </section>

            <?php if ($user['role'] == 'seeker'): ?>
            <section class="info-block">
                <h2>Информация о соискателе</h2>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-signature"></i> Имя:</label>
                        <input type="text" name="firstName" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-signature"></i> Фамилия:</label>
                        <input type="text" name="lastName" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-city"></i> Город:</label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-birthday-cake"></i> Дата рождения:</label>
                        <input type="date" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-venus-mars"></i> Пол:</label>
                        <select name="gender">
                            <option value="not_specified" <?php if ($user['gender'] == 'not_specified') echo 'selected'; ?>>Не указано</option>
                            <option value="male" <?php if ($user['gender'] == 'male') echo 'selected'; ?>>Мужской</option>
                            <option value="female" <?php if ($user['gender'] == 'female') echo 'selected'; ?>>Женский</option>
                            <option value="other" <?php if ($user['gender'] == 'other') echo 'selected'; ?>>Другой</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-money-bill-wave"></i> Желаемая зарплата:</label>
                        <input type="number" name="desired_salary" value="<?php echo htmlspecialchars($user['desired_salary'] ?? ''); ?> ₽">
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-tools"></i> Навыки:</label>
                        <textarea name="skills"><?php echo htmlspecialchars($user['skills'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                      <label><i class="fas fa-comment-dots"></i> О себе:</label>
                      <textarea  name="about"><?php echo htmlspecialchars($user['about'] ?? ''); ?></textarea>
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-share-alt"></i> Социальные сети:</label>
                        <textarea name="social_links"><?php echo htmlspecialchars($user['social_links'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="resume">Резюме:<i class="fas fa-file-pdf"></i></label>
                    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                    <input type="hidden" name="oldResume" value="<?php echo htmlspecialchars($user['resume'] ?? ''); ?>">
                     <?php if (!empty($user['resume'])): ?>
                        <p>Текущее резюме загружено</p>
                    <?php else: ?>
                        <p>Резюме отсутствует</p>
                    <?php endif; ?>
                </div>
                <!-- Информация об образовании -->
                <section class="info-block">
                    <h2>Информация об образовании</h2>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Учебное заведение:</label>
                            <input type="text" name="educationInstitution" value="<?php echo htmlspecialchars($user['educationInstitution'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Степень:</label>
                             <select id="educationDegree" name="educationDegree">
                                <option value="">Выберите степень</option>
                                <option value="спо" <?php if ($user['educationDegree'] == 'спо') echo 'selected'; ?>>Среднее профессиональное образование (СПО)</option>
                                <option value="бакалавр" <?php if ($user['educationDegree'] == 'бакалавр') echo 'selected'; ?>>Бакалавр</option>
                                <option value="специалист" <?php if ($user['educationDegree'] == 'специалист') echo 'selected'; ?>>Специалист</option>
                                <option value="магистр" <?php if ($user['educationDegree'] == 'магистр') echo 'selected'; ?>>Магистр</option>
                                <option value="кандидат_наук" <?php if ($user['educationDegree'] == 'кандидат_наук') echo 'selected'; ?>>Кандидат наук</option>
                                <option value="доктор_наук" <?php if ($user['educationDegree'] == 'доктор_наук') echo 'selected'; ?>>Доктор наук</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Начало обучения:</label>
                            <input type="month" name="educationStart" value="<?php echo htmlspecialchars($user['educationStart'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Окончание обучения:</label>
                            <input type="month" name="educationEnd" value="<?php echo htmlspecialchars($user['educationEnd'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Описание:</label>
                            <textarea name="educationDescription"><?php echo htmlspecialchars($user['educationDescription'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </section>
            </section>
            <?php endif; ?>

            <?php if ($user['role'] == 'employer'): ?>
            <section class="info-block">
                <h2>Информация о работодателе</h2>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-building"></i> Название компании:</label>
                        <input type="text" name="companyName" value="<?php echo htmlspecialchars($user['company_name'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-industry"></i> Индустрия:</label>
                        <input type="text" name="industry" value="<?php echo htmlspecialchars($user['industry'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-comment-dots"></i> Описание компании:</label>
                        <textarea name="companyDescription"><?php echo htmlspecialchars($user['company_description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <div style="text-align:center">
            <a href="#" onclick="document.querySelector('form').submit(); return false;" class="btn">Сохранить изменения</a>
            <a href="profile.php" class="btn">Отменить</a>
        </div>
    </form>
</div>
<script src="../js/profile.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    if (error) {
        const errorData = JSON.parse(decodeURIComponent(error));
        for (const key in errorData) {
            if (errorData.hasOwnProperty(key)) {
                const errorField = document.getElementById(key + 'Error');
                if (errorField) {
                    errorField.textContent = errorData[key];
                }
            }
        }
    }
});
</script>
</body>
</html>