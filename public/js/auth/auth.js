document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".container");
    const registerLink = document.querySelector(".register-link");
    const loginLink = document.querySelector(".login-link");

    // === LIGHT / DARK MODE ===
    const themeToggle = document.getElementById("themeToggle");
    const themeIcon = themeToggle?.querySelector("i");
    const body = document.body;

    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "light") {
        body.classList.add("light-mode");
        themeIcon?.classList.replace("bx-moon", "bx-sun");
    }

    themeToggle?.addEventListener("click", () => {
        body.classList.toggle("light-mode");

        if (body.classList.contains("light-mode")) {
            themeIcon.classList.replace("bx-moon", "bx-sun");
            localStorage.setItem("theme", "light");
        } else {
            themeIcon.classList.replace("bx-sun", "bx-moon");
            localStorage.setItem("theme", "dark");
        }
    });

    // === REGISTER / LOGIN SWITCH ===
    if (registerLink) {
        registerLink.addEventListener("click", (e) => {
            e.preventDefault();
            container.classList.add("active");
            window.history.pushState({}, "", AUTH_CONFIG.registerUrl);
            document.title = "Register";
        });
    }

    if (loginLink) {
        loginLink.addEventListener("click", (e) => {
            e.preventDefault();
            container.classList.remove("active");
            window.history.pushState({}, "", AUTH_CONFIG.loginUrl);
            document.title = "Login";
        });
    }

    // === PASSWORD TOGGLE ===
    function setupPasswordToggle(toggleId, inputId) {
        const toggleIcon = document.getElementById(toggleId);
        const passwordInput = document.getElementById(inputId);

        if (toggleIcon && passwordInput) {
            toggleIcon.addEventListener("click", () => {
                const type =
                    passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                toggleIcon.classList.toggle("bx-hide");
                toggleIcon.classList.toggle("bx-show");
            });
        }
    }

    setupPasswordToggle("togglePassword", "passwordInput");
    setupPasswordToggle("togglePasswordReg", "passwordReg");
    setupPasswordToggle("togglePasswordConfirm", "passwordConfirm");

    // === HANDLE BACK BUTTON ===
    window.onpopstate = () => {
        if (window.location.pathname.includes("register")) {
            container.classList.add("active");
            document.title = "Register";
        } else {
            container.classList.remove("active");
            document.title = "Login";
        }
    };

    // === HANDLE VALIDATION ERROR ===
    if (
        AUTH_CONFIG.hasErrors &&
        (AUTH_CONFIG.isRegisterOld || AUTH_CONFIG.action === "register")
    ) {
        container.classList.add("active");
        document.title = "Register";
    }
});
