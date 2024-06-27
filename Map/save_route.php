<?php
session_start(); // Починаємо сесію
include "\\Ampps\\www\\Project-PP\\Application\\database\\db.php";

// Отримуємо дані з POST запиту
$data = json_decode(file_get_contents('php://input'), true);

// Перевіряємо наявність даних і зберігаємо їх у базу даних
if (!empty($data['starting_place']) && !empty($data['destination'])) {
    $startingPlace = $data['starting_place'];
    $destination = $data['destination'];

    // Перевіряємо чи користувач залогінений
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Отримуємо поточну дату та час
        $currentTime = date('Y-m-d H:i:s');

        // Додаємо id користувача до параметрів
        $params = [
            'starting_place' => $startingPlace,
            'destination' => $destination,
            'user_id' => $userId,
            'time_spawn' => $currentTime
        ];
        insert('route', $params);

        echo "Дані успішно збережені.";
    } else {
        echo "Помилка: Користувач не залогінений.";
    }
} else {
    echo "Помилка: Відсутні дані.";
}
?>
