document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const emailField = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const form = document.getElementById('loginForm');

    // Toggle password visibility on click
    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Validate form on submit
    form.addEventListener('submit', function (event) {
        let valid = true;

        // Clear previous errors
        emailError.textContent = '';
        passwordError.textContent = '';
        emailField.classList.remove('is-invalid');
        passwordField.classList.remove('is-invalid');

        // Check email
        const emailValue = emailField.value;
        if (!emailValue || !validator.isEmail(emailValue)) {
            emailError.textContent = 'Please enter a valid email address.';
            emailField.classList.add('is-invalid');
            valid = false;
        }

        // Check password
        const passwordValue = passwordField.value;
        if (!passwordValue.trim()) {
            passwordError.textContent = 'Password is required.';
            passwordField.classList.add('is-invalid');
            valid = false;
        }

        if (!valid) {
            event.preventDefault(); // Prevent form submission
            focusInvalidField();
        }
    });

    // Function to focus on the first invalid field
    function focusInvalidField() {
        // Select all elements with the class 'is-invalid'
        const invalidFields = document.querySelectorAll('.is-invalid');
        
        // Check if there are any invalid fields
        if (invalidFields.length > 0) {
            // Focus on the first invalid field
            invalidFields[0].focus();
        }
    }
    
});
