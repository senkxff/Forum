<?php session_start();

if (!isset($_SESSION['msg'])) {
    $_SESSION['msg'] = [];
}

if (isset($_POST['sendButton'])) {
    if (isset($_POST['message'])) {
        $message = htmlspecialchars($_POST['message']);
        $_SESSION['msg'][] = $message;
    }
}



