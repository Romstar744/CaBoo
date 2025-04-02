document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.container');
    const numberOfElements = 12; // Увеличили количество призраков
    const numberOfVerticalZones = 4; // Количество вертикальных зон

    for (let i = 0; i < numberOfElements; i++) {
        const element = document.createElement('div');
        element.classList.add('floating-ghost');

        // 1. Случайная зона по вертикали
        const zoneHeight = 80 / numberOfVerticalZones; // Высота каждой зоны
        const zone = Math.floor(Math.random() * numberOfVerticalZones); // Случайный выбор зоны
        const topPosition = zone * zoneHeight + Math.random() * zoneHeight; // Случайная позиция внутри зоны
        element.style.top = topPosition + '%';

        // 2. Случайная позиция по горизонтали (за пределами экрана)
        element.style.left =  Math.random() * -100 + '%'; // За пределами экрана слева

        // 3. Случайная продолжительность анимации
        const animationDuration = Math.random() * 20 + 15;
        element.style.animationDuration = animationDuration + 's';

        // 4. Случайная задержка
        element.style.animationDelay = Math.random() * 10 + 's';

        // 5. Случайный размер
        const size = Math.random() * 30 + 40; // Размер от 40px до 70px
        element.style.width = size + 'px';
        element.style.height = (size * 1.2) + 'px'; // Сохраняем пропорции

        container.appendChild(element);
    }
});