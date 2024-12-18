
<?php require_once __DIR__ . "/logic/DataBaseLogic.php" ?>

<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="../css/signIn.css">
        <title>Вход</title>
    </head>
    <body>
        <header>
            <h1 class="header">WELCOME TO MY FORUM</h1>
        </header>
        <main>
            <form action="" method="post">
                <div class="form">
                    <input name="login" class="login" placeholder="Введите логин" required value="<?= $_POST['login'] ?? "";?>"><br>

                    <input name="password" class="password" type="password" placeholder="Введите пароль" required value="<?= $_POST['password'] ?? "";?>"><br>

                    <input type="submit" value="Войти" class="signInButton">
                    <input type="submit" value="Новый аккаунт" class="registrationButton" name="registrationButton">
                </div>
            </form>
        </main>
        <footer class="footer">
            <h2 style="color: black; text-align: center">Все права защищены, Арсений Сеньков - senkxff© </h2>
        </footer>
    </body>
</html>