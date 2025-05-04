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

// Получаем список соискателей, прошедших тестирование (только для работодателей)
$jobseekers = [];
if ($user['role'] == 'employer') {
    $sql = "SELECT id, username, first_name, last_name, proforientation_test_results, proforientation_recommendations FROM users WHERE role = 'seeker' AND proforientation_test_results IS NOT NULL";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobseekers[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .jobseeker-list {
            margin-top: 20px;
        }

        .jobseeker-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="background"></div>
<div class="container">
    <div class="profile-form">
        <h1>Личный кабинет</h1>
        <div class="profile-avatar">
            <?php if ($user['role'] == 'seeker' && !empty($user['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Аватар" class="avatar">
            <?php elseif ($user['role'] == 'employer' && !empty($user['company_logo'])): ?>
                <img src="<?php echo htmlspecialchars($user['company_logo']); ?>" alt="Логотип компании" class="avatar">
            <?php else: ?>
                <img src="https://adm-hasyn.gosuslugi.ru/netcat_files/128/2202/scale_1200.jpg" alt="Аватар" class="avatar">
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <section class="info-block">
                <h2>Основная информация</h2>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-user"></i> Логин:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-check-circle"></i> Email подтвержден:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['is_email_verified'] == 1 ? 'Да' : 'Нет'); ?>" readonly class="<?php echo $user['is_email_verified'] == 1 ? '' : 'warning'; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-briefcase"></i> Роль:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['role'] == 'seeker' ? 'Соискатель' : 'Работодатель'); ?>" readonly>
                    </div>
                </div>
                 <!-- Предпросмотр резюме -->
                <section class="info-block">
                <?php if ($user['role'] == 'seeker'): ?>
                <h2>Предпросмотр резюме</h2>
                <div class="resume-preview">
                    <?php
                    $relativePath = $user['resume']; // Относительный путь из базы данных

                    // **ВАЖНО: Замените это на реальный способ получения базового URL вашего сайта:**
                    $baseUrl = 'https://starostin.xn--80ahdri7a.site/CaBoo/';

                    // Преобразование относительного пути в полный URL:
                    // Проверка, чтобы избежать двойных слешей
                    if (substr($baseUrl, -1) == '/' && substr($relativePath, 0, 2) == '../') {
                        $resumePath = $baseUrl . substr($relativePath, 3);
                    } elseif (substr($baseUrl, -1) == '/' && substr($relativePath, 0, 1) == '/') {
                        $resumePath = $baseUrl . substr($relativePath, 1);
                    } elseif (substr($baseUrl, -1) != '/' && substr($relativePath, 0, 1) != '/') {
                        $resumePath = $baseUrl . '/' . $relativePath;
                    } else {
                        $resumePath = $baseUrl . $relativePath;
                    }

                    $resumePath = str_replace('../', '', $resumePath);

                    if ($resumePath) {
                        // Определяем расширение файла (очень упрощенно)
                        $fileExtension = strtolower(pathinfo($resumePath, PATHINFO_EXTENSION));

                        // Отображаем резюме в зависимости от типа файла
                        if ($fileExtension == 'pdf') {
                            echo '<embed src="' . htmlspecialchars($resumePath) . '" type="application/pdf" width="100%" height="600">';
                        } elseif ($fileExtension == 'docx' || $fileExtension == 'doc') {
                            // Google Docs Viewer
                            echo '<iframe src="https://docs.google.com/viewer?url=' . urlencode($resumePath) . '&embedded=true" width="100%" height="600"></iframe>';
                        } else {
                            echo '<p>Неподдерживаемый формат файла.</p>';
                        }
                    } else {
                        echo '<p>Резюме не загружено.</p>';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </section>
            </section>

            <?php if ($user['role'] == 'seeker'): ?>
            <section class="info-block">
                <h2>Информация о соискателе</h2>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-signature"></i> Имя:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['first_name'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-signature"></i> Фамилия:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['last_name'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-city"></i> Город:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['city'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-birthday-cake"></i> Дата рождения:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['birthdate'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-venus-mars"></i> Пол:</label>
                        <input type="text" value="<?php
                        switch ($user['gender']) {
                            case 'male': echo 'Мужской'; break;
                            case 'female': echo 'Женский'; break;
                            case 'other': echo 'Другой'; break;
                            default: echo 'Не указано'; break;
                        }
                        ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-money-bill-wave"></i> Желаемая зарплата:</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['desired_salary'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                 <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-tools"></i> Навыки:</label>
                            <textarea readonly><?php echo htmlspecialchars($user['skills'] ?? 'Не указано'); ?></textarea>
                        </div>
                    </div>
                 <div class="form-group">
                        <div class="form-row">
                          <label><i class="fas fa-comment-dots"></i> О себе:</label>
                          <textarea  readonly><?php echo htmlspecialchars($user['about'] ?? 'Не указано'); ?></textarea>
                        </div>
                    </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-share-alt"></i> Социальные сети:</label>
                        <textarea readonly><?php echo htmlspecialchars($user['social_links'] ?? 'Не указано'); ?></textarea>
                    </div>
                </div>
                <!-- Информация об образовании -->
                <section class="info-block">
                    <h2>Информация об образовании</h2>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Учебное заведение:</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['educationInstitution'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Степень:</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['educationDegree'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Начало обучения:</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['educationStart'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Окончание обучения:</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['educationEnd'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Описание:</label>
                            <textarea readonly><?php echo htmlspecialchars($user['educationDescription'] ?? 'Не указано'); ?></textarea>
                        </div>
                    </div>
                </section>
            </section>
            <?php endif; ?>

            <?php if ($user['role'] == 'seeker'): ?>
            <?php if (empty($user['proforientation_test_results'])): ?>
                <div style="text-align:center; margin-top: 20px;">
                    <a href="../testing/test.php" class="btn">Пройти тестирование</a>
                </div>
            <?php else: ?>
                <section class="info-block">
                    <h2>Результаты профориентационного тестирования</h2>
                    <?php
                    // Отображаем результаты тестирования
                    $testResults = json_decode($user['proforientation_test_results'], true);
                    $recommendations = json_decode($user['proforientation_recommendations'], true);

                    if ($testResults && is_array($testResults)) {
                        echo "<p>Результаты:</p>";
                        echo "<ul>";
                        foreach ($testResults as $key => $value) {
                            echo "<li>" . htmlspecialchars($key) . ": " . htmlspecialchars($value) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Результаты не найдены.</p>";
                    }

                    if ($recommendations && is_array($recommendations)) {
                        echo "<p>Рекомендации:</p>";
                        echo "<ul>";
                        foreach ($recommendations as $recommendation) {
                            echo "<li>" . htmlspecialchars($recommendation) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Рекомендации не найдены.</p>";
                    }
                    ?>
                </section>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($user['role'] == 'employer'): ?>
            <section class="info-block jobseeker-list">
                <h2>Список соискателей, прошедших тестирование</h2>
                <?php if (!empty($jobseekers)): ?>
                    <?php foreach ($jobseekers as $jobseeker): ?>
                        <div class="jobseeker-item">
                            <h3><?php echo htmlspecialchars($jobseeker['first_name'] . ' ' . $jobseeker['last_name'] . ' (' . $jobseeker['username'] . ')'); ?></h3>
                            <p>Результаты тестирования: <?php echo htmlspecialchars(substr($jobseeker['proforientation_test_results'], 0, 100)) . '...'; ?></p>
                            <p>Рекомендации: <?php echo htmlspecialchars(substr($jobseeker['proforientation_recommendations'], 0, 100)) . '...'; ?></p>
                            <a href="view_jobseeker.php?id=<?php echo htmlspecialchars($jobseeker['id']); ?>">Просмотреть профиль</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Нет соискателей, прошедших тестирование.</p>
                <?php endif; ?>
            </section>
            <?php endif; ?>
        </div>

        <div style="text-align:center">
            <a href="edit_profile.php" class="btn">Редактировать профиль</a>
            <a href="../index.php" class="back-link">На главную</a>
            <a href="logout.php" class="btn">Выйти</a>
        </div>
    </div>
</div>
<script src="../js/profile.js"></script>
</body>
</html>