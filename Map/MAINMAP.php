<?php
session_start();
include "../Application/database/db.php";

$name = '';
$email = '';

// авторизован ли юзер
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    
    $user = selectOne('user', ['id' => $userId]);
    
    if ($user) {
        $name = $user['name'];
        $email = $user['email'];
    }
}

$isLoggedIn = isset($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap with Mapbox</title>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css" rel="stylesheet" type="text/css">
    <style>
        body {
            margin: 0;
            overflow-x: hidden;
            font-family: Arial, sans-serif;
        }
        #map {
            height: 100vh;
            width: 100vw;
        }
        #ticket-container, #payment-popup, #overlay {
            display: none;
        }
        #ticket-container, #payment-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            z-index: 10;
            width: 90%;
            max-width: 400px;
        }
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9;
        }
        .close {
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 18px;
            color: #aaa;
        }
        .close:hover {
            color: #000;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
        .payment-method {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .payment-method img {
            width: 40px;
            margin-right: 10px;
        }
        .payment-method:hover {
            background-color: #f8f9fa;
        }
        .payment-details {
            display: none;
        }
        .payment-details form button {
            background-color: #007bff;
        }
        .payment-details form button:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>

    <div id="map"></div>
       
    
    <div id="ticket-container">

   

        <span class="close" onclick="closePaymentPopup()">&times;</span>
        <h2>Купівля квитка</h2>
        <form id="ticket-form">
            <label for="name">Ім'я:</label>
            <input type="text" id="name" name="name" placeholder="Ваше ім'я" value="<?php echo htmlspecialchars($name); ?>" required>
            
            <label for="email">Електронна пошта:</label>
            <input type="email" id="email" name="email" placeholder="Ваша електронна пошта" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="ticket-quantity">Кількість квитків:</label>
            <input type="number" id="ticket-quantity" name="ticket-quantity" min="1" value="1" required>

            <button type="button" onclick="validateTicketForm()">Купити квиток</button>
        </form>
    </div>

    <div id="overlay" onclick="closePaymentPopup()"></div>
    <div id="payment-popup">
        <span class="close" onclick="closePaymentPopup()">&times;</span>
        <h2>Оплата</h2>
        <div class="payment-method" onclick="selectPaymentMethod('card')">
            <img src="https://banner2.cleanpng.com/20180621/uhk/kisspng-visa-electron-credit-card-computer-icons-5b2c732dd97e02.8290038015296397258909.jpg" alt="Банківська карта">
            <span>Банківська карта</span>
        </div>
        <div class="payment-method" onclick="selectPaymentMethod('google-wallet')">
            <img src="https://external-preview.redd.it/3TazQWoIyXxrH261pdaW2DNeu0V1-JeivUxwW7M0aGU.jpg?auto=webp&s=da32a0014b13006e862f65d814d4a4e4472ea70f" alt="Google Wallet">
            <span>Google Wallet</span>
        </div>
        <div class="payment-method" onclick="selectPaymentMethod('apple-pay')">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968601.png" alt="Apple Pay">
            <span>Apple Pay</span>
        </div>


        <div class="payment-method" onclick="selectPaymentMethod('privat24')">
            <img src="https://d2z9uwnt8eubh7.cloudfront.net/media/default/0001/14/486daea6ba339172b7fde66786c3ee870d866ede.png" alt="privat">
            <span>Приват банк</span>
        </div>

        <div class="payment-method" onclick="selectPaymentMethod('mono')">
            <img src="https://multicast.com.ua/wp-content/uploads/2024/04/monobank-logo.png" alt="mono">
            <span>Моно банк</span>
        </div>

        <div id="payment-card" class="payment-details">

            </style>
            <form id="card-payment-form">
                <label for="card-number">Номер картки:</label>
                <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" required>
                
                <label for="card-expiry">Термін дії (MM/YY):</label>
                <input type="text" id="card-expiry" name="card-expiry" placeholder="MM/YY" required>
                
                <label for="card-cvc">CVC:</label>
                <input type="number" id="card-cvc" name="card-cvc" placeholder="123" required>

                <button id="save_button">Оплатити</button>
            </form>
        </div>

        <div id="payment-google-wallet" class="payment-details">

            <form id="google-wallet-payment-form">
                <p>Для оплати через Google Wallet, дотримуйтесь інструкцій на вашому пристрої.</p>
                <button type="submit">Оплатити через Google Wallet</button>
            </form>
        </div>

        <div id="payment-apple-pay" class="payment-details">
            <form id="apple-pay-payment-form">
                <p>Для оплати через Apple Pay, дотримуйтесь інструкцій на вашому пристрої.</p>
                <button type="submit">Оплатити через Apple Pay</button>
            </form>
        </div>
    </div>

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoib2RpbnNkIiwiYSI6ImNseDUwNnBpazE3c3UyanF1ZzFmMWE4bnkifQ.UtZZlulp_lQ-TI8ZISDJXg'; 

        navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
            enableHighAccuracy: true
        });


        function successLocation(position) {
            setupMap([position.coords.longitude, position.coords.latitude]);
        }

        function errorLocation() {
            setupMap([36.2292, 49.9935]); 
        }

        function setupMap(center) {
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: center,
                zoom: 15
            });

            const nav = new mapboxgl.NavigationControl();
            map.addControl(nav);

            var directions = new MapboxDirections({
                accessToken: mapboxgl.accessToken,
            });

            map.addControl(directions, 'top-left');

            directions.on('route', function(e) {
                showTicketForm();
            });
        }

        function showTicketForm() {
            document.getElementById('ticket-container').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closePaymentPopup() {
            document.getElementById('ticket-container').style.display = 'none';
            document.getElementById('payment-popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
            hidePaymentDetails();
        }

        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        function validateTicketForm() {
            var form = document.getElementById('ticket-form');
            if (form.checkValidity()) {
                form.style.display = 'none';
                document.getElementById('payment-popup').style.display = 'block';
            } else {
                form.reportValidity();
            }
            
            if (!isLoggedIn) {
                alert('Ви повинні бути залогінені, щоб купити квиток.');
                window.location.href = '/Project-PP/index.php';
                return;
            }
        }

        function selectPaymentMethod(method) {
            hidePaymentDetails();
            document.getElementById('payment-' + method).style.display = 'block';
        }

        function hidePaymentDetails() {
            var details = document.querySelectorAll('.payment-details');
            details.forEach(function(detail) {
                detail.style.display = 'none';
            });
        
        }


        
        document.addEventListener('DOMContentLoaded', () => {
    console.log("DOMContentLoaded event fired");

    // Функція для відправки даних на сервер
    function saveRouteData(startingPlace, destination) {
        console.log("Відправляємо дані на сервер:", { startingPlace, destination });
        fetch('save_route.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                starting_place: startingPlace,
                destination: destination
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log("Відповідь від сервера:", data); // Логування відповіді від сервера
        })
        .catch(error => {
            console.error('Помилка:', error);
        });
    }

    // Функція для отримання поточних значень input полів
    function getInputFieldValues() {
        const startingPlaceInput = document.querySelector('input[placeholder="Choose a starting place"]');
        const destinationInput = document.querySelector('input[placeholder="Choose destination"]');
        console.log("Отримані значення input полів:", {
            startingPlace: startingPlaceInput ? startingPlaceInput.value : '',
            destination: destinationInput ? destinationInput.value : ''
        });
        return {
            startingPlace: startingPlaceInput ? startingPlaceInput.value : '',
            destination: destinationInput ? destinationInput.value : ''
        };
    }

    // Знаходимо кнопку для збереження даних у базу даних
    const saveButton = document.getElementById('save_button');
    if (saveButton) {
        saveButton.addEventListener('click', () => {
            console.log("Кнопка збереження натиснута");
            // Отримуємо поточні значення input полів
            const { startingPlace, destination } = getInputFieldValues();

            // Перевіряємо наявність значень
            if (startingPlace !== '' && destination !== '') {
                // Відправляємо дані на сервер
                saveRouteData(startingPlace, destination);
            } else {
                console.error('Поля вводу порожні');
            }
        });
    } else {
        console.error('Кнопка збереження не знайдена');
    }
});


    </script>
</body>
</html>
