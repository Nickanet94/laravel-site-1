<?php
session_start(); // Добавляем старт сессии вручную
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Вход</h1>
        
        <form id="login-form" action="/login" method="POST">
            <input type="hidden" name="_token" value="TEMPORARY_DISABLE_CSRF">
            
            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div id="login-error" class="error-message" style="display: none;"></div>
            
            <button type="submit">Войти</button>
        </form>
        
        <div class="links">
            <a href="/register">Регистрация</a> | 
            <a href="/reset">Забыли пароль?</a>
        </div>
    </div>
    
    <script src="/js/auth.js"></script>
</body>
</html>