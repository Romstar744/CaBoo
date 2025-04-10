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
        } else {
            checkEmailAvailability(this.value);
            emailInput.classList.remove('invalid');
        }
    });

    const usernameInput = document.getElementById('username');
    usernameInput.addEventListener('input', function () {
        checkUsernameAvailability(this.value);
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