<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
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

// Функция для проверки, находится ли вакансия в избранном у текущего пользователя
function isVacancyFavorite($conn, $user_id, $vacancy_id) {
    $sql = "SELECT COUNT(*) FROM favorites_vacancies WHERE user_id = ? AND vacancy_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $vacancy_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];
    return $count > 0;
}

// Функция для проверки, находится ли соискатель в избранном у текущего работодателя
function isJobseekerFavorite($conn, $employer_id, $jobseeker_id) {
    $sql = "SELECT COUNT(*) FROM favorites_jobseekers WHERE employer_id = ? AND jobseeker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $employer_id, $jobseeker_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];
    return $count > 0;
}

// Получаем список соискателей, прошедших тестирование (только для работодателей)
$jobseekers = [];
if ($user['role'] == 'employer') {
    $sql = "SELECT id, username, first_name, last_name, city, birthdate, desired_salary, proforientation_test_results, proforientation_recommendations FROM users WHERE role = 'seeker' AND proforientation_test_results IS NOT NULL";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobseekers[] = $row;
        }
    }
}
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
        <?php if ($user['role'] == 'seeker'): ?>
            <section class="info-block">
                <div class="resume-download-button">
                    <p>Скачать шаблон резюме <a href="../resumes/resume.docx" class="btn btn-sm" download="resume_template.docx"> <i class="fas fa-download"></i></a></p>
                </div>
                <h2>Резюме соискателя</h2>
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
                        <input type="text" value="<?php echo htmlspecialchars($user['desired_salary'] ?? 'Не указано'); ?> ₽" readonly>
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
            <div id="testResults">
            <?php
            // Получаем результаты тестирования и рекомендации из базы данных
            $testResults = json_decode($user['proforientation_test_results'], true); // Декодируем JSON с результатами
            $recommendationsHTML = $user['proforientation_recommendations']; // Получаем HTML-разметку рекомендаций

            if ($testResults && is_array($testResults)) {
                echo "<p>Результаты:</p>";
                echo "<ul>";
                foreach ($testResults as $key => $value) {
                    $questionId = htmlspecialchars($key);
                    echo "<li>" . htmlspecialchars($key) . ": " . htmlspecialchars($value) . "</li>"; // Экранируем значения
                }
                echo "</ul>";
            } else {
                echo "<p>Результаты не найдены.</p>";
            }

            echo "<h2>Рекомендации:</h2>";
            echo "<div class='recommendationsContainer'>";
            echo $recommendationsHTML; // Выводим HTML-разметку рекомендаций (БЕЗ ЭКРАНИРОВАНИЯ!)
            echo "</div>";
            ?>
            </div>
        </section>

        <!-- Отображение доступных вакансий для соискателя, прошедшего тестирование -->
        <section class="info-block">
            <h2>Доступные вакансии</h2>
            <?php
                $sql = "SELECT v.*, u.company_name 
                        FROM vacancies v
                        JOIN users u ON v.company_id = u.id";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0): ?>
                    <div class="vacancy-list">
                        <?php while($vacancy = $result->fetch_assoc()): ?>
                            <div class="vacancy-item">
                                <h3><?= htmlspecialchars($vacancy['title']) ?></h3>
                                <p>Компания: <?= htmlspecialchars($vacancy['company_name']) ?></p>
                                <p>Зарплата: <?= htmlspecialchars($vacancy['salary']) ?> ₽</p>
                                <a href="view_vacancy.php?id=<?= $vacancy['id'] ?>" class="btn">Подробнее</a>
                                    <button class="favorite-btn"
                                            data-vacancy-id="<?= $vacancy['id'] ?>"
                                            data-is-favorite="<?= isVacancyFavorite($conn, $user_id, $vacancy['id']) ? 'true' : 'false' ?>">
                                        <i class="fas fa-heart<?= isVacancyFavorite($conn, $user_id, $vacancy['id']) ? '' : '-o' ?>"></i>
                                        <span class="favorite-text"><?= isVacancyFavorite($conn, $user_id, $vacancy['id']) ? 'В избранном' : 'В избранное' ?></span>
                                    </button>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>Нет доступных вакансий</p>
                <?php endif; ?>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($user['role'] == 'seeker'): ?>
    <section class="info-block">
        <h2>Мои чаты</h2>
        <?php
        $sql = "SELECT c.id, u.username AS employer_username
                FROM chats c
                JOIN users u ON c.employer_id = u.id
                WHERE c.seeker_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $chats = $stmt->get_result();

        if ($chats->num_rows > 0): ?>
            <ul>
                <?php while ($chat = $chats->fetch_assoc()): ?>
                    <li>
                        <a href="chat.php?chat_id=<?php echo htmlspecialchars($chat['id']); ?>">
                            Чат с работодателем: <?php echo htmlspecialchars($chat['employer_username']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>У вас пока нет активных чатов.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php if ($user['role'] == 'employer'): ?>
            <section class="info-block">
                <h2>Информация о работодателе</h2>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-building"></i> Название компании:</label>
                        <input type="text" name="companyName" value="<?php echo htmlspecialchars($user['company_name']  ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-industry"></i> Индустрия:</label>
                        <input type="text" name="industry" value="<?php echo htmlspecialchars($user['industry'] ?? 'Не указано'); ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label><i class="fas fa-comment-dots"></i> Описание компании:</label>
                        <textarea readonly="companyDescription"><?php echo htmlspecialchars($user['company_description'] ?? 'Не указано'); ?> </textarea>
                    </div>
                </div>
            </section>
            <?php endif; ?>

<?php if ($user['role'] == 'employer'): ?>
    <section class="info-block jobseeker-list">
        <h2>Список соискателей, прошедших тестирование</h2>
        <?php if (!empty($jobseekers)): ?>
            <?php foreach ($jobseekers as $jobseeker): ?>
                <div class="jobseeker-item">
                    <h3><?php echo htmlspecialchars($jobseeker['first_name'] . ' ' . $jobseeker['last_name'] . ' '); ?></h3>
                    <p>Город: <?php echo htmlspecialchars($jobseeker['city'] ?? 'Не указано'); ?></p>
                    <p>Дата рождения: <?php echo htmlspecialchars($jobseeker['birthdate'] ?? 'Не указано'); ?></p>
                    <p>Желаемая зарплата: <?php echo htmlspecialchars($jobseeker['desired_salary'] ?? 'Не указано'); ?> ₽</p>
                    <a href="view_jobseeker.php?id=<?php echo htmlspecialchars($jobseeker['id']); ?>">Просмотреть профиль</a>
                    <button class="favorite-btn"
                            data-jobseeker-id="<?= $jobseeker['id'] ?>"
                            data-is-favorite="<?= isJobseekerFavorite($conn, $user_id, $jobseeker['id']) ? 'true' : 'false' ?>">
                        <i class="fas fa-heart<?= isJobseekerFavorite($conn, $user_id, $jobseeker['id']) ? '' : '-o' ?>"></i>
                        <span class="favorite-text"><?= isJobseekerFavorite($conn, $user_id, $jobseeker['id']) ? 'В избранном' : 'В избранное' ?></span>
                    </button>
                    <a href="start_chat.php?seeker_id=<?php echo htmlspecialchars($jobseeker['id']); ?>">Начать чат</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет соискателей, прошедших тестирование.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php if ($user['role'] == 'employer'): ?>
    <section class="info-block vacancy-management">
        <h2>Управление вакансиями</h2>
        <a href="create_vacancy.php" class="btn create-vacancy-btn">Создать новую вакансию</a>

        <?php
        // Получаем вакансии компании
        $sql = "SELECT * FROM vacancies WHERE company_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $vacancies = $stmt->get_result();

        if ($vacancies->num_rows > 0): ?>
            <div class="vacancy-list">
                <?php while($vacancy = $vacancies->fetch_assoc()): ?>
                    <div class="vacancy-item">
                        <h3><?= htmlspecialchars($vacancy['title']) ?></h3>
                        <p>Зарплата: <?= htmlspecialchars($vacancy['salary']) ?> ₽</p>
                        <div class="vacancy-actions">
                            <a href="edit_vacancy.php?id=<?= $vacancy['id'] ?>" class="btn edit-btn">Редактировать</a>
                            <a href="delete_vacancy.php?id=<?= $vacancy['id'] ?>" class="btn delete-btn">Удалить</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>У вас пока нет активных вакансий</p>
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