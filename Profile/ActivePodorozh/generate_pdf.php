<?php
require_once __DIR__ . '/../../vendor/setasign/fpdf/fpdf.php';
include "../../Application/database/db.php";

function generatePDF($ticketId) {
    global $pdo;


    // SQL-запит для отримання даних квитка
    $sql = "SELECT user.name, user.surname, route.starting_place, route.destination, route.time_spawn 
            FROM user
            JOIN route ON user.id = route.user_id
            WHERE route.id = :ticketId";

    $query = $pdo->prepare($sql);
    $query->execute(['ticketId' => $ticketId]);

    // Перевірка помилок у виконанні запиту
    dbCheckError($query);

    $ticket = $query->fetch(PDO::FETCH_ASSOC);

    // Перевірка, чи є квиток
    if (!$ticket) {
        die('Ticket not found!');
    }

    // Створення PDF-файлу
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Електронний квиток');
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, "Ім'я: " . $ticket['name']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Прізвище: " . $ticket['surname']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Маршрут: " . $ticket['starting_place'] . ' - ' . $ticket['destination']);
    $pdf->Ln();
    $pdf->Cell(40, 10, "Дата створення: " . $ticket['time_spawn']);
    $pdf->Output('D', 'ticket.pdf');
}

if (isset($_GET['id'])) {
    generatePDF($_GET['id']);
} else {
    die('Ticket ID not provided!');
}
?>
