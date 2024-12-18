<?php

$dbname = "users_information";
$host = "localhost:3305";
$user = "root";
$password = "5456527";

$connect = mysqli_connect($host, $user, $password, $dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["registrationButton"])) {
    $login = htmlspecialchars($_POST["login"]);
    $password = htmlspecialchars($_POST["password"]);
    $ip = $_SERVER['REMOTE_ADDR'];
    $browserVersion = $_SERVER['HTTP_USER_AGENT'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO `users` (email, password_hash, IP, browser) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $login, $hashed_password, $ip, $browserVersion);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location:mainPage.php");
}


