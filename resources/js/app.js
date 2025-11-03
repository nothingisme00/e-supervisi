import "./bootstrap";

// Close dropdown when clicking outside
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        const userMenu = document.getElementById("user-menu");
        const userMenuButton = document.getElementById("user-menu-button");

        if (userMenu && userMenuButton) {
            if (
                !userMenuButton.contains(event.target) &&
                !userMenu.contains(event.target)
            ) {
                userMenu.classList.add("hidden");
            }
        }
    });
});
