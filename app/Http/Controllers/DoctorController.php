<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DoctorController extends Controller
{
    private $doctorsFile;
    private $recordedFile;

    public function __construct()
    {
        $this->doctorsFile = storage_path('admindoctors-data.json');
        $this->recordedFile = storage_path('recorded-users.json');
        
        if (!file_exists($this->doctorsFile)) {
            file_put_contents($this->doctorsFile, json_encode([]));
        }
        
        if (!file_exists($this->recordedFile)) {
            file_put_contents($this->recordedFile, json_encode([]));
        }

        $this->middleware('web');
    }

    public function index()
    {
        $doctorsFile = storage_path('admindoctors-data.json');
    
    if (!file_exists($doctorsFile)) {
        file_put_contents($doctorsFile, json_encode([]));
    }
    
    $doctors = json_decode(file_get_contents($doctorsFile), true);
    
    // Получаем содержимое файла и заменяем переменные
    $content = file_get_contents(public_path('admin/doctors.php'));
    
    // Заменяем PHP-переменные на значения
    $content = str_replace('<?php echo $doctor[\'last_name\']; ?>', '{{last_name}}', $content);
    $content = preg_replace('/<\?php.*?\?>/', '', $content);
    
    return response($content)
        ->header('Content-Type', 'text/html');
    }

    public function create()
    {
    // Включаем буферизацию вывода
    ob_start();
    
    // Включаем файл формы (он выполнится как PHP)
    include public_path('admin/add-doctor.php');
    
    // Получаем содержимое буфера
    $content = ob_get_clean();
    
    // Возвращаем ответ с HTML
    return response($content)
        ->header('Content-Type', 'text/html');
    }

    public function store(Request $request)
    {
        \Log::debug('Store method called', [
    'session' => $_SESSION ?? null,
    'input' => $request->all(),
    'headers' => $request->headers->all()
]);

    session_start(); // Явный старт сессии
    
    // Проверка CSRF
    if ($request->input('_token') !== ($_SESSION['_token'] ?? null)) {
        return redirect('/login')->with('error', 'Недействительный CSRF-токен');
    }


    // Валидация данных
    $validated = $request->validate([
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'specialty' => 'required|string|max:255',
        'experience' => 'required|integer|min:0',
        '_token' => 'required' // Проверка CSRF-токена
    ]);
    
    // Создаем врача
    $doctor = [
        'id' => uniqid(),
        'last_name' => $validated['last_name'],
        'first_name' => $validated['first_name'],
        'middle_name' => $validated['middle_name'] ?? '',
        'specialty' => $validated['specialty'],
        'experience' => $validated['experience'],
        'created_at' => now()->toDateTimeString()
    ];
    
    // Сохраняем в файл
    $doctorsFile = storage_path('admindoctors-data.json');
    $doctors = file_exists($doctorsFile) 
        ? json_decode(file_get_contents($doctorsFile), true) 
        : [];
    $doctors[] = $doctor;
    file_put_contents($doctorsFile, json_encode($doctors, JSON_UNESCAPED_UNICODE));
    
    return redirect('/admin/doctors')->with('success', 'Врач успешно добавлен');
    }

    private function generateDefaultSchedule()
    {
        $schedule = [];
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
    
    foreach ($days as $day) {
        foreach ($hours as $hour) {
            $schedule[$day][$hour] = true;
        }
    }
    return $schedule;
    }

    public function destroy($id)
    {
        $doctors = json_decode(file_get_contents($this->doctorsFile), true);
        $doctors = array_filter($doctors, fn($d) => $d['id'] !== $id);
        file_put_contents($this->doctorsFile, json_encode(array_values($doctors)));
        
        return back();
    }
    public function makeAppointment(Request $request)
    {
    $recorded = json_decode(file_get_contents($this->recordedFile), true);
    $appointment = [
        'user' => $_SESSION['user']['username'],
        'doctor_id' => $request->doctor_id,
        'date' => $request->date,
        'time' => $request->time,
        'recorded_at' => date('Y-m-d H:i:s')
    ];
    
    $recorded[] = $appointment;
    file_put_contents($this->recordedFile, json_encode($recorded));
    
    // Обновляем расписание врача
    $doctors = json_decode(file_get_contents($this->doctorsFile), true);
    foreach ($doctors as &$doctor) {
        if ($doctor['id'] === $request->doctor_id) {
            $dayOfWeek = date('l', strtotime($request->date));
            if (isset($doctor['schedule'][$dayOfWeek][$request->time])) {
                $doctor['schedule'][$dayOfWeek][$request->time] = false;
            }
        }
    }
    file_put_contents($this->doctorsFile, json_encode($doctors));
    
    return redirect('/user/appointment')->with('success', 'Вы успешно записаны!');
    }   
}
