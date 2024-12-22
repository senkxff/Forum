<?php
// Запуск сессии, если она еще не начата
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Подключение к базе данных
$dbname = "users_information";
$host = "localhost:3305";
$user = "root";
$password = "5456527";

$connect = mysqli_connect($host, $user, $password, $dbname);
if (!$connect) {
    die("Ошибка соединения: " . mysqli_connect_error());
}

// Регистрация пользователя
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registrationButton"])) {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $userName = htmlspecialchars($_POST["userName"]);
    $ip = $_SERVER['REMOTE_ADDR'];
    $browserVersion = $_SERVER['HTTP_USER_AGENT'];

    // Хэширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL-запрос для вставки пользователя
    $query = "INSERT INTO `users` (email, password_hash, IP, browser, user_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    if (!$stmt) {
        die("Ошибка подготовки SQL-запроса: " . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt, "sssss", $email, $hashed_password, $ip, $browserVersion, $userName);

    if (!mysqli_stmt_execute($stmt)) {
        die("Ошибка выполнения SQL-запроса: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    // Получение ID зарегистрированного пользователя
    $query = "SELECT id FROM `users` WHERE email = ?";
    $stmt = mysqli_prepare($connect, $query);
    if (!$stmt) {
        die("Ошибка подготовки SQL-запроса: " . mysqli_error($connect));
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_fetch($stmt);

    // Установка данных сессии
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $userName;

    mysqli_stmt_close($stmt);

    // Перенаправление
    header("Location: mainPage.php");
    exit();
}

// Авторизация пользователя
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["signInButton"])) {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $query = "SELECT `id`, `user_name`, `password_hash` FROM `users` WHERE `email` = ?";
    $stmt = mysqli_prepare($connect, $query);
    if (!$stmt) {
        die("Ошибка подготовки SQL-запроса: " . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $userName, $password_hash);
    mysqli_stmt_fetch($stmt);

    // Проверка пароля
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $userName;

        header("Location: mainPage.php");
        exit();
    } else {
        echo "Неверный email или пароль!";
    }

    mysqli_stmt_close($stmt);
}

// Отправка сообщения
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sendButton"])) {
    if (!isset($_SESSION["user_name"])) {
        die("Ошибка: Пользователь не авторизован.");
    }

    $message = htmlspecialchars($_POST["message"]);
    $senderName = htmlspecialchars($_SESSION["user_name"]);

    $query = "INSERT INTO `messages` (`sender_name`, `message_text`) VALUES (?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    if (!$stmt) {
        die("Ошибка подготовки SQL-запроса: " . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt, "ss", $senderName, $message);

    if (!mysqli_stmt_execute($stmt)) {
        die("Ошибка выполнения SQL-запроса: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    // Перенаправление
    header("Location: mainPage.php");
    exit();
}

// Получение сообщений
$query = "SELECT `sender_name`, `message_text` FROM `messages` ORDER BY `created_at` DESC";
$stmt = mysqli_prepare($connect, $query);

if (!$stmt) {
    die("Ошибка подготовки SQL-запроса: " . mysqli_error($connect));
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}

// Загрузка файлов
if ($_FILES) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $uploadDir = "uploads/";
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $finalMessage = "Файл загружен: " . $fileName;
        $senderName = htmlspecialchars($_SESSION["user_name"]);

        $query = "INSERT INTO `messages` (`sender_name`, `message_text`, `file_path`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        if (!$stmt) {
            die("Ошибка подготовки SQL-запроса: " . mysqli_error($connect));
        }

        mysqli_stmt_bind_param($stmt, "sss", $senderName, $finalMessage, $filePath);

        if (!mysqli_stmt_execute($stmt)) {
            die("Ошибка выполнения SQL-запроса: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        // Перенаправление
        header("Location: mainPage.php");
        exit();
    } else {
        die("Ошибка загрузки файла.");
    }
}

