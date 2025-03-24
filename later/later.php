<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret</title>
    <link rel="stylesheet" href="later.css">
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <h1>Secret room</h1>
        <div id="code-input-container">
            <input type="text" id="code-input" placeholder="enter code">
            <button id="submit-code">check</button>
        </div>
        <div id="buttons-container" style="display: none;">
            <button class="nav-button" onclick="window.location.href='news.php'">Новости</button>
            <button class="nav-button" onclick="window.location.href='stat.php'">Статистика</button>
        </div>
          <div id="error-message" style="display: none; color: red; margin-top: 10px;">
            Тебе здесь не место!
        </div>
    </div>
    <script src="later.js"></script>
</body>
</html>