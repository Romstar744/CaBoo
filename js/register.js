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

    // Обработчик изменения аватара (для соискателя)
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview').querySelector('img');
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                avatarPreview.src = '#';
                avatarPreview.style.display = 'none';
            }
        });
    }

    // Обработчик изменения логотипа компании (для работодателя)
    const companyLogoInput = document.getElementById('companyLogo');
    const companyLogoPreview = document.getElementById('companyLogoPreview').querySelector('img');
    if (companyLogoInput && companyLogoPreview) {
        companyLogoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    companyLogoPreview.src = e.target.result;
                    companyLogoPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                companyLogoPreview.src = '#';
                companyLogoPreview.style.display = 'none';
            }
        });
    }

    const roleSeekerRadio = document.getElementById('seekerRole');
    const roleEmployerRadio = document.getElementById('employerRole');
    const seekerFields = document.querySelector('.seeker-fields');
    const employerFields = document.querySelector('.employer-fields');

    // Функция для отображения/скрытия полей в зависимости от выбранной роли
    function toggleFields() {
        if (roleSeekerRadio.checked) {
            seekerFields.classList.remove('hidden');
            employerFields.classList.add('hidden');
        } else if (roleEmployerRadio.checked) {
            seekerFields.classList.add('hidden');
            employerFields.classList.remove('hidden');
        }
    }

    // Вызываем функцию toggleFields при загрузке страницы и при изменении выбранной роли
    toggleFields();
 // Добавляем обработчики событий для radio button с ролью
 roleSeekerRadio.addEventListener('change', toggleFields);
 roleEmployerRadio.addEventListener('change', toggleFields);

 // Устанавливаем значения по умолчанию для полей с датами
 const birthdateInput = document.getElementById('birthdate');
 const educationStartInput = document.getElementById('educationStart');
 const educationEndInput = document.getElementById('educationEnd');

 // Функция для получения текущей даты в формате YYYY-MM-DD
 function getDefaultDate() {
  const today = new Date();
  const year = today.getFullYear();
  let month = today.getMonth() + 1; // Месяцы начинаются с 0
  month = month < 10 ? '0' + month : month; // Добавляем 0, если месяц < 10
  let day = today.getDate();
  day = day < 10 ? '0' + day : day; // Добавляем 0, если день < 10
  return `${year}-${month}-${day}`;
 }

 // Устанавливаем значения по умолчанию
 if (birthdateInput) {
  birthdateInput.value = getDefaultDate();
 }
 if (educationStartInput) {
  educationStartInput.value = '2000-01';  // Начало 2000 года
 }

 if (educationEndInput) {
  educationEndInput.value = '2004-01';  // Конец 2004 года
 }

    // Фокус и прокрутка до первого невалидного поля при отправке
    const registerForm = document.querySelector('.register-form');
    if(registerForm){
        registerForm.addEventListener('submit', function(event) {
            let isValid = true;

            // Общая валидация для обязательных полей (логин, email, пароль)
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
          // Валидация для соискателя
          if (roleSeekerRadio.checked) {
           const seekerInputs = seekerFields.querySelectorAll('input, textarea, select');
           seekerInputs.forEach(input => {
             // Проверяем, является ли поле обязательным ИЛИ было заполнено ранее
            if ((input.required || input.value.trim() !== '') && !input.value.trim()) {
             isValid = false;
             input.classList.add('invalid');
             const errorId = input.id + 'Error';
             const errorElement = document.getElementById(errorId);
             if (errorElement) {
              errorElement.textContent = 'Пожалуйста, заполните это поле.';
             }
            } else {
             input.classList.remove('invalid');
             const errorId = input.id + 'Error';
             const errorElement = document.getElementById(errorId);
             if (errorElement) {
              errorElement.textContent = '';
             }
            }
           });
          }

          // Валидация для работодателя
          if (roleEmployerRadio.checked) {
           const employerInputs = employerFields.querySelectorAll('input, textarea, select');
           employerInputs.forEach(input => {
             // Проверяем, является ли поле обязательным ИЛИ было заполнено ранее
             if ((input.required || input.value.trim() !== '') && !input.value.trim()) {
              isValid = false;
              input.classList.add('invalid');
              const errorId = input.id + 'Error';
              const errorElement = document.getElementById(errorId);
              if (errorElement) {
               errorElement.textContent = 'Пожалуйста, заполните это поле.';
              }
             } else {
              input.classList.remove('invalid');
              const errorId = input.id + 'Error';
              const errorElement = document.getElementById(errorId);
              if (errorElement) {
               errorElement.textContent = '';
              }
             }
           });
          }

            if (!isValid) {
                event.preventDefault(); // Предотвращаем отправку
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