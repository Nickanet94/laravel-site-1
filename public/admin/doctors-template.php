<!DOCTYPE html>
<html>
<head>
    <title>Управление врачами</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Список врачей</h1>
        
        <?php foreach ($doctors as $doctor): ?>
        <div class="doctor-card">
            <h3><?= htmlspecialchars($doctor['last_name']) ?> <?= htmlspecialchars($doctor['first_name']) ?></h3>
            <p>Специальность: <?= htmlspecialchars($doctor['specialty']) ?></p>
            <p>Стаж: <?= htmlspecialchars($doctor['experience']) ?> лет</p>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>