document.addEventListener('DOMContentLoaded', function () {
    const nav = document.querySelector('.slider-nav');
     const navItems = nav.querySelectorAll('li');
    const sections = document.querySelectorAll('section');
    const header = document.querySelector('header');
    const offset = 0; // Дополнительное смещение в пикселях

     // Функция для определения текущей секции и активации соответствующего пункта меню
    function updateActiveSection() {
      let currentSection = null;
         const scrollPosition = window.scrollY;

          sections.forEach(section => {
           const sectionTop = section.offsetTop - header.offsetHeight - offset;
            const sectionBottom = sectionTop + section.offsetHeight;

            if(scrollPosition >= sectionTop && scrollPosition < sectionBottom){
                currentSection = section;
            }
          });
    }
    // Инициализация индикатора при загрузке страницы
    requestAnimationFrame(updateActiveSection);

    // Добавляем слушатели клика на пункты меню
    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const targetSectionId = this.getAttribute('data-target');
            const targetSection = document.getElementById(targetSectionId);
            const headerHeight = header.offsetHeight;
            window.scrollTo({
                top: targetSection.offsetTop - headerHeight - offset,
                behavior: 'smooth'
            });
          requestAnimationFrame(updateActiveSection);
        });
    });

    // Слушаем событие прокрутки и обновляем состояние слайдера
    window.addEventListener('scroll', () => {
        updateActiveSection();
    });
});