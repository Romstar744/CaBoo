<!DOCTYPE html>
<html>
<head>
    <title>Профориентационное тестирование</title>
    <link rel="stylesheet" href="../css/test.css">
</head>
<body>
    <div class="container">
        <h1>Пройдите профориентационное тестирование</h1>
        <form id="testForm">
            <div class="question">
                <label for="q1">Вопрос 1: Что вам больше нравится делать в свободное время?</label>
                <select id="q1" name="q1">
                    <option value="reading">Читать книги или статьи</option>
                    <option value="coding">Программировать</option>
                    <option value="designing">Заниматься дизайном</option>
                    <option value="playing">Играть в игры</option>
                </select>
            </div>
            <div class="question">
                <label for="q2">Вопрос 2: Какие навыки у вас лучше всего развиты?</label>
                <select id="q2" name="q2">
                    <option value="problem_solving">Решение проблем</option>
                    <option value="communication">Общение с людьми</option>
                    <option value="creativity">Креативность</option>
                    <option value="analytical_skills">Аналитические способности</option>
                </select>
            </div>
            <div class="question">
                <label for="q3">Вопрос 3: Какое описание вам больше подходит?</label>
                <select id="q3" name="q3">
                    <option value="organized">Организованный и методичный</option>
                    <option value="creative">Креативный и изобретательный</option>
                    <option value="outgoing">Общительный и дружелюбный</option>
                    <option value="independent">Независимый и самостоятельный</option>
                </select>
            </div>
            <div class="question">
                <label for="q4">Вопрос 4: Какой язык программирования вам кажется наиболее интересным?</label>
                <select id="q4" name="q4">
                    <option value="python">Python</option>
                    <option value="javascript">JavaScript</option>
                    <option value="java">Java</option>
                    <option value="csharp">C#</option>
                </select>
            </div>
            <div class="question">
                <label for="q5">Вопрос 5: Что для вас важнее при выборе работы?</label>
                <select id="q5" name="q5">
                    <option value="salary">Высокая зарплата</option>
                    <option value="interesting_tasks">Интересные задачи</option>
                    <option value="career_growth">Возможность карьерного роста</option>
                    <option value="work_life_balance">Баланс между работой и личной жизнью</option>
                </select>
            </div>
            <div class="question">
                <label for="q6">Вопрос 6: Как вы предпочитаете учиться?</label>
                <select id="q6" name="q6">
                    <option value="online_courses">Онлайн-курсы</option>
                    <option value="books">Чтение книг</option>
                    <option value="mentoring">Работа с наставником</option>
                    <option value="self_learning">Самостоятельное изучение</option>
                </select>
            </div>
             <div class="question">
                <label for="q7">Вопрос 7: В какой области программирования вы хотели бы развиваться?</label>
                <select id="q7" name="q7">
                    <option value="web_development">Веб-разработка</option>
                    <option value="mobile_development">Мобильная разработка</option>
                    <option value="data_science">Наука о данных</option>
                    <option value="game_development">Разработка игр</option>
                </select>
            </div>
             <div class="question">
                <label for="q8">Вопрос 8: Что вы цените в работе в команде?</label>
                <select id="q8" name="q8">
                    <option value="collaboration">Совместная работа и взаимопомощь</option>
                    <option value="clear_communication">Четкая коммуникация и обратная связь</option>
                    <option value="shared_goals">Общие цели и задачи</option>
                    <option value="respect">Взаимное уважение и поддержка</option>
                </select>
            </div>
             <div class="question">
                <label for="q9">Вопрос 9: Как вы обычно решаете сложные задачи?</label>
                <select id="q9" name="q9">
                    <option value="break_down">Разбиваю на более мелкие подзадачи</option>
                    <option value="research">Ищу информацию и примеры в интернете</option>
                    <option value="ask_for_help">Обращаюсь за помощью к коллегам</option>
                    <option value="experiment">Экспериментирую с разными подходами</option>
                </select>
            </div>
             <div class="question">
                <label for="q10">Вопрос 10: Какая роль вам больше нравится в проекте?</label>
                <select id="q10" name="q10">
                    <option value="leader">Лидер команды</option>
                    <option value="executor">Исполнитель задач</option>
                    <option value="analyst">Аналитик требований</option>
                    <option value="tester">Тестировщик</option>
                </select>
            </div>
            <button type="submit">Отправить результаты</button>
        </form>
    </div>
    <script src="../js/test.js"></script>
</body>
</html>