<?php
session_start(); // Добавляем старт сессии вручную
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Регистрация</h1>
        
        <form id="register-form" action="/register" method="POST">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        
            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div id="register-error" class="error-message" style="display: none;"></div>
            
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <div class="links">
            <a href="/login">Войти в существующий аккаунт</a>
        </div>
    </div>
    
    <script src="/js/auth.js"></script>
</body>
</html>