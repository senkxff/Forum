<?php
require_once __DIR__ . "/logic/DataBaseLogic.php";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sortOrder"])) {
    $sortOrder = $_POST["sortOrder"];
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

<main>
    <div class="messageBlock">
        <form action="" method="post" enctype="multipart/form-data">
            <textarea placeholder="Введите ваше сообщение..." class="textarea" name="message"></textarea>

            <input type="file" name="file" class="chooseFileButton" title="выбрать файл">
            <button type="submit" class="sendButton" name="sendButton" title="отправить"></button>

            <select name="sortOrder" onchange="this.form.submit()">
                <option value="date_desc" <?= $sortOrder === 'date_desc' ? 'selected' : '' ?>>По дате (новые сначала)</option>
                <option value="date_asc" <?= $sortOrder === 'date_asc' ? 'selected' : '' ?>>По дате (старые сначала)</option>
                <option value="name_asc" <?= $sortOrder === 'name_asc' ? 'selected' : '' ?>>По имени (A-Z)</option>
                <option value="name_desc" <?= $sortOrder === 'name_desc' ? 'selected' : '' ?>>По имени (Z-A)</option>
            </select>
        </form>
    </div>

    <div class="printMessage" id="messageContainer">
        <?php
        if (isset($messages) && is_array($messages)) {
            foreach ($messages as $message) {
                $senderName = htmlspecialchars($message['sender_name'] ?? 'Неизвестный отправитель');
                $messageText = htmlspecialchars($message['message_text'] ?? '');
                $filePath = htmlspecialchars($message['file_path'] ?? '');

                echo "<p><b>$senderName:</b> $messageText</p>";

                if (!empty($filePath)) {
                    $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);
                    if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo "<p><img src='$filePath' alt='Изображение' style='max-width:320px; max-height:240px;'></p>";
                    } elseif ($fileExt === 'txt') {
                        echo "<p><a href='$filePath' target='_blank'>Скачать текстовый файл</a></p>";
                    } else {
                        echo "<p><a href='$filePath' target='_blank'>Скачать файл</a></p>";
                    }
                }
            }
        }
        ?>
        <div id="bottom"></div>
    </div>
</main>

</body>
</html>
