<?php
session_start(); 

include "db.php";

if (!isset($_SESSION['id'])) {
    echo 'не авторизован';
    exit();
}


$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Проверка уникальности username
    $existingUser = selectOne('user', ['username' => $username]);
    if ($existingUser && $existingUser['id'] != $user_id) {
        $_SESSION['error_message'] = "Ім'я користувача вже зайнято.";
        header('Location: /Project-PP/Profile/Profile.php');
        exit();
    }

    // Проверка уникальности email
    $existingEmail = selectOne('user', ['email' => $email]);
    if ($existingEmail && $existingEmail['id'] != $user_id) {
        $_SESSION['error_message'] = "Електронна адреса вже використовується.";
        header('Location: /Project-PP/Profile/Profile.php');
        exit();
    }   

    // Проверка минимальной длины
    if (strlen($username) < 3) {
        $_SESSION['error_message'] = "Ім'я користувача повинно містити не менше 3 символів.";
        header('Location: /Project-PP/Profile/Profile.php');
        exit();
    }

    // Проверка текущего пароля
    $user = selectOne('user', ['id' => $user_id]);
    if ($currentPassword !== '') {
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['error_message'] = "Невірний поточний пароль.";
            header('Location: /Project-PP/Profile/Profile.php');
            exit();
        }
    } elseif ($newPassword !== '' || $confirmPassword !== '') {
        $_SESSION['error_message'] = "Для зміни пароля необхідно ввести поточний пароль.";
        header('Location: /Project-PP/Profile/Profile.php');
        exit();
    }

    // Проверка нового пароля
    if ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = "Паролі не співпадають.";
        header('Location: /Project-PP/Profile/Profile.php');
        exit();
    }

    // Обновление данных пользователя
    $updateData = [
        'name' => $firstName,
        'surname' => $lastName,
        'username' => $username,
        'email' => $email,
        'number_phone' => $phone,
        'location' => $location
    ];

    // Хеширование нового пароля, если он указан, и добавление его в данные для обновления
    if ($newPassword !== '') {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateData['password'] = $hashedPassword;
    }

    update('user', $user_id, $updateData);
    
    $_SESSION['success_message'] = 'Дані успішно оновлено.'; // Додаємо повідомлення про успішне оновлення
    header('Location: /Project-PP/Profile/Profile.php'); // Перенаправление на страницу профиля
    exit();
} else {
    // header('Location: \Ampps\www\Project\Profile\Profile.php'); // Перенаправление на страницу профиля
    // exit();
}
?>
