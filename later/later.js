const codeInput = document.getElementById('code-input');
const submitButton = document.getElementById('submit-code');
const buttonsContainer = document.getElementById('buttons-container');
const errorMessage = document.getElementById('error-message');

function checkCode() {
    const code = codeInput.value;

    if (code === 'StarLezy') {
        buttonsContainer.style.display = 'block';
        errorMessage.style.display = 'none';
    } else {
        buttonsContainer.style.display = 'none';
        errorMessage.style.display = 'block';
    }
}

submitButton.addEventListener('click', checkCode);

codeInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        checkCode();
    }
});