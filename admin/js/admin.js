document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('confirm_password');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordMismatch = document.getElementById('passwordMismatch');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    const passwordSuggestion = document.getElementById('passwordSuggestion');
    const phoneNumberField = document.getElementById('phone_number');
    const phoneError = document.getElementById('phoneError');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('#eyeIcon').classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmField.setAttribute('type', type);
        this.querySelector('#eyeIconConfirm').classList.toggle('fa-eye-slash');
    });

    confirmField.addEventListener('input', function () {
        const password = passwordField.value;
        const confirm = confirmField.value;
        if (password !== confirm) {
            passwordMismatch.style.display = 'block';
        } else {
            passwordMismatch.style.display = 'none';
        }
    });

    passwordField.addEventListener('input', function () {
        const password = passwordField.value;
        const result = zxcvbn(password);

        switch (result.score) {
            case 0:
                passwordStrengthText.textContent = "Very Weak";
                passwordStrength.style.backgroundColor = "#ff6666";
                passwordStrengthText.style.color = "#ff6666";
                break;
            case 1:
                passwordStrengthText.textContent = "Weak";
                passwordStrength.style.backgroundColor = "#ffcc66";
                passwordStrengthText.style.color = "#ffcc66";
                break;
            case 2:
                passwordStrengthText.textContent = "Fair";
                passwordStrength.style.backgroundColor = "#ffff66";
                passwordStrengthText.style.color = "#ffff66";
                break;
            case 3:
                passwordStrengthText.textContent = "Strong";
                passwordStrength.style.backgroundColor = "#99ff99";
                passwordStrengthText.style.color = "#99ff99";
                break;
            case 4:
                passwordStrengthText.textContent = "Very Strong";
                passwordStrength.style.backgroundColor = "#66ff66";
                passwordStrengthText.style.color = "#66ff66";
                break;
            default:
                passwordStrengthText.textContent = "";
                passwordStrength.style.backgroundColor = "";
                break;
        }

        if (result.feedback.suggestions.length > 0) {
            passwordSuggestion.textContent = result.feedback.suggestions.join(' ');
            passwordSuggestion.style.color = "#3366ff";
            passwordSuggestion.style.display = 'block';
        } else {
            passwordSuggestion.style.display = 'none';
        }

        const confirm = confirmField.value;
        if (password === confirm) {
            passwordMismatch.style.display = 'none';
        }
    });

    phoneNumberField.addEventListener('input', function () {
        const phoneNumber = phoneNumberField.value;

        if (phoneNumber.length !== 10) {
            phoneError.textContent = "Phone number should be 10 digits long.";
            phoneError.style.display = 'block';
            return;
        }

        if (!/^\d{10}$/.test(phoneNumber)) {
            phoneError.textContent = "Phone number should contain only digits.";
            phoneError.style.display = 'block';
            return;
        }
        phoneError.style.display = 'none';
    });
});
