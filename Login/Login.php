
<?php

include "../Application/database/controllers/user.php";
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roady Registration/Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1 class="title">Roady</h1>
        <div class="tab-container">

            <button class="tablinks" id="defaultOpen" onclick="openForm(event, 'login')">Увійти</button>
            <button class="tablinks" onclick="openForm(event, 'register')" id="register-button">Зареєструватися</button>

        </div>

        <div id="login" class="tabcontent">
    <form method="post" action="Login.php">
        <div>
            <p><?=$errorMsgLog?></p> 
        </div>
        <div class="input-group">
            <input type="text" id="username" name="username" placeholder="Ім'я користувача" value="<?=$username?>">
            <i class="fas fa-user"></i>
        </div>
        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Пароль">
            <i class="fas fa-lock"></i>
        </div>
        <button type="submit" name="button-log">Увійти</button>
        <a href="login_google.php" class="google-login-button">Увійти через Google</a>
    </form>
</div>

<div id="register" class="tabcontent">
    <form method="post" action="Login.php">
        <div>
            <p><?=$errorMsg?></p> 
        </div>
        <div class="input-group">
            <input type="text" id="new-username" name="new-username" value="<?=$new_username?>" placeholder="Ім'я користувача">
            <i class="fas fa-user"></i>
        </div>
        <div class="input-group">
            <input type="password" id="new-password" name="new-password" placeholder="Пароль">
            <i class="fas fa-lock"></i>
        </div>
        <div class="input-group">
            <input type="password" id="repeat-password" name="repeat-password" placeholder="Повторіть пароль">
            <i class="fas fa-lock"></i>
        </div>
        <div class="input-group">
            <input type="email" id="email" name="email" value="<?=$email?>" placeholder="Електронна пошта">
            <i class="fas fa-envelope"></i>
        </div>
        <button type="submit" name="button-reg">Зареєструватись</button>
        <a href="login_google.php" class="google-login-button">Зареєструватися через Google</a>
    </form>
</div>
    </div>
    <script src="Login.js"></script>
</body>
</html>
