<?php
session_start();

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF-токена
    if (empty($_POST['_token']) || $_POST['_token'] !== ($_SESSION['_token'] ?? '')) {
        die('Ошибка CSRF-токена');
    }

    // Валидация данных
    $errors = [];
    $required = ['last_name', 'first_name', 'specialty', 'experience'];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Поле " . ucfirst(str_replace('_', ' ', $field)) . " обязательно для заполнения";
        }
    }

    if (!is_numeric($_POST['experience']) || $_POST['experience'] < 0) {
        $errors[] = "Стаж должен быть положительным числом";
    }

    // Если нет ошибок - сохраняем
    if (empty($errors)) {
        $doctor = [
            'id' => uniqid(),
            'last_name' => htmlspecialchars($_POST['last_name']),
            'first_name' => htmlspecialchars($_POST['first_name']),
            'middle_name' => htmlspecialchars($_POST['middle_name'] ?? ''),
            'specialty' => htmlspecialchars($_POST['specialty']),
            'experience' => (int)$_POST['experience'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Путь к файлу
        $doctorsFile = __DIR__.'doctors-data.json';
        
        // Чтение и запись в файл
        $doctors = [];
        if (file_exists($doctorsFile)) {
            $currentData = file_get_contents($doctorsFile);
            $doctors = json_decode($currentData, true) ?: [];
        }
        
        $doctors[] = $doctor;
        file_put_contents($doctorsFile, json_encode($doctors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Перенаправление после успешного сохранения
        header('Location: /admin/doctors.php?success=1&last_added=' . urlencode($doctor['last_name']));
        exit;
    }
}

// Генерация CSRF-токена если нет
if (empty($_SESSION['_token'])) {
    $_SESSION['_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить врача</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .container { max-width: 600px; margin: 30px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; }
        .error { color: red; margin-top: 5px; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добавить нового врача</h1>
        
        <?php if (!empty($errors)): ?>
            <div style="color: red; margin-bottom: 15px;">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?>">
            
            <div class="form-group">
                <label>Фамилия *</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Имя *</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Отчество</label>
                <input type="text" name="middle_name" value="<?= htmlspecialchars($_POST['middle_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Специальность *</label>
                <input type="text" name="specialty" value="<?= htmlspecialchars($_POST['specialty'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Стаж (лет) *</label>
                <input type="number" name="experience" min="0" value="<?= htmlspecialchars($_POST['experience'] ?? '') ?>" required>
            </div>
            
            <button type="submit">Добавить врача</button>
            <a href="/admin/doctors" style="margin-left: 10px;">Отмена</a>
        </form>
    </div>
</body>
</html>