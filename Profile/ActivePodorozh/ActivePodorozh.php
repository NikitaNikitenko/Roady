<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Активная поездка</title>
    <link rel="stylesheet" href="ActivePodorozh.css">
</head>
<body>
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
            width: 250px;
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
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #00b894;
            transform: translateX(8px);
        }
        .main-content {
            padding: 40px 50px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f1f2f6;
            border-radius: 15px;
        }
        .main-content h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
            color: #2d3436;
            font-weight: 700;
        }
        .ticket {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .ticket img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .ticket button {
            padding: 14px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .ticket button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .ticket {
            width: 300px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }

        #ticketContent {
            margin-bottom: 20px;
        }

        #ticketContent h2 {
            font-size: 1.5em;
            color: #333;
        }

        #ticketContent p {
            margin: 10px 0;
        }

        #ticketContent strong {
            font-weight: bold;
        }

        #ticketContent span {
            font-weight: normal;
            color: #555;
        }

        #downloadBtn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        #downloadBtn:hover {
            background-color: #45a049;
        }
        
        #barcodeContainer {
            display: inline-block;
            vertical-align: top;
            margin-left: 20px;
        }

        #barcodeImage {
            width: 120px; /* розміри штрих-коду */
            height: auto;
        }
        

    </style>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="\Project-PP\Profile\Profile.php">Профiль</a></li>
                <li><a href="ActivePodorozh.php" class="active">Активна подорож</a></li>
                <li><a href="\Project-PP\Profile\HistoryPodorozh\HistoryPodorozh.php">Історія подорожей</a></li>
            </ul>
        </aside>
        <main class="main-content">
    <h1>Активная подорож</h1>
    <div id="ticketsSection">
        <!-- Квитки будуть додані сюди динамічно -->
    </div>

    <script>
        // Функція для отримання квитків з сервера
    function fetchTickets() {
            fetch('get_tickets.php')
                .then(response => response.json())
                .then(data => {
                    // Перебираємо кожен квиток і створюємо відповідний DOM елемент
                    data.forEach(ticket => {
                        createTicket(ticket);
                    });
                })
                .catch(error => {
                    console.error('Error fetching tickets:', error);
                });
        }

        // Функція для створення одного квитка на основі даних
        function createTicket(ticketData) {
            // Створення основного контейнера для квитка
            const ticketContainer = document.createElement('div');
            ticketContainer.classList.add('ticket');

            // Створення контенту квитка
            const ticketContent = document.createElement('div');
            ticketContent.id = 'ticketContent';
            ticketContent.innerHTML = `
                <h2>Електронний квиток</h2>
                <p><strong>Ім'я:</strong> <span id="ticketName">${ticketData.name}</span></p>
                <p><strong>Прізвище:</strong> <span id="ticketSurname">${ticketData.surname}</span></p>
                <p><strong>Маршрут:</strong> <span id="ticketRoute">${ticketData.starting_place} - ${ticketData.destination}</span></p>
                <p><strong>Дата створення:</strong> <span id="ticketDate">${ticketData.time_spawn}</span></p>
            `;

            // Створення штрих-коду (якщо необхідно)
            const barcodeContainer = document.createElement('div');
            barcodeContainer.id = 'barcodeContainer';
            // Додайте код для створення штрих-коду тут, якщо потрібно

            // Кнопка для завантаження квитка
            const downloadBtn = document.createElement('button');
            downloadBtn.id = 'downloadBtn';
            downloadBtn.textContent = 'Завантажити';
            downloadBtn.addEventListener('click', () => {
                // Виклик PHP-скрипту для генерування PDF
                window.location.href = `generate_pdf.php?id=${ticketData.id}`;
            });

            // Додавання всіх елементів до основного контейнера квитка
            ticketContainer.appendChild(ticketContent);
            ticketContainer.appendChild(barcodeContainer);
            ticketContainer.appendChild(downloadBtn);

            // Додаємо квиток до DOM
            const ticketsSection = document.getElementById('ticketsSection');
            ticketsSection.appendChild(ticketContainer);
        }

        // Виклик функції для отримання та відображення квитків
        fetchTickets();
    </script>
</main>
    </div>
    <script src="ActivePodorozh.js"></script>
</body>
</html>
