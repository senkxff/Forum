<?php
require_once __DIR__ . "/logic/DataBaseLogic.php";
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

            <input type="file" name="file" class="chooseFileButton" title="выбрать файл">
            <button type="submit" class="sendButton" name="sendButton" title="отправить"></button>
        </form>
    </div>

    <div class="printMessage" id="messageContainer">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendButton'])) {

            $messageText = !empty($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
            $fileUploadMessage = null;

            // Обработка файла
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $allowedTextType = 'text/plain';

                $fileType = mime_content_type($_FILES['file']['tmp_name']);
                $fileSize = $_FILES['file']['size'];
                $uploadDir = __DIR__ . "/../uploads";

                // Создаем папку uploads, если она не существует
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (in_array($fileType, $allowedImageTypes)) {
                    // Проверяем размер изображения
                    list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
                    if ($width > 320 || $height > 240) {
                        $fileUploadMessage = "Изображение превышает допустимые размеры 320x240 пикселей.";
                    } else {
                        $fileName = basename($_FILES['file']['name']);
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                            $fileUploadMessage = "Изображение " . htmlspecialchars($fileName) . " успешно загружено.";
                        } else {
                            $fileUploadMessage = "Ошибка при загрузке изображения.";
                        }
                    }
                } elseif ($fileType === $allowedTextType) {
                    // Проверяем размер текстового файла
                    if ($fileSize > 102400) {
                        $fileUploadMessage = "Текстовый файл превышает допустимый размер 100КБ.";
                    } else {
                        $fileName = basename($_FILES['file']['name']);
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                            $fileUploadMessage = "Текстовый файл " . htmlspecialchars($fileName) . " успешно загружен.";
                        } else {
                            $fileUploadMessage = "Ошибка при загрузке текстового файла.";
                        }
                    }
                } else {
                    $fileUploadMessage = "Недопустимый формат файла. Разрешены только JPG, GIF, PNG, TXT.";
                }
            }

            // Вывод сообщений
            if ($messageText) {
                echo "<p><b>" . htmlspecialchars($_SESSION['user_name'] ?? 'Неизвестный пользователь') . ":</b> $messageText</p>";
            }
            if ($fileUploadMessage) {
                echo "<p><b>" . htmlspecialchars($_SESSION['user_name'] ?? 'Неизвестный пользователь') . ":</b> $fileUploadMessage</p>";
            }
        }

        // Отображение сообщений
        if (isset($messages) && is_array($messages)) {
            foreach ($messages as $message) {
                $senderName = htmlspecialchars($message['sender_name'] ?? 'Неизвестный отправитель');
                $messageText = htmlspecialchars($message['message_text'] ?? '');
                $filePath = htmlspecialchars($message['file_path'] ?? '');

                echo "<p><b>$senderName:</b> $messageText</p>";

                if (!empty($filePath)) {
                    $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);
                    if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                        // Отображение изображения
                        echo "<p><img src='$filePath' alt='Изображение' style='max-width:320px; max-height:240px;'></p>";
                    } elseif ($fileExt === 'txt') {
                        // Ссылка на текстовый файл
                        echo "<p><a href='$filePath' target='_blank'>Скачать текстовый файл</a></p>";
                    } else {
                        // Ссылка на другие типы файлов
                        echo "<p><a href='$filePath' target='_blank'>Скачать файл</a></p>";
                    }
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
