<?php 

require('connection.php');

// Функция для более удобного форматирование даных 
function formatData($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

// Проверка выполнения запроса к БД
function dbCheckError($query) {

    $errorInfo = $query->errorInfo(); // Получение ошибок, если они будут
    if($errorInfo[0] !== PDO::ERR_NONE) { // Вывод самих ошибок
        echo $errorInfo[2];
        exit;
    }
    return true;
}

// универсальная функция для отображение данных таблицы
function selectAll($table, $params = []) {

    global $pdo; 
    $sql = "SELECT * FROM $table"; // запрос sql

    // Добавление условий к запросу, если переданы параметры
    if(!empty($params)) {
        $i = 0;
        foreach ($params as $key => $value) {
            if (!is_numeric($value)) {
                $value = "'".$value."'";
            }
            if ($i === 0) {
                $sql = $sql . " WHERE $key = $value";
            } else {
                $sql = $sql . " AND $key = $value";
            }
            $i++;
        }
    }
    //formatData($sql);

    $query = $pdo->prepare($sql); // Подготовка запроса для повышение безопасности
    $query->execute(); // непосредственно сам запрос 
    dbCheckError($query);
    return $query->fetchAll(); // возвращение значений
}

// Запрос на получение одной строки с выбраной таблицы
function selectOne($table, $params = []) {

    global $pdo; 
    $sql = "SELECT * FROM $table"; // запрос sql

    // Добавление условий к запросу, если переданы параметры
    if(!empty($params)) {
        $i = 0;
        foreach ($params as $key => $value) {
            if (!is_numeric($value)) {
                $value = "'".$value."'";
            }
            if ($i === 0) {
                $sql = $sql . " WHERE $key = $value";
            } else {
                $sql = $sql . " AND $key = $value";
            }
            $i++;
        }
    }
    //formatData($sql);
    $query = $pdo->prepare($sql); // Подготовка запроса для повышение безопасности
    $query->execute(); // непосредственно сам запрос 
    dbCheckError($query);
    return $query->fetch(); // возвращение значений
}

// $params = [
//     'password' => '12345'
// ];

// $data = selectOne('user', $params);
// formatData($data);

// $data = selectAll('user', $params);
// formatData($data);

//Функция записи в таблицу БД
function insert($table, $params) {
    global $pdo; 

    $i = 0;
    $columns = '';
    $placeholders = '';
    foreach($params as $key => $value) {

        if ($i === 0) {

            $columns = $columns . " $key";
            $placeholders = $placeholders . "?";

        } else {

            $columns = $columns . ", $key";
            $placeholders = $placeholders . ", ?";

        }
        $i++;
    }

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)"; // запрос sql
    // formatData($sql);
    // exit();
    $query = $pdo->prepare($sql); // Подготовка запроса для повышения безопасности
    $query->execute(array_values($params)); // непосредственно сам запрос 
    dbCheckError($query);

    return $pdo->lastInsertId();
}

// $arrayData = [
//     'name' => 'Alina',
//     'surname' => 'Костянтенко',
//     'email' => 'alinak@example.com',
//     'number_phone' => '+380500487563',
//     'location' => 'м. Харків',
//     'password' => 'dfversSSd58742354',
//     'username' => 'ALINA587'
// ];

// insert('user', $arrayData);

//Функция обновление данных в таблице
function update($table, $id, $params) {
    global $pdo; 

    $i = 0;
    $str = '';
    foreach($params as $key => $value) {

        if ($i === 0) {

            $str = $str . "$key = ?";
        } else {

            $str = $str . ", $key = ?";
        }
        $i++;
    }

    $sql = "UPDATE $table SET $str WHERE id = $id"; // запрос sql
    // formatData($sql);
    // exit();
    $query = $pdo->prepare($sql); // Подготовка запроса для повышения безопасности
    $query->execute(array_values($params)); // непосредственно сам запрос 
    dbCheckError($query);
}

// $arrayData = [
//     'name' => 'Alina',
//     'surname' => 'Костянтенко',
//     'number_phone' => '695555555',
//     'email' => "pedro@example.com"
// ];

// update('user', 8, $arrayData);

//Функция удаление данных в таблице
function delete($table, $id) {
    global $pdo; 

    $sql = "DELETE FROM $table WHERE id = $id"; // запрос sql
    // formatData($sql);
    // exit();
    $query = $pdo->prepare($sql); // Подготовка запроса для повышения безопасности
    $query->execute(); // непосредственно сам запрос 
    dbCheckError($query);
}

?> 