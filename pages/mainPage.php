<?php
session_start();

if (isset($_POST['sendButton']) && !empty($_POST['message']) && !empty($_SESSION['userName'])) {
    $_SESSION['msg'][] = [
        'userName' => $_SESSION['userName'],
        'text' => htmlspecialchars($_POST['message'])
    ];
    header("Location: " . $_SERVER['REQUEST_URI'] . '#bottom');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Сообщения</title>
    <link rel="stylesheet" href="../css/mainPage.css">
</head>
<body>
<header></header>

<main>
    <div class="messageBlock">
        <form action="" method="post" enctype="multipart/form-data">
            <textarea placeholder="Введите ваше сообщение..." class="textarea" name="message" autofocus></textarea>
            <button type="submit" class="sendButton" name="sendButton" title="отправить"></button>
            <input type="file" name="file" class="chooseFileButton" title="выбрать файл">
        </form>
    </div>

    <div class="printMessage" id="messageContainer">
        <?php
        if (!empty($_SESSION['msg'])) {
            foreach ($_SESSION['msg'] as $message) {
                if (is_array($message)) {
                    echo "<h8>" . htmlspecialchars($message['userName']) . "</h8>";
                    echo "<h7>" . htmlspecialchars($message['text']) . "</h7><br>";
                } else {
                    // Если это не массив, выводим как текстовое сообщение
                    echo "<h7>" . htmlspecialchars($message) . "</h7><br>";
                }
            }
        }
        ?>
        <div id="bottom"></div>
    </div>
</main>

<footer></footer>
</body>
</html>
