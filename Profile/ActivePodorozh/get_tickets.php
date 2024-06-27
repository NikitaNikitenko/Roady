<?php
include "../../Application/database/db.php";

// SQL-запит для отримання всіх квитків
$sql = "SELECT route.id, user.name, user.surname, route.starting_place, route.destination, route.time_spawn
        FROM route
        JOIN user ON route.user_id = user.id";

$query = $pdo->query($sql);
$tickets = $query->fetchAll(PDO::FETCH_ASSOC);

// Повернення даних у форматі JSON
header('Content-Type: application/json');
echo json_encode($tickets);
?>
