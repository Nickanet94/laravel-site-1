<!DOCTYPE html>
<html>
<head>
    <title>Запись к врачу</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Запись к врачу</h1>
        
        <?php
        $doctors = json_decode(file_get_contents(storage_path('doctors-data.json')), true);
        $page = $_GET['page'] ?? 1;
        $perPage = 5;
        $totalPages = ceil(count($doctors) / $perPage);
        $currentDoctors = array_slice($doctors, ($page-1)*$perPage, $perPage);
        ?>
        
        <div class="doctors-list">
            <?php foreach ($currentDoctors as $doctor): ?>
            <div class="doctor-card">
                <h3><?= $doctor['last_name'] ?> <?= $doctor['first_name'] ?> <?= $doctor['middle_name'] ?></h3>
                <p>Специальность: <?= $doctor['specialty'] ?></p>
                <p>Стаж: <?= $doctor['experience'] ?> лет</p>
                
                <form class="appointment-form" method="POST" action="/user/make-appointment">
                    <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    
                    <div class="form-group">
                        <label>Дата:</label>
                        <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Время:</label>
                        <select name="time" required>
                            <?php 
                            $times = ['08:00', '09:00', '10:00', '11:00', '12:00', 
                                     '13:00', '14:00', '15:00', '16:00', '17:00'];
                            foreach ($times as $time): 
                            ?>
                                <option value="<?= $time ?>"><?= $time ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit">Записаться</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i == $page ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>