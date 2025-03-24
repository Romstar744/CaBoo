document.addEventListener('DOMContentLoaded', function() {
    // === Слайдер ===
    const slider = document.querySelector('.testimonial-slider');
    const prevButton = document.querySelector('.slider-prev');
    const nextButton = document.querySelector('.slider-next');
    let currentIndex = 0;
    let autoSlideInterval;

    // Функция для загрузки и отображения отзывов из JSON
    function loadTestimonials() {
        fetch('media/comments.json')
            .then(response => response.json())
            .then(data => {
                data.forEach((item, index) => {
                    const testimonialDiv = document.createElement('div');
                    testimonialDiv.classList.add('testimonial');
                    if(index === 0) {
                         testimonialDiv.classList.add('active')
                    }
                    testimonialDiv.innerHTML = `
                        <p>"${item.text}"</p>
                        <cite>${item.author}</cite>
                    `;
                    slider.appendChild(testimonialDiv);
                });

                const slides = document.querySelectorAll('.testimonial');
                const slideCount = slides.length;

                   function updateSlider() {
                        slider.scrollTo({
                            left: slides[currentIndex].offsetLeft,
                            behavior: 'smooth'
                        });

                         slides.forEach((slide, index) => {
                            if (index === currentIndex) {
                                slide.classList.add('active');
                            } else {
                                slide.classList.remove('active');
                            }
                        });
                    }

                   function nextSlide() {
                        currentIndex = (currentIndex + 1) % slideCount;
                        updateSlider();
                   }

                   function prevSlide() {
                        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
                        updateSlider();
                   }

                    nextButton.addEventListener('click', function () {
                        nextSlide();
                        clearInterval(autoSlideInterval);
                        startAutoSlide();
                    });

                    prevButton.addEventListener('click', function () {
                    prevSlide();
                    clearInterval(autoSlideInterval);
                    startAutoSlide();
                    });

                function startAutoSlide() {
                    autoSlideInterval = setInterval(nextSlide, 7000);
                }

                 startAutoSlide();

            })
            .catch(error => console.error('Error loading testimonials:', error));
    }

    loadTestimonials();

    // === Анимация плашек по очереди сверху вниз ===
    const steps = document.querySelectorAll(".how-it-works .step");
    const observer = new IntersectionObserver(entries => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add("card-visible");
                }, index * 200);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2,
    });

    steps.forEach(step => {
        observer.observe(step);
    });


    // === Анимация элементов статистики ===
    const stats = document.querySelectorAll(".statistics .stat");
    const observerStats = new IntersectionObserver(entries => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.classList.add("card-visible");
            }, index * 200);
          observerStats.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.2,
    });

    stats.forEach(stat => {
      observerStats.observe(stat);
    });

    // === Анимация элементов возможности ===
    const cards = document.querySelectorAll(".rec-card");
    const observerM = new IntersectionObserver(entries => {
      entries.forEach((entry, index) => {
         if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.classList.add("card-visible");
            }, index * 200);
            observerM.unobserve(entry.target);
          }
      });
    }, {
        threshold: 0.2, // 20% видимости
    });
     cards.forEach(card => {
        observerM.observe(card);
    });

    const onas = document.querySelectorAll(".info-block");
    const observero = new IntersectionObserver(entries => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.classList.add("card-visible");
            }, index * 200);
            observero.unobserve(entry.target);
        }
    });
    }, {
    threshold: 0.2,
    });

    onas.forEach(onas => {
        observero.observe(onas);
    });
});