document.addEventListener('DOMContentLoaded', function() {
    const testForm = document.getElementById('testForm');

    if (testForm) {
        testForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const testResults = JSON.stringify(getTestResults());
            const recommendations = JSON.stringify(getRecommendations());

            const formData = new FormData();
            formData.append('test_results', testResults);
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

    function getTestResults() {
        const results = {
            q1: document.getElementById('q1').value,
            q2: document.getElementById('q2').value,
            q3: document.getElementById('q3').value,
            q4: document.getElementById('q4').value,
            q5: document.getElementById('q5').value,
            q6: document.getElementById('q6').value,
            q7: document.getElementById('q7').value,
            q8: document.getElementById('q8').value,
            q9: document.getElementById('q9').value,
            q10: document.getElementById('q10').value
        };
        return results;
    }

    function getRecommendations() {
        // Здесь будет логика для генерации рекомендаций на основе результатов теста
        // Пока просто возвращаем пример
        const recommendations = ['Рекомендация 1', 'Рекомендация 2', 'Рекомендация 3'];
        return recommendations;
    }
});