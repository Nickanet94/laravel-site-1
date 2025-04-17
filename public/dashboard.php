<?php
session_start(); // Добавляем старт сессии вручную
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .dashboard {
            text-align: center;
            padding: 2rem;
        }
        .user-info {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="user-info">
            <h1>Добро пожаловать, <span id="username"></span>!</h1>
            <p>Вы успешно вошли в систему.</p>
            <form action="/logout" method="POST">
                <button type="submit" class="secondary-button">Выйти</button>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Получаем имя пользователя из URL или хранилища
            const urlParams = new URLSearchParams(window.location.search);
            const username = urlParams.get('user') || 'Пользователь';
            document.getElementById('username').textContent = username;
        });
    </script>
</body>
</html>