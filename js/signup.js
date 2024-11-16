signupForm.addEventListener("submit", function (event) {
    let isValid = true;
  
    // Clear previous error messages
    document.getElementById("nameError").textContent = "";
    document.getElementById("surnameError").textContent = "";
    document.getElementById("emailError").textContent = "";
    document.getElementById("phoneError").textContent = "";
    passwordMatch.textContent = "";
  
    // Validate name
    const nameInput = document.getElementById("name");
    const name = nameInput.value.trim();
    if (name === "") {
      document.getElementById("nameError").textContent =
        "First name is required.";
      isValid = false;
    } else if (/[^a-zA-Z\s]/.test(name)) {
      document.getElementById("nameError").textContent =
        "First name contains illegal characters.";
      isValid = false;
    }
  
    // Validate surname
    const surnameInput = document.getElementById("surname");
    const surname = surnameInput.value.trim();
    if (surname === "") {
      document.getElementById("surnameError").textContent =
        "Last name is required.";
      isValid = false;
    } else if (/[^a-zA-Z\s]/.test(surname)) {
      document.getElementById("surnameError").textContent =
        "Last name contains illegal characters.";
      isValid = false;
    }
  
    // Validate email
    const emailInput = document.getElementById("email");
    const email = emailInput.value;
    const isEmailValid = validator.isEmail(email);
    if (!isEmailValid) {
      document.getElementById("emailError").textContent =
        "Please enter a valid email address.";
      isValid = false;
    }
  
    // Validate phone number
    const phoneInput = document.getElementById("phone");
    const phone = phoneInput.value.trim();
    if (!/^\d{10}$/.test(phone)) {
      document.getElementById("phoneError").textContent =
        "Phone number must be exactly 10 digits and contain only numbers.";
      isValid = false;
    } else {
      document.getElementById("phoneError").textContent = ""; // Clear error if valid
    }
  
    // Check if passwords match
    const password = passwordField.value;
    const confirmPassword = confirmPasswordField.value;
  
    if (password !== confirmPassword) {
      passwordMatch.textContent = "Passwords do not match.";
      isValid = false;
    }
  
    if (!isValid) {
      event.preventDefault(); // Prevent form submission if invalid
    }
  });
  