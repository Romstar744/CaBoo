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
                <!-- Другая информация о соискателе -->
                <section class="info-block">
                    <h2>Результаты профориентационного тестирования</h2>
                    <?php
                    // Отображаем результаты тестирования
                    $testResults = json_decode($jobseeker['proforientation_test_results'], true);
                    $recommendations = json_decode($jobseeker['proforientation_recommendations'], true);

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