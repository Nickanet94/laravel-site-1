<?php

// Читаем данные о врачах
$doctorsFile = __DIR__.'/../../admindoctors-data.json';
$doctors = [];

if (file_exists($doctorsFile)) {
    $jsonData = file_get_contents($doctorsFile);
    $doctors = json_decode($jsonData, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Обработка ошибки JSON
        error_log("Ошибка парсинга JSON: " . json_last_error_msg());
    }
}
$jsonData = file_get_contents($doctorsFile);
    $doctors = json_decode($jsonData, true);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Наши врачи</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .doctors-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .doctor-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .doctor-name {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .doctor-specialty {
            color: #3498db;
            margin-bottom: 8px;
        }
        .doctor-experience {
            color: #7f8c8d;
        }
        .page-header {
            background: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body class="index">
    <header class="page-header">
        <h1>Наши специалисты</h1>
        <div class="registration-login">
            <button href="/login"><a href="/login">Вход</a></button>
            <button href="/registration"><a href="/register">Регистрация</a></button>
    </header>

    <main class="doctors-container">
        <?php if (!empty($doctors)): ?>
            <?php foreach ($doctors as $doctor): ?>
                <div class="doctor-card">
                    <div class="doctor-name">
                        <?= htmlspecialchars($doctor['last_name'] ?? '') ?> 
                        <?= htmlspecialchars($doctor['first_name'] ?? '') ?> 
                        <?= htmlspecialchars($doctor['middle_name'] ?? '') ?>
                    </div>
                    <div class="doctor-specialty">
                        Специальность: <?= htmlspecialchars($doctor['specialty'] ?? '') ?>
                    </div>
                    <div class="doctor-experience">
                        Стаж работы: <?= htmlspecialchars($doctor['experience'] ?? '0') ?> лет
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center;">
                <p>В данный момент нет информации о врачах</p>
            </div>
        <?php endif; ?>
    </main>

    <footer style="text-align: center; padding: 20px; background: #f8f9fa;">
        <p>© 2025 Медицинский центр. Все права защищены.</p>
    </footer>
</body>
</html>