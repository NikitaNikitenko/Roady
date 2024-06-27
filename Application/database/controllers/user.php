<?php

require_once __DIR__ . '/../db.php';

$errorMsg = '';
$errorMsgLog = '';

$new_username = '';
$email = '';
$new_password = '';
$repeat_password = '';
$username = '';
$password = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['button-reg'])){

        // Зареєструватися
        $new_username = trim($_POST['new-username']);
        $new_password = trim($_POST['new-password']);
        $repeat_password = trim($_POST['repeat-password']);
        $email = trim($_POST['email']);

        if($new_username === '' || $email === '' || $new_password === '') {
            $errorMsg = "Не всі поля заповнені";

        } elseif(mb_strlen($new_username, 'UTF8') < 3) {
            $errorMsg = "Ім'я користувача повенне бути більше ніж 3 символа";

        } elseif($new_password !== $repeat_password) {
            $errorMsg = "Паролі не співпадають!";

        } else {

            $email_existence = selectOne('user', ['email' => $email]);
            $username_existence = selectOne('user', ['username' => $new_username]);

            if ($email_existence && $email_existence['email'] === $email) {

                $errorMsg = "Користувач з такою поштою вже існує";

            } elseif ($username_existence && $username_existence['username'] === $new_username) {

                $errorMsg = "Користувач з таким іменем вже існує";

            } else { 
    
                $password = password_hash($new_password, PASSWORD_DEFAULT);
                $post_reg = [
                    'username' => $new_username,
                    'password' => $password,
                    'email' => $email
                ];

                $id = insert('user', $post_reg);
                $errorMsg = "Користувач " . "<strong>" . $new_username . "</strong>" . " успішко зареєстрован";
                echo 
                    "<script>
                        setTimeout(function() {
                            window.location.href = '/Project-PP/index.php';
                        }, 3000); // Задержка в миллисекундах (2000ms = 2s)
                    </script>";


                $last_row = selectOne('user', ['id' => $id]);

                
            }

        }
    }

    if (isset($_POST['button-log'])) {
        // Код входа
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if ($username === '' || $password === '') {
            $errorMsgLog = "Не всі поля заповнені";

        } else {

            $user = selectOne('user', ['username' => $username]);

            if ($user) {

                if (password_verify($password, $user['password'])) {
                    session_start();

                    // echo "Пользователь найден: ";
                    // formatData($user);

                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    // echo "Успешный вход!";

                    echo "<script>
                    setTimeout(function() {
                        window.location.href = '/Project-PP/index.php';
                    }, 2000); // Задержка в миллисекундах (3000ms = 3s)
                  </script>";
                  $errorMsgLog = "Ви успішно увійшли в систему!";
            // echo "Успешный вход!.";
            return;
                } else {
                    $errorMsgLog = "Невірний пароль";
                }
            } else {
                $errorMsgLog = "Невірне ім'я користувача";
            }
        }
    }


} else {
    $new_username ='';
    $email = '';
    $username = '';
}


?>