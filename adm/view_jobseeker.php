<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Проверка роли пользователя (должен быть работодатель)
if ($_SESSION['role'] != 'employer') {
    die("У вас нет прав для просмотра этой страницы.");
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

// Получаем ID соискателя из GET-параметра
$jobseeker_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($jobseeker_id <= 0) {
    die("Некорректный ID соискателя.");
}

// Получаем информацию о соискателе из базы данных
$sql = "SELECT * FROM users WHERE id = ? AND role = 'seeker'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $jobseeker_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $jobseeker = $result->fetch_assoc();
} else {
    die("Соискатель не найден.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль соискателя</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<div class="container">
    <div class="profile-form">
        <h1>Профиль соискателя</h1>

        <div class="profile-info">
            <section class="info-block">
                <h2>Основная информация</h2>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-user"></i> Логин:</label>
                        <input type="text" value="<?php echo htmlspecialchars($jobseeker['username']); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" value="<?php echo htmlspecialchars($jobseeker['email']); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-signature"></i> Имя:</label>
                        <input type="text" value="<?php echo htmlspecialchars($jobseeker['first_name'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-signature"></i> Фамилия:</label>
                        <input type="text" value="<?php echo htmlspecialchars($jobseeker['last_name'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>

                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-city"></i> Город:</label>
                        <input type="text" value="<?php echo htmlspecialchars($jobseeker['city'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-birthday-cake"></i> Дата рождения:</label>
                        <input type="text" value="<?php echo htmlspecialchars($jobseeker['birthdate'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-venus-mars"></i> Пол:</label>
                        <input type="text" value="<?php
                        switch ($jobseeker['gender']) {
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
                        <input type="text" value="<?php echo htmlspecialchars($jobseeker['desired_salary'] ?? 'Не указано'); ?> ₽" readonly>
                    </div>
                </div>
                 <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-tools"></i> Навыки:</label>
                            <textarea readonly><?php echo htmlspecialchars($jobseeker['skills'] ?? 'Не указано'); ?></textarea>
                        </div>
                    </div>
                 <div class="form-group">
                        <div class="form-row">
                          <label><i class="fas fa-comment-dots"></i> О себе:</label>
                          <textarea  readonly><?php echo htmlspecialchars($jobseeker['about'] ?? 'Не указано'); ?></textarea>
                        </div>
                    </div>
                 <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-share-alt"></i> Социальные сети:</label>
                        <textarea readonly><?php echo htmlspecialchars($jobseeker['social_links'] ?? 'Не указано'); ?></textarea>
                    </div>
                </div>
                <!-- Информация об образовании -->
                <section class="info-block">
                    <h2>Информация об образовании</h2>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Учебное заведение:</label>
                            <input type="text" value="<?php echo htmlspecialchars($jobseeker['educationInstitution'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Степень:</label>
                            <input type="text" value="<?php echo htmlspecialchars($jobseeker['educationDegree'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Начало обучения:</label>
                            <input type="text" value="<?php echo htmlspecialchars($jobseeker['educationStart'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Окончание обучения:</label>
                            <input type="text" value="<?php echo htmlspecialchars($jobseeker['educationEnd'] ?? 'Не указано'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <label><i class="fas fa-graduation-cap"></i> Описание:</label>
                            <textarea readonly><?php echo htmlspecialchars($jobseeker['educationDescription'] ?? 'Не указано'); ?></textarea>
                        </div>
                    </div>
                </section>
                <br>
            <section class="info-block">
    <h2>Результаты профориентационного тестирования</h2>
    <?php
    // Получаем результаты тестирования и рекомендации из базы данных
    $testResults = json_decode($jobseeker['proforientation_test_results'], true);
    $recommendationsHTML = $jobseeker['proforientation_recommendations']; // Получаем HTML-разметку

    if ($testResults && is_array($testResults)) {
        echo "<p>Результаты:</p>";
        echo "<ul>";
        foreach ($testResults as $key => $value) {
            echo "<li>" . htmlspecialchars($key) . ": " . htmlspecialchars($value) . "</li>"; // Экранируем значения
        }
        echo "</ul>";
    } else {
        echo "<p>Результаты не найдены.</p>";
    }

    echo "<h2>Рекомендации:</h2>";
    echo "<div class='recommendationsContainer'>"; // Добавляем контейнер для стилей
    echo $recommendationsHTML; // Выводим HTML-разметку (БЕЗ ЭКРАНИРОВАНИЯ!)
    echo "</div>";
    ?>
            <section class="info-block">
                <?php if ($jobseeker['role'] == 'seeker'): ?>
                <h2>Резюме соискателя</h2>
                <div class="resume-preview">
                    <?php
                    $relativePath = $jobseeker['resume']; // Относительный путь из базы данных

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
                            echo '<p>Неподдерживаемый формат файла или отсуствует загруженный документ.</p>';
                        }
                    } else {
                        echo '<p>Резюме не загружено.</p>';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </section>
</section>
            </section>
        </div>

        <div style="text-align:center">
            <a href="mailto:<?php echo htmlspecialchars($jobseeker['email']); ?>" class="btn">Связаться с соискателем</a>
            <a href="profile.php" class="back-link">Вернуться в личный кабинет</a>
        </div>
    </div>
</div>
<script src="../js/profile.js"></script>
</body>
</html>