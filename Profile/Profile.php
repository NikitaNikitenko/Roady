<?php 
session_start();
include "../Application/database/db.php";

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['id'])) {
    header('Location: Project-PP\Login\Login.php'); // Перенаправление на страницу входа, если пользователь не авторизован
    exit();
}

$user_id = $_SESSION['id']; 
$user = selectOne('user', ['id' => $user_id]);

if (!$user) {
    echo "Користувач не знайдений.";
    exit();
}

// Set empty fields to empty strings
// Set empty fields to empty strings
$user['name'] = !empty($user['name']) ? $user['name'] : '';
$user['surname'] = !empty($user['surname']) ? $user['surname'] : '';
$user['username'] = !empty($user['username']) ? $user['username'] : '';
$user['email'] = !empty($user['email']) ? $user['email'] : '';
$user['number_phone'] = !empty($user['number_phone']) ? $user['number_phone'] : '';
$user['location'] = !empty($user['location']) ? $user['location'] : '';

// Проверяем наличие необходимых данных
$profileIncomplete = empty($user['name']) || empty($user['surname']) || empty($user['number_phone']);

// Проверка наличия сообщения об успешном обновлении
$successMessage = '';
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Удаляем сообщение после отображения
}

// Проверка наличия сообщения об ошибке
$errorMessage = '';
if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профіль користувача</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            display: flex;
            background-color: #fff;
            min-height: 90vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            overflow: hidden;
            margin: 40px auto;
            max-width: 1200px;
            padding: 20px;
        }
        .sidebar {
            width: 300px;
            background-color: #2d3436;
            color: #dfe6e9;
            padding: 30px;
            box-sizing: border-box;
            border-radius: 15px;
            margin-right: 20px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 20px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #dfe6e9;
            padding: 15px 25px;
            display: block;
            border-radius: 10px;
            background-color: #636e72;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #00b894;
            transform: translateX(8px);
        }
        .content {
            padding: 40px 50px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f1f2f6;
            border-radius: 15px;
        }
        .content h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
            color: #2d3436;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            position: relative;
            justify-content: space-between;
        }
        .form-group label {
            width: 25%;
            font-weight: bold;
            color: #2d3436;
        }
        .form-group input {
            width: 70%;
            padding: 14px;
            border: 1px solid #ced6e0;
            border-radius: 10px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-size: 16px;
        }
        .form-group input:focus {
            border-color: #00b894;
            box-shadow: 0 0 8px rgba(0, 184, 148, 0.2);
        }
        .form-group input[type="password"] {
            padding-right: 45px;
        }
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #636e72;
            transition: color 0.3s;
        }
        .toggle-password:hover {
            color: #2d3436;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }
        .form-actions button {
            padding: 14px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 15px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .form-actions .cancel {
            background-color: #d63031;
            color: #fff;
        }
        .form-actions .cancel:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        .form-actions .save {
            background-color: #00b894;
            color: #fff;
        }
        .form-actions .save:hover {
            background-color: #009b79;
            transform: translateY(-2px);
        }
        .note {
            color: #d63031;
            font-size: 15px;
            margin-top: 15px;
            text-align: center;
        }

    /* Additional improvements */
    .sidebar ul li.active a {
        background-color: #00b894;
        color: #fff;
    }
    .content form {
        background-color: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="password"] {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .toggle-password::before {
        content: "👁️";
    }

    .success{
        padding: 20px;
        background-color: #27ae60;
        color: white;
        border-radius: 5px;
        text-align: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        max-width: 100%;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px;
         margin-bottom: 20px;
        border-radius: 5px;
    }

</style>

<script>
    function logout() {

        window.location.href = 'logout.php';
    }

    // Функция для скрытия сообщения через 5 секунд
    function hideSuccessMessage() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    }

    // Вызов функции после загрузки страницы
    window.onload = hideSuccessMessage;

</script>

</head>
<body>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li class="active"><a href="#">Профіль</a></li>
                <li><a href="ActivePodorozh\ActivePodorozh.php">Активна подорож</a></li>
                <li><a href="HistoryPodorozh\HistoryPodorozh.php">Історія подорожей</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Профіль користувача</h1>
        

            <?php if ($successMessage): ?>
                <div id="successMessage" class="success"><?php echo $successMessage; ?></div>
            <?php endif; ?>


            <?php if ($errorMessage): ?>
                <div id="errorMessage" class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <form id="profileForm" action="/Project-PP/Application/database/update_profile.php" method="post">
                <div class="form-group">
                    <label for="firstName">Ім'я</label>
                    <input type="text" id="firstName" name="firstName" placeholder="Ім'я" value="<?php echo htmlspecialchars($user['name']); ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Прізвище</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Прізвище" value="<?php echo htmlspecialchars($user['surname']); ?>">
                </div>
                <div class="form-group">
                    <label for="username">Ім'я користувача</label>
                    <input type="text" id="username" name="username" placeholder="Ім'я користувача" value="<?php echo htmlspecialchars($user['username']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Електронна адреса</label>
                    <input type="email" id="email" name="email" placeholder="email@example.com" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Номер телефону</label>
                    <input type="tel" id="phone" name="phone" placeholder="123-456-7890" value="<?php echo htmlspecialchars($user['number_phone']); ?>">
                </div>
                <div class="form-group">
                    <label for="location">Місцезнаходження (не обов'язково)</label>
                    <input type="text" id="location" name="location" placeholder="Місцезнаходження" value="<?php echo htmlspecialchars($user['location']); ?>">
                </div>
                <div class="form-group">
                    <label for="currentPassword">Поточний пароль</label>
                    <input type="password" id="currentPassword" name="currentPassword">
                    <span class="toggle-password" onclick="togglePassword('currentPassword')"></span>
                </div>
                <div class="form-group">
                    <label for="newPassword">Новий пароль</label>
                    <input type="password" id="newPassword" name="newPassword">
                    <span class="toggle-password" onclick="togglePassword('newPassword')"></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Підтвердіть новий пароль</label>
                    <input type="password" id="confirmPassword" name="confirmPassword">
                    <span class="toggle-password" onclick="togglePassword('confirmPassword')"></span>
                </div>
                <div class="form-actions">

                    <button type="button" class="cancel button-color" onclick="logout()">Вийти з системи</button>

                    <button type="button" class="cancel" onclick="resetForm()">Відмінити</button>

                    <button type="submit" class="save" name="save">Зберегти</button>

                    <style>
                        .button-color { 
                            background-color: #0f55eb !important;
                        }
                    </style>

                </div>

                <?php if ($profileIncomplete): ?>
                    <p class="note">*Увага ваш профіль користувача не заповнено. Для пришвидшення оформлення квитків, рекомендується це зробити</p>
                <?php endif; ?>

            </form>
        </div>
    </div>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const fieldType = field.getAttribute("type");
            if (fieldType === "password") {
                field.setAttribute("type", "text");
            } else {
                field.setAttribute("type", "password");
            }
        }

        function resetForm() {
        var form = document.getElementById('profileForm');
            form.reset();
        }

    </script>
</body>
</html>