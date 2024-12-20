<?php
$dbname = "users_information";
$host = "localhost:3305";
$user = "root";
$password = "5456527";

$connect = mysqli_connect($host, $user, $password, $dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["userName"]) && isset($_POST["registrationButton"])) {
    $login = htmlspecialchars($_POST["login"]);
    $password = htmlspecialchars($_POST["password"]);
    $userName = htmlspecialchars($_POST["userName"]);
    $ip = $_SERVER['REMOTE_ADDR'];
    $browserVersion = $_SERVER['HTTP_USER_AGENT'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO `users` (email, password_hash, IP, browser, user_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $login, $hashed_password, $ip, $browserVersion, $userName);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location:mainPage.php");
}


