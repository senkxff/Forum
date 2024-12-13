<?php require_once __DIR__ . "/signInLogic.php"?>

<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="signIn.css">
        <title>Регистрация</title>
    </head>
    <body>
        <form action="" method="post">
            <div id="login">
                <label for="login" id="lgn">Введите Ваш логин: </label>
                <input name="login" id="lgn">
            </div>
            <div id="password">
                <label for="password">Введите Ваш пароль: </label>
                <input name="password" type="password">
            </div>
            <div id="startButtons">
                <input type="submit" value="Войти" id="enterInto">
                <input type="submit" value="Зарегистрироваться" id="registrationInto">
            </div>

        </form>
    </body>
</html>