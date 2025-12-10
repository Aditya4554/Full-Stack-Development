const menuButton = document.getElementById("menu-button");
const navLinks = document.getElementById("nav-links");

// MOBILE MENU
menuButton.addEventListener("click", () => {
    navLinks.classList.toggle("open");
    menuButton.textContent = navLinks.classList.contains("open") ? "✕" : "☰";
});

// CONTACT FORM HANDLER
const form = document.getElementById("contact-form");
const formMsg = document.getElementById("form-msg");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    formMsg.textContent = "Thank you! Your message has been sent.";
    formMsg.style.color = "lightgreen";
    form.reset();
});
