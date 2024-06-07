<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0f0f0f;
            color: #ffffff;
            padding: 0;
            margin: 0;
            background-image: url('https://i.imgur.com/ZaH1h8a.jpg'); /* Путь к фоновому изображению */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Прозрачный цвет фона контейнера */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            max-width: 800px; /* Максимальная ширина контейнера */
            text-align: center;
        }

        h1 {
            color: #ffba00; /* Золотой цвет заголовка */
            font-size: 36px;
            margin-bottom: 20px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu li {
            margin-bottom: 15px;
            overflow: hidden;
            position: relative;
        }

        .menu li a {
            display: block;
            padding: 15px 40px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 0;
            transition: transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to right, #3a273a, #202020); /* Градиентный фон */
            background-size: 200% auto;
            animation: gradient 5s linear infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .menu li a::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s, transform 0.5s;
            z-index: 1;
            opacity: 0;
        }

        .menu li a:hover::before {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .menu li a:hover {
            transform: scale(1.1);
        }

        .menu li a:active {
            background-color: #1a1a1a; /* Темно-серый цвет фона при нажатии */
        }

        .menu li a:hover h3 {
            color: #ffba00; /* Золотой цвет заголовка при наведении */
        }

        /* Стиль для кнопки "Выйти" */
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #ffba00; /* Золотой цвет фона */
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #cca300; /* Темно-золотой цвет фона при наведении */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, Администратор!</h1>
        <h2>Выберите действие:</h2>
        <ul class="menu">
            <li class="variant1">
                <a href="add_practice_admin.php">
                    <h3>Добавить практику</h3>
                </a>
            </li>
            <li class="variant2">
                <a href="edit_practice.php">
                    <h3>Редактировать практику</h3>
                </a>
            </li>
            <li class="variant3">
                <a href="delete_practice.php">
                    <h3>Удалить практику</h3>
                </a>
            </li>
        </ul>
        <a href="../logout.php" class="logout-btn">Выйти</a>
    </div>
</body>
</html>
