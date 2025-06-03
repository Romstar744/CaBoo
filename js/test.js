document.addEventListener('DOMContentLoaded', function() {
    const testForm = document.getElementById('testForm');

    if (testForm) {
        testForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const testResults = getTestResults();
            const recommendations = getRecommendations(testResults);

            const formData = new FormData();
            formData.append('test_results', JSON.stringify(testResults));
            formData.append('recommendations', recommendations);

            fetch('../adm/test_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = '../adm/profile.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при отправке данных на сервер.');
            });
        });
    }

    // Определяем соответствия вопросов и ответов на русском языке
    const questionTranslations = {
        q1: "Что вам больше нравится?",
        q2: "Какие навыки у вас развиты лучше?",
        q3: "Каким вы видите себя?",
        q4: "Какой язык программирования вам ближе?",
        q5: "Что для вас важнее всего?",
        q6: "Как вы предпочитаете учиться?",
        q7: "Что вам интересно в ИТ?",
        q8: "Что важно в командной работе?",
        q9: "Как вы решаете проблемы?",
        q10: "Какую роль вы предпочитаете в команде?"
    };

    const answerTranslations = {
        reading: "Чтение",
        coding: "Программирование",
        designing: "Дизайн",
        playing: "Игры",
        problem_solving: "Решение проблем",
        communication: "Общение",
        creativity: "Творчество",
        analytical_skills: "Аналитические навыки",
        organized: "Организованный",
        creative: "Творческий",
        outgoing: "Общительный",
        independent: "Независимый",
        python: "Python",
        javascript: "JavaScript",
        java: "Java",
        csharp: "C#",
        salary: "Зарплата",
        interesting_tasks: "Интересные задачи",
        career_growth: "Карьерный рост",
        work_life_balance: "Баланс работы и личной жизни",
        web_development: "Веб-разработка",
        mobile_development: "Мобильная разработка",
        data_science: "Наука о данных",
        game_development: "Разработка игр",
        collaboration: "Сотрудничество",
        clear_communication: "Четкое общение",
        shared_goals: "Общие цели",
        respect: "Уважение",
        break_down: "Разбиваю на части",
        research: "Исследую",
        ask_for_help: "Прошу помощи",
        experiment: "Экспериментирую",
        leader: "Лидер",
        executor: "Исполнитель",
        analyst: "Аналитик",
        tester: "Тестировщик"
    };

    function getTestResults() {
        const results = {};
        for (let i = 1; i <= 10; i++) {
            const questionId = `q${i}`;
            const answerId = document.getElementById(questionId).value;
            results[questionId] = answerTranslations[answerId]; // Сохраняем только ПЕРЕВОДЫ ответов
        }
        return results;
    }

   function getRecommendations(results) {
        // Веса для каждого ответа
        const weights = {
            'q1': { // Используем ID вопросов
                'reading': { 'Веб-разработка': 1, 'Data Science': 1, 'Анализ': 1 },
                'coding': { 'Веб-разработка': 2, 'Data Science': 2, 'Разработка игр': 2 },
                'designing': { 'Веб-разработка': 1, 'UX/UI': 2, 'Front-end': 1 },
                'playing': { 'Разработка игр': 2, 'QA': 1, 'Data Science': 1 }
            },
            'q2': {
                'problem_solving': { 'Веб-разработка': 2, 'Data Science': 2, 'QA': 2 },
                'communication': { 'Менеджмент': 2, 'UX/UI': 1, 'Анализ': 1 },
                'creativity': { 'UX/UI': 2, 'Front-end': 2, 'Разработка игр': 2 },
                'analytical_skills': { 'Data Science': 2, 'Анализ': 2, 'QA': 2 }
            },
            'q3': {
                'organized': { 'Back-end': 2, 'Менеджмент': 1, 'QA': 1 },
                'creative': { 'Front-end': 2, 'UX/UI': 2, 'Разработка игр': 1 },
                'outgoing': { 'Менеджмент': 2, 'Front-end': 1, 'UX/UI': 1 },
                'independent': { 'Data Science': 2, 'Back-end': 1, 'Разработка игр': 1 }
            },
            'q4': {
                'python': { 'Data Science': 2, 'Back-end': 1, 'QA': 1 },
                'javascript': { 'Веб-разработка': 2, 'Front-end': 2, 'UX/UI': 1 },
                'java': { 'Back-end': 2, 'Android': 2, 'QA': 1 },
                'csharp': { 'Разработка игр': 2, 'Back-end': 1, 'QA': 1 }
            },
            'q5': {
                'salary': { 'Data Science': 2, 'Back-end': 2, 'Менеджмент': 1 },
                'interesting_tasks': { 'Веб-разработка': 2, 'Разработка игр': 2, 'UX/UI': 2 },
                'career_growth': { 'Менеджмент': 2, 'Data Science': 1, 'Back-end': 1 },
                'work_life_balance': { 'Front-end': 2, 'UX/UI': 1, 'QA': 2 }
            },
            'q6': {
                'online_courses': { 'Веб-разработка': 1, 'Data Science': 1, 'UX/UI': 1 },
                'books': { 'Data Science': 2, 'Back-end': 2, 'QA': 1 },
                'mentoring': { 'Менеджмент': 2, 'Front-end': 1, 'Веб-разработка': 1 },
                'self_learning': { 'Разработка игр': 2, 'Data Science': 1, 'Веб-разработка': 1 }
            },
            'q7': {
                'web_development': { 'Веб-разработка': 2, 'Front-end': 2, 'Back-end': 2 },
                'mobile_development': { 'Android': 2, 'iOS': 2, 'QA': 1 },
                'data_science': { 'Data Science': 2, 'Анализ': 2, 'Веб-разработка': 1 },
                'game_development': { 'Разработка игр': 2, 'QA': 1, 'UX/UI': 1 }
            },
            'q8': {
                'collaboration': { 'Веб-разработка': 1, 'Data Science': 1, 'Менеджмент': 2 },
                'clear_communication': { 'Менеджмент': 2, 'Front-end': 1, 'QA': 1 },
                'shared_goals': { 'Веб-разработка': 2, 'Data Science': 2, 'Разработка игр': 2 },
                'respect': { 'Менеджмент': 1, 'UX/UI': 1, 'QA': 2 }
            },
            'q9': {
                'break_down': { 'Веб-разработка': 1, 'Data Science': 1, 'Менеджмент': 2 },
                'research': { 'Data Science': 2, 'Анализ': 2, 'QA': 1 },
                'ask_for_help': { 'Веб-разработка': 2, 'Front-end': 2, 'UX/UI': 1 },
                'experiment': { 'Разработка игр': 2, 'Front-end': 1, 'QA': 2 }
            },
            'q10': {
                'leader': { 'Менеджмент': 2, 'Веб-разработка': 1, 'Data Science': 1 },
                'executor': { 'Back-end': 2, 'QA': 2, 'Веб-разработка': 1 },
                'analyst': { 'Data Science': 2, 'Анализ': 2, 'QA': 1 },
                'tester': { 'QA': 2, 'Веб-разработка': 1, 'Разработка игр': 1 }
            }
        };

        const categories = {
            'Веб-разработка': { description: 'Создание сайтов и веб-приложений.', skills: ['HTML', 'CSS', 'JavaScript'] },
            'Front-end': { description: 'Разработка пользовательского интерфейса.', skills: ['React', 'Vue', 'Angular'] },
            'Back-end': { description: 'Разработка серверной части приложений.', skills: ['Node.js', 'Python', 'Java'] },
            'Data Science': { description: 'Анализ данных и машинное обучение.', skills: ['Python', 'SQL', 'Machine Learning'] },
            'UX/UI': { description: 'Проектирование удобных и привлекательных интерфейсов.', skills: ['Figma', 'Adobe XD', 'Прототипирование'] },
            'Разработка игр': { description: 'Создание компьютерных и мобильных игр.', skills: ['C#', 'Unity', 'C++'] },
            'Менеджмент': { description: 'Управление проектами и командами.', skills: ['Agile', 'Scrum', 'Лидерство'] },
            'QA': { description: 'Тестирование программного обеспечения.', skills: ['Тест-кейсы', 'Автоматизированное тестирование', 'Анализ ошибок'] },
            'Анализ': { description: 'Сбор и анализ данных.', skills: ['сбор данных', 'статистика', 'excel', 'Google Analytics', 'Яндекс Метрика'] }
        };

        // Рассчитываем баллы для каждой категории
        const scores = {};
        Object.keys(categories).forEach(category => scores[category] = 0);

        for (const question in results) {
            const answer = results[question];
            if (weights[question] && weights[question][answer]) {
                for (const category in weights[question][answer]) {
                    scores[category] += weights[question][answer][category];
                }
            }
        }

        // Определяем наиболее подходящие категории
        const sortedScores = Object.entries(scores).sort(([, a], [, b]) => b - a);
        const topCategories = sortedScores.slice(0, 3).map(entry => entry[0]); // Топ 3

        let recommendations = [];
        topCategories.forEach(category => {
            recommendations.push({
                title: category,
                description: categories[category].description,
                skills: categories[category].skills
            });
        });

         // Красивое форматирование рекомендаций
        let formattedRecommendations = recommendations.map(rec => {
            return `
                <div class="recommendation">
                    <h3>${rec.title}</h3>
                    <p>${rec.description}</p>
                    <p>Ключевые навыки: ${rec.skills.join(', ')}</p>
                </div>
            `;
        }).join('');

        // УДАЛЯЕМ СИМВОЛЫ НОВОЙ СТРОКИ
        formattedRecommendations = formattedRecommendations.replace(/\n/g, '');

        return formattedRecommendations;
    }
});