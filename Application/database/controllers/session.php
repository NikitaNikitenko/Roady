
<?php
// Всегда начинать сессию до любого вывода
session_start();

$isLoggedIn = isset($_SESSION['id']);
?>