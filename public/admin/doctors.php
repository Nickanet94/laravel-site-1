<?php
session_start();


// Чтение данных о врачах
$doctorsFile = storage_path('C:\Users\heroc\clinic_manager\public\admindoctors-data.json');
$doctors = file_get_contents($doctorsFile);

// Проверка успешного добавления
$successMessage = '';
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = 'Врач успешно добавлен!';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список врачей</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .success-message { 
            background-color: #dff0d8; 
            color: #3c763d; 
            padding: 10px; 
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .action-buttons a { 
            display: inline-block; 
            margin-right: 5px; 
            padding: 5px 10px; 
            text-decoration: none;
            border-radius: 3px;
        }
        .add-btn { background: #5cb85c; color: white; }
        .edit-btn { background: #337ab7; color: white; }
        .delete-btn { background: #d9534f; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Список врачей</h1>
        <?php echo "<script>console.log('123{$doctors}' );</script>"; ?> 
        <?php if ($successMessage): ?>
            <div class="success-message"><?= $successMessage ?></div>   
        <?php endif; ?>
        
        <div style="margin-bottom: 20px;">
            <a href="/admin/add-doctor.php" class="add-btn">Добавить врача</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Специальность</th>
                    <th>Стаж (лет)</th>
                    <th>Дата добавления</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctors as $doctor): ?>
                <tr>
                    <td><?= htmlspecialchars($doctor['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($doctor['last_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($doctor['first_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($doctor['middle_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($doctor['specialty'] ?? '') ?></td>
                    <td><?= htmlspecialchars($doctor['experience'] ?? '') ?></td>
                    <td><?= htmlspecialchars($doctor['created_at'] ?? '') ?></td>
                    <td class="action-buttons">
                        <a href="/admin/edit-doctor.php?id=<?= $doctor['id'] ?>" class="edit-btn">Редактировать</a>
                        <a href="/admin/delete-doctor.php?id=<?= $doctor['id'] ?>" class="delete-btn">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
