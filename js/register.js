document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    if(error){
        const errorData = JSON.parse(decodeURIComponent(error));
        for (const key in errorData) {
            if (errorData.hasOwnProperty(key)) {
                const errorField = document.getElementById(key + 'Error');
                if(errorField){
                    errorField.textContent = errorData[key];
                }
            }
        }
    }
    // Валидация email
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('input', function () {
        const emailError = document.getElementById('emailError');
        if (!emailInput.validity.valid) {
            emailError.textContent = 'Пожалуйста, введите корректный email.';
            emailInput.classList.add('invalid');
        } else if (!/@gmail\.com$/.test(this.value)) { // Проверка gmail
            emailError.textContent = 'Разрешена только Gmail почта.';
            emailInput.classList.add('invalid');
        }
         else {
            checkEmailAvailability(this.value);
            emailInput.classList.remove('invalid');
        }
    });

    const usernameInput = document.getElementById('username');
    usernameInput.addEventListener('input', function () {
        checkUsernameAvailability(this.value);
    });

     // Новая логика для отображения/скрытия полей и валидации
    const employerFields = document.getElementById('employerFields');
    const seekerRole = document.getElementById('seekerRole');
    const employerRole = document.getElementById('employerRole');
    const companyNameInput = document.getElementById('company_name');
    const industryInput = document.getElementById('industry');

    function toggleEmployerFields() {
        employerFields.style.display = employerRole.checked ? 'block' : 'none';
        companyNameInput.required = employerRole.checked;
        industryInput.required = employerRole.checked;
    }

    seekerRole.addEventListener('change', toggleEmployerFields);
    employerRole.addEventListener('change', toggleEmployerFields);

    // Инициализация при загрузке страницы
    toggleEmployerFields();

    //Валидация пароля
    const passwordInput = document.getElementById('password');
    let isPasswordValid = false; // Флаг для отслеживания валидности пароля

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const passwordError = document.getElementById('passwordError');
        let isValid = true;

        if (password.length < 8) {
            passwordError.textContent = 'Пароль должен содержать не менее 8 символов.';
            isValid = false;
        } else if (!/[A-Z]/.test(password)) {
            passwordError.textContent = 'Пароль должен содержать хотя бы одну заглавную букву.';
            isValid = false;
        } else if (!/[^a-zA-Z0-9\s]/.test(password)) {
            passwordError.textContent = 'Пароль должен содержать хотя бы один спец. символ.';
            isValid = false;
        } else {
            passwordError.textContent = '';
        }

        if (isValid) {
            passwordInput.classList.remove('invalid');
            passwordError.textContent = '';
            isPasswordValid = true; // Пароль валиден
        } else {
            passwordInput.classList.add('invalid');
            isPasswordValid = false; // Пароль невалиден
        }
    });

    // Убираем класс invalid при фокусе на поле
    passwordInput.addEventListener('focus', function() {
        this.classList.remove('invalid');
    });

    const registerForm = document.querySelector('.register-form');
    if(registerForm){
        registerForm.addEventListener('submit', function(event) {
            let isValid = true;
            const requiredFields = registerForm.querySelectorAll('input[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('invalid');
                    const errorField = document.getElementById(field.id + 'Error');
                    if (errorField) {
                        errorField.textContent = 'Это поле обязательно для заполнения.';
                    }
                } else {
                    field.classList.remove('invalid');
                    const errorField = document.getElementById(field.id + 'Error');
                    if (errorField) {
                        errorField.textContent = '';
                    }
                }
            });
           // Проверка валидности пароля перед отправкой формы
            if (!isPasswordValid) {
                isValid = false;
                passwordInput.classList.add('invalid');
                const passwordError = document.getElementById('passwordError');
                passwordError.textContent = 'Пароль не соответствует требованиям.';
            }
             // Проверка email на gmail перед отправкой формы
            const emailInput = document.getElementById('email');
            if (!/@gmail\.com$/.test(emailInput.value)) {
               isValid = false;
               emailInput.classList.add('invalid');
               const emailError = document.getElementById('emailError');
               emailError.textContent = 'Разрешена только Gmail почта.';
            }
            if (!isValid) {
                event.preventDefault();
                alert('Пожалуйста, заполните все обязательные поля.');
                const invalidFields = registerForm.querySelectorAll('.invalid');
                if (invalidFields.length > 0) {
                    const firstInvalidField = invalidFields[0];
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    }
});

function checkUsernameAvailability(username){
    const usernameError = document.getElementById('usernameError');
    if(username.trim() === ''){
        usernameError.textContent = 'Пожалуйста, введите логин.';
        return;
    }
    fetch(`../adm/check_user.php?username=${username}`)
        .then(response => response.json())
        .then(data => {
            if(data.available === false){
                usernameError.textContent = data.message;
            } else {
                usernameError.textContent = '';
            }
        })
        .catch(error => {
            usernameError.textContent = 'Ошибка при проверке логина'
        });
}

function checkEmailAvailability(email){
    const emailError = document.getElementById('emailError');
    if(email.trim() === ''){
        emailError.textContent = 'Пожалуйста, введите email.';
        return;
    }
    fetch(`../adm/check_user.php?email=${email}`)
        .then(response => response.json())
        .then(data => {
            if(data.available === false){
                emailError.textContent = data.message;
            } else {
                emailError.textContent = '';
            }
        })
        .catch(error => {
            emailError.textContent = 'Ошибка при проверке email.';
        });
}