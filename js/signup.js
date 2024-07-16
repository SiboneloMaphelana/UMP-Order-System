// Wait for the DOM to load before executing the script
document.addEventListener('DOMContentLoaded', function () {
    // Get references to all the input fields
    const firstNameField = document.getElementById('name');
    const lastNameField = document.getElementById('surname');
    const emailField = document.getElementById('email');
    const registrationNumberField = document.getElementById('registration_number');
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('confirmPassword');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordMismatch = document.getElementById('passwordMismatchMessage');
    const passwordStrength = document.getElementById('passwordStrengthIndicator');
    const passwordSuggestion = document.getElementById('passwordSuggestions');
    const passwordStrengthText = document.createElement('div'); // Makes password strength text appear
    passwordStrength.appendChild(passwordStrengthText);

    // Toggle password visibility on click
    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('#eyeIcon').classList.toggle('fa-eye-slash');
    });

    // Toggle confirm password visibility on click
    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmField.setAttribute('type', type);
        this.querySelector('#eyeIconConfirm').classList.toggle('fa-eye-slash');
    });

    // Check if the password and confirm password match
    function checkPasswordMatch() {
        const password = passwordField.value;
        const confirm = confirmField.value;
        if (password !== confirm) {
            passwordMismatch.style.display = 'block';
        } else {
            passwordMismatch.style.display = 'none';
        }
    }

    // Add event listeners to check password match on input
    confirmField.addEventListener('input', checkPasswordMatch);
    passwordField.addEventListener('input', checkPasswordMatch);

    // Check password strength and display suggestions
    passwordField.addEventListener('input', function () {
        const password = passwordField.value;
        const result = zxcvbn(password);
        const score = result.score;

        // Display password strength
        const strengthLabels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        passwordStrengthText.textContent = `Strength: ${strengthLabels[score]}`;

        // Display suggestions
        const suggestions = result.feedback.suggestions;
        passwordSuggestion.innerHTML = '';
        suggestions.forEach(function (suggestion) {
            const suggestionItem = document.createElement('li');
            suggestionItem.textContent = suggestion;
            passwordSuggestion.appendChild(suggestionItem);
        });
    });

    // Add submit event listener to the form to validate before submission
    const signupForm = document.getElementById('signupForm');
    signupForm.addEventListener('submit', function (event) {
        const password = passwordField.value;
        const confirm = confirmField.value;

        if (password !== confirm) {
            passwordMismatch.style.display = 'block';
            event.preventDefault(); // Prevent form submission
        } else {
            passwordMismatch.style.display = 'none';
        }
    });
});
