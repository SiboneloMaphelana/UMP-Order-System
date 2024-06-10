document.addEventListener('DOMContentLoaded', function () {
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

    function checkPasswordMatch() {
        const password = passwordField.value;
        const confirm = confirmField.value;
        if (password !== confirm) {
            passwordMismatch.style.display = 'block';
        } else {
            passwordMismatch.style.display = 'none';
        }
    }

    confirmField.addEventListener('input', checkPasswordMatch);
    passwordField.addEventListener('input', checkPasswordMatch);

    passwordField.addEventListener('input', function () {
        const password = passwordField.value;
        const result = zxcvbn(password);

        switch (result.score) {
            case 0:
                passwordStrengthText.textContent = "Very Weak";
                passwordStrength.style.backgroundColor = "#ff6666";
                break;
            case 1:
                passwordStrengthText.textContent = "Weak";
                passwordStrength.style.backgroundColor = "#ffcc66";
                break;
            case 2:
                passwordStrengthText.textContent = "Fair";
                passwordStrength.style.backgroundColor = "#ffff66";
                break;
            case 3:
                passwordStrengthText.textContent = "Strong";
                passwordStrength.style.backgroundColor = "#99ff99";
                break;
            case 4:
                passwordStrengthText.textContent = "Very Strong";
                passwordStrength.style.backgroundColor = "#66ff66";
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
    });

     // Validation functions
    function validateFirstName() {
        const firstName = firstNameField.value.trim();
        const nameRegex = /^[A-Za-z]+(?:[ '-][A-Za-z]+)*$/;
    
        // Check if the field is empty
        if (firstName === '') {
            console.error('First name is required');
            firstNameField.classList.add('is-invalid');
            return false;
        }
        // Check if the name contains any invalid characters
        else if (!nameRegex.test(firstName)) {
            console.error('First name contains invalid characters');
            firstNameField.classList.add('is-invalid');
            return false;
        }
        
        // If all checks pass, remove the invalid class and return true
        firstNameField.classList.remove('is-invalid');
        return true;
    }
    
    function validateLastName() {
        const lastName = lastNameField.value.trim();
        const nameRegex = /^[A-Za-z]+(?:[ '-][A-Za-z]+)*$/; // Adjust regex as needed
        
        // Check if the field is empty
        if (lastName === '') {
            lastNameField.classList.add('is-invalid'); // Add a red border to the input field
            console.error('Last name is required');
            return false;
        }
        // Check if the name contains any invalid characters
        else if (!nameRegex.test(lastName)) {
            lastNameField.classList.add('is-invalid'); // Add a red border to the input field
            console.error('Last name contains invalid characters');
            return false;
        }
         
        // If all checks pass, remove the invalid class and return true
        lastNameField.classList.remove('is-invalid');
        return true;
    }

    
    function validateEmail() {
        const email = emailField.value.trim();
        // Regular expression for email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            console.error('Invalid email format');
            return false;
        }
        return true;
    }
    
    function validateRegistrationNumber() {
        const registrationNumber = registrationNumberField.value.trim();
        if (registrationNumber === '') {
            console.error('Registration number is required');
            return false;
        }
        // Check if the registration number is between 1 and 15 characters long
        else if (registrationNumber.length < 1 || registrationNumber.length > 15) {
            console.error('Registration number must be between 1 and 15 characters long');
            return false;
        }
        // Check if the registration number contains only numbers
        else if (!/^\d+$/.test(registrationNumber)) {
            console.error('Registration number must contain only numbers');
            return false;
        }
        return true;
    }
    
    // Event listeners for input fields
    firstNameField.addEventListener('input', validateFirstName);
    lastNameField.addEventListener('input', validateLastName);
    emailField.addEventListener('input', validateEmail);
    registrationNumberField.addEventListener('input', validateRegistrationNumber);
});
