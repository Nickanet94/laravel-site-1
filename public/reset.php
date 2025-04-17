<?php
session_start(); // Добавляем старт сессии вручную
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Восстановление пароля</h1>
        
        <form id="reset-form" action="/reset" method="POST">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div id="reset-error" class="error-message" style="display: none;"></div>
            
            <button type="submit">Изменить пароль</button>
        </form>
        
        <div class="links">
            <a href="/login">Войти в существующий аккаунт</a> | 
            <a href="/register">Зарегистрироваться</a>
        </div>
    </div>
    
    <script src="/js/auth.js"></script>
</body>
</html>