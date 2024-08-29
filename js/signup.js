document.addEventListener('DOMContentLoaded', function () {
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

    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        eyeIconConfirm.classList.toggle('fa-eye');
        eyeIconConfirm.classList.toggle('fa-eye-slash');
    });

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

    confirmPasswordField.addEventListener('input', function () {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        if (password !== confirmPassword) {
            passwordMatch.textContent = 'Passwords do not match.';
        } else {
            passwordMatch.textContent = '';
        }
    });

    const signupForm = document.getElementById('signupForm');
    signupForm.addEventListener('submit', function (event) {
        let isValid = true;

        // Clear previous error messages
        document.getElementById('nameError').textContent = '';
        document.getElementById('surnameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('phoneError').textContent = '';
        passwordMatch.textContent = '';

        // Validate name
        const nameInput = document.getElementById('name');
        const name = nameInput.value.trim();
        console.log('Name:', name); // Debug output
        if (name === '') {
            document.getElementById('nameError').textContent = 'First name is required.';
            isValid = false;
        } else if (/[^a-zA-Z\s]/.test(name)) {
            document.getElementById('nameError').textContent = 'First name contains illegal characters.';
            isValid = false;
        }

        // Validate surname
        const surnameInput = document.getElementById('surname');
        const surname = surnameInput.value.trim();
        console.log('Surname:', surname); // Debug output
        if (surname === '') {
            document.getElementById('surnameError').textContent = 'Last name is required.';
            isValid = false;
        } else if (/[^a-zA-Z\s]/.test(surname)) {
            document.getElementById('surnameError').textContent = 'Last name contains illegal characters.';
            isValid = false;
        }

        // Validate email
        const emailInput = document.getElementById('email');
        const email = emailInput.value;
        console.log('Email:', email); // Debug output
        const isEmailValid = validator.isEmail(email);
        if (!isEmailValid) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        }

        // Validate phone
        const phoneInput = document.getElementById('phone');
        const phone = phoneInput.value;
        console.log('Phone:', phone); // Debug output
        if (!phone.match(/^\+?[0-9]{10,}$/)) {
            document.getElementById('phoneError').textContent = 'Please enter a valid phone number.';
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
