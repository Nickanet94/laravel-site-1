document.addEventListener("DOMContentLoaded", function () {
    // Общие функции для всех страниц
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get("error");

    if (error) {
        handleErrors(error);
    }

    // Обработка формы входа
    const loginForm = document.getElementById("login-form");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            if (!validateInput(username) || !validateInput(password)) {
                showError(
                    "login-error",
                    "Только английские буквы и цифры разрешены"
                );
                return;
            }

            this.submit();
        });
    }

    // Обработка формы регистрации
    const registerForm = document.getElementById("register-form");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            if (!validateInput(username) || !validateInput(password)) {
                showError(
                    "register-error",
                    "Только английские буквы и цифры разрешены"
                );
                return;
            }

            this.submit();
        });
    }

    // Обработка формы сброса пароля
    const resetForm = document.getElementById("reset-form");
    if (resetForm) {
        resetForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            if (!validateInput(username) || !validateInput(password)) {
                showError(
                    "reset-error",
                    "Только английские буквы и цифры разрешены"
                );
                return;
            }

            this.submit();
        });
    }
});

function validateInput(input) {
    return /^[a-zA-Z0-9]+$/.test(input);
}

function handleErrors(error) {
    switch (error) {
        case "invalid_credentials":
            showError("login-error", "Неверный логин или пароль");
            break;
        case "invalid_chars":
            showError(
                "register-error",
                "Только английские буквы и цифры разрешены"
            );
            break;
        case "user_exists":
            showError("register-error", "Такой пользователь уже существует");
            document.getElementById("username").classList.add("error");
            break;
        case "user_not_found":
            showError("reset-error", "Пользователь не найден");
            document.getElementById("username").classList.add("error");
            break;
    }
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = "block";
    }
}
