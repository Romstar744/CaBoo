const avatar = document.querySelector('.profile-avatar img');

avatar.addEventListener('mouseover', () => {
    avatar.style.transform = 'rotate(10deg)';
    avatar.style.transition = 'transform 0.3s ease-in-out';
});

avatar.addEventListener('mouseout', () => {
    avatar.style.transform = 'rotate(0deg)';
});

// Добавляем обработку для кнопок "Избранное"
document.addEventListener('DOMContentLoaded', () => {
    const favoriteButtons = document.querySelectorAll('.favorite-btn');

    favoriteButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            event.preventDefault(); // Предотвращаем перезагрузку страницы

            const vacancyId = button.dataset.vacancyId;
            const jobseekerId = button.dataset.jobseekerId;
            let isFavorite = button.dataset.isFavorite === 'true';

            let url = 'toggle_favorite_ajax.php'; // Используем отдельный файл для AJAX

            if (vacancyId) {
                url += `?vacancy_id=${vacancyId}`;
            } else if (jobseekerId) {
                url += `?jobseeker_id=${jobseekerId}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    isFavorite = !isFavorite; // Инвертируем состояние
                    button.dataset.isFavorite = isFavorite.toString(); // Обновляем data-атрибут

                    const icon = button.querySelector('i');
                    const textSpan = button.querySelector('.favorite-text');

                    if (isFavorite) {
                        icon.classList.remove('fa-heart-o');
                        icon.classList.add('fa-heart');
                        textSpan.textContent = 'В избранном';
                    } else {
                        icon.classList.remove('fa-heart');
                        icon.classList.add('fa-heart-o');
                        textSpan.textContent = 'В избранное';
                    }
                } else {
                    alert('Ошибка при добавлении/удалении из избранного: ' + data.error);
                }
            } catch (error) {
                console.error('Ошибка при выполнении запроса:', error);
                alert('Произошла ошибка при добавлении/удалении из избранного.');
            }
        });
    });
});