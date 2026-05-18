<?php

session_start();

if(!isset($_SESSION['user_id'])) {

    header("Location: /WTech Project/views/auth/login.php");
    exit;
}

?>