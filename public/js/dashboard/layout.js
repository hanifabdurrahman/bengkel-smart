document.addEventListener("DOMContentLoaded", () => {
    // =====================
    // SIDEBAR TOGGLE
    // =====================
    window.toggleSidebar = function () {
        document.getElementById("sidebar")?.classList.toggle("active");
        document.getElementById("sidebarOverlay")?.classList.toggle("active");
    };

    // =====================
    // DARK MODE
    // =====================
    const themeIcon = document.getElementById("themeIcon");
    const root = document.documentElement;

    const setIcon = (theme) => {
        if (!themeIcon) return;

        if (theme === "dark") {
            themeIcon.classList.remove("bi-moon-stars");
            themeIcon.classList.add("bi-sun-fill");
        } else {
            themeIcon.classList.remove("bi-sun-fill");
            themeIcon.classList.add("bi-moon-stars");
        }
    };

    const storedTheme = localStorage.getItem("theme");

    if (storedTheme) {
        root.setAttribute("data-bs-theme", storedTheme);
        setIcon(storedTheme);
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        root.setAttribute("data-bs-theme", "dark");
        setIcon("dark");
    }

    window.toggleDarkMode = function () {
        const currentTheme = root.getAttribute("data-bs-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        root.setAttribute("data-bs-theme", newTheme);
        localStorage.setItem("theme", newTheme);
        setIcon(newTheme);
    };
});
