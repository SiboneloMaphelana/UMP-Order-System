document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

    // Toggle confirm password visibility
    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        eyeIconConfirm.classList.toggle('fa-eye');
        eyeIconConfirm.classList.toggle('fa-eye-slash');
    });

    // Password strength check with zxcvbn
    passwordField.addEventListener('input', function () {
        const password = passwordField.value;
        const result = zxcvbn(password);
        const strength = result.score;
        let message = '';
        let color = '';

        switch (strength) {
            case 0:
                message = 'Very Weak';
                color = 'red';
                break;
            case 1:
                message = 'Weak';
                color = 'orange';
                break;
            case 2:
                message = 'Moderate';
                color = 'yellow';
                break;
            case 3:
                message = 'Strong';
                color = 'lightgreen';
                break;
            case 4:
                message = 'Very Strong';
                color = 'green';
                break;
        }

        passwordStrength.textContent = `Password strength: ${message}`;
        passwordStrength.style.color = color;
    });

    // Check if passwords match
    confirmPasswordField.addEventListener('input', function () {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        if (password !== confirmPassword) {
            passwordMatch.textContent = 'Passwords do not match.';
        } else {
            passwordMatch.textContent = '';
        }
    });

    // Form validation
    const signupForm = document.getElementById('signupForm');
    signupForm.addEventListener('submit', function (event) {
        let isValid = true;

        // Clear previous error messages
        document.getElementById('nameError').textContent = '';
        document.getElementById('surnameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('roleError').textContent = '';
        document.getElementById('phoneError').textContent = '';
        passwordMatch.textContent = '';

        // Validate name
        const nameInput = document.getElementById('name');
        const name = nameInput.value.trim();
        if (name === '') {
            document.getElementById('nameError').textContent = 'First name is required.';
            isValid = false;
        } else if (/[^a-zA-Z\s]/.test(name)) {  // Check for illegal characters
            document.getElementById('nameError').textContent = 'First name contains illegal characters.';
            isValid = false;
        }

        // Validate surname
        const surnameInput = document.getElementById('surname');
        const surname = surnameInput.value.trim();
        if (surname === '') {
            document.getElementById('surnameError').textContent = 'Last name is required.';
            isValid = false;
        } else if (/[^a-zA-Z\s]/.test(surname)) {  // Check for illegal characters
            document.getElementById('surnameError').textContent = 'Last name contains illegal characters.';
            isValid = false;
        }

        // Validate email
        const emailInput = document.getElementById('email');
        const email = emailInput.value;
        const isEmailValid = validator.isEmail(email);
        if (!isEmailValid) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        }

        // Validate phone
        const phoneInput = document.getElementById('phone');
        const phone = phoneInput.value;
        const countryCode = phoneInput.dataset.countryCode || 'ZA';  // Default to South Africa
        if (!phone.match(/^\+?[0-9]{10,}$/)) {  // Basic validation for phone numbers
            document.getElementById('phoneError').textContent = 'Please enter a valid phone number.';
            isValid = false;
        }

        // Validate role
        const roleInput = document.getElementById('role');
        if (roleInput.value === '' || roleInput.value === 'Select Role') {
            document.getElementById('roleError').textContent = 'Please select a role.';
            isValid = false;
        }

        // Check if passwords match
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        if (password !== confirmPassword) {
            passwordMatch.textContent = 'Passwords do not match.';
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if invalid
        }
    });
});
