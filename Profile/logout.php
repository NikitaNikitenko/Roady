<?php
// logout.php
session_start();
session_unset();
session_destroy();

header("Location: /Project-PP/index.php"); // Перенаправлення на головну сторінку або сторінку входу
exit();
?>