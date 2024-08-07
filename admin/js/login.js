document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("togglePassword")
    .addEventListener("click", function () {
      const passwordInput = document.getElementById("password");
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });

  document.getElementById("loginForm").addEventListener("submit", function (e) {
    let isValid = true;

    // Clear previous error messages
    document.getElementById("emailError").textContent = "";
    document.getElementById("passwordError").textContent = "";

    // Validate email
    const email = document.getElementById("email").value;
    if (!validator.isEmail(email)) {
      document.getElementById("emailError").textContent =
        "Invalid email format.";
      isValid = false;
    }

    // Validate password
    const password = document.getElementById("password").value;
    if (password.trim() === "") {
      document.getElementById("passwordError").textContent =
        "Password is required.";
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault(); // Prevent form submission if validation fails
    }
  });
});
