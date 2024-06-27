<?php 

$driver = 'mysql';
$host = 'localhost';
$db_name = 'roadyproject';
$db_user = 'root';
$db_pass = 'mysql';
$charset = 'utf8';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

// ATTR_ERRMODE - атрибут режима обработки ошибок
// ERRMODE_EXCEPTION - Указывает, что ошибки должны вызывать исключения

try {
    $dsn = "$driver:host=$host;dbname=$db_name;charset=$charset";
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    //echo "Подключение к базе данных успешно установлено.";
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}


?>