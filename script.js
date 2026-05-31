const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

if (togglePassword && password) {
  togglePassword.addEventListener("click", () => {
    const isHidden = password.type === "password";
    password.type = isHidden ? "text" : "password";
    togglePassword.textContent = isHidden ? "Hide" : "Show";
  });
}
