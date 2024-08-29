// Define element IDs as variables
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
const confirmPassword = document.getElementById("confirm_password");
const registrationForm = document.getElementById("registrationForm");
const email = document.getElementById("email");
const emailError = document.getElementById("emailError");
const name = document.getElementById("name");
const nameError = document.getElementById("nameError");
const phoneNumber = document.getElementById("phone_number");
const phoneNumberError = document.getElementById("phoneNumberError");
const passwordError = document.getElementById("passwordError");
const confirmPasswordError = document.getElementById("confirmPasswordError");
const passwordStrength = document.getElementById("passwordStrength");
const role = document.getElementById("role");
const roleError = document.getElementById("roleError");

// Password visibility toggle
togglePassword.addEventListener("click", function () {
  const type = password.getAttribute("type") === "password" ? "text" : "password";
  password.setAttribute("type", type);
  this.classList.toggle("fa-eye");
  this.classList.toggle("fa-eye-slash");
});

toggleConfirmPassword.addEventListener("click", function () {
  const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
  confirmPassword.setAttribute("type", type);
  this.classList.toggle("fa-eye");
  this.classList.toggle("fa-eye-slash");
});

// Password strength and validation
password.addEventListener("input", function () {
  const passwordValue = password.value;
  const passwordResult = zxcvbn(passwordValue);

  let strengthText = "";
  let strengthColor = "";

  switch (passwordResult.score) {
    case 0:
    case 1:
      strengthText = "Very Weak";
      strengthColor = "red";
      break;
    case 2:
      strengthText = "Weak";
      strengthColor = "orange";
      break;
    case 3:
      strengthText = "Moderate";
      strengthColor = "yellowgreen";
      break;
    case 4:
      strengthText = "Strong";
      strengthColor = "green";
      break;
  }

  passwordStrength.textContent = `Password Strength: ${strengthText}`;
  passwordStrength.style.color = strengthColor;
});


confirmPassword.addEventListener("input", function () {
  const confirmPasswordValue = confirmPassword.value;
  const passwordValue = password.value;

  if (confirmPasswordValue !== passwordValue) {
    confirmPasswordError.textContent = "Passwords do not match";
    confirmPasswordError.style.color = "red";
  } else {
    confirmPasswordError.textContent = ""; // Clear any previous error messages
  }
});

// Form submission
registrationForm.addEventListener("submit", function (event) {
  let valid = true;

  // Email validation
  const emailValue = email.value;
  if (!validator.isEmail(emailValue)) {
    emailError.textContent = "Invalid email address";
    valid = false;
  } else {
    emailError.textContent = "";
  }

  // Name validation
  const nameValue = name.value;
  if (/[^a-zA-Z\s]/.test(nameValue)) {
    nameError.textContent = "Name contains illegal characters";
    valid = false;
  } else if (nameValue.trim() === "") {
    nameError.textContent = "Name is required";
    valid = false;
  } else {
    nameError.textContent = "";
  }

  // Phone number validation
  const phoneNumberValue = phoneNumber.value;
  const phoneNumberRegex = /^(?:\+27|0)[6-9]\d{8}$/;
  if (!phoneNumberRegex.test(phoneNumberValue)) {
    phoneNumberError.textContent = "Invalid phone number";
    valid = false;
  } else {
    phoneNumberError.textContent = "";
  }

  // Password strength validation
  const passwordValue = password.value;
  const passwordResult = zxcvbn(passwordValue);
  if (passwordResult.score < 3) {
    passwordError.textContent = "Password is too weak. Try using a mix of letters, numbers, and special characters.";
    valid = false;
  } else {
    passwordError.textContent = "";
  }

  // Confirm password validation
  const confirmPasswordValue = confirmPassword.value;
  if (confirmPasswordValue !== passwordValue) {
    confirmPasswordError.textContent = "Passwords do not match";
    confirmPasswordError.style.color = "red";
    valid = false;
  } else {
    confirmPasswordError.textContent = "";
  }

  // Role validation
  const roleValue = role.value;
  if (!roleValue) {
    roleError.textContent = "Please select a role";
    valid = false;
  } else {
    roleError.textContent = "";
  }

  if (!valid) {
    event.preventDefault(); // Prevent form submission if validation fails
  }
});

