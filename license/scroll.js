const scrollDownButton = document.getElementById('scrollDownButton');
const scrollThreshold = 0.8; // Порог для исчезновения кнопки (80% от верха)

let isButtonVisible = true; // Изначально кнопка видна

scrollDownButton.addEventListener('click', function() {
    window.scrollTo({
        top: document.documentElement.scrollHeight,
        behavior: 'smooth'
    });
    scrollDownButton.style.display = 'none';
    isButtonVisible = false;
});

window.addEventListener('scroll', function() {
    const scrollPosition = window.scrollY;
    const documentHeight = document.documentElement.scrollHeight;
    const viewportHeight = window.innerHeight;

    const scrollPercentageFromTop = scrollPosition / (documentHeight - viewportHeight);

   if (scrollPercentageFromTop >= scrollThreshold && isButtonVisible) {
        scrollDownButton.style.display = 'none';
        isButtonVisible = false;
    } else if (scrollPercentageFromTop < scrollThreshold && !isButtonVisible) {
        scrollDownButton.style.display = 'block';
        isButtonVisible = true;
    }
});