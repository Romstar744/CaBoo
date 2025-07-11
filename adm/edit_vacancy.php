<?php
session_start();

if ($_SESSION['role'] != 'employer') {
    header("Location: profile.php");
    exit();
}

// Подключение к БД (замените на свои данные)
$servername = "localhost";
$username = "starostin_CaBoo";
$password = "Admin123*";
$dbname = "starostin_CaBoo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем ID вакансии из GET-параметра
$vacancy_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($vacancy_id <= 0) {
    die("Некорректный ID вакансии.");
}

// Получаем информацию о вакансии из базы данных
$sql = "SELECT * FROM vacancies WHERE id = ? AND company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vacancy_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $vacancy = $result->fetch_assoc();
} else {
    die("Вакансия не найдена или у вас нет прав на ее редактирование.");
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактирование вакансии</title>
    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Подключаем Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Подключаем файл CSS -->
    <link rel="stylesheet" href="../css/create_vacancy.css">
</head>
<body>
    <div class="container">
        <div class="profile-form">
            <h1>Редактирование вакансии</h1>
            <form action="update_vacancy.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($vacancy['id']); ?>">

                <div class="form-group">
                    <label for="title">Название вакансии:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($vacancy['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($vacancy['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="requirements">Требования:</label>
                    <textarea id="requirements" name="requirements" rows="5" required><?php echo htmlspecialchars($vacancy['requirements']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="salary"><i class="fas fa-dollar-sign"></i> Зарплата:</label>
                    <div class="salary-input">
                        <input type="number" id="salary" name="salary" placeholder="Укажите предлагаемую зарплату" min="0" max="9999999">
                        <span class="currency">₽</span>
                    </div>
                </div>

                <button type="submit" class="btn">Сохранить изменения</button>
                <a href="profile.php" class="back-link">Отмена</a>
            </form>
        </div>
    </div>
</body>
</html>