document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const arrow = item.querySelector('.arrow');

        question.addEventListener('click', () => {
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.querySelector('.faq-answer').classList.remove('show');
                    otherItem.querySelector('.faq-question').classList.remove('active');
                    otherItem.classList.remove('active'); // Убрали padding
                    otherItem.querySelector('.arrow').style.transform = 'rotate(0deg)';
                }
             });

            answer.classList.toggle('show');
            question.classList.toggle('active');
            item.classList.toggle('active'); // Добавляем класс active к элементу faq-item
            arrow.style.transform = question.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    });
});