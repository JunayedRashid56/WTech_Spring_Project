<?php
$host = "localhost";
$dbname = "food_ordering_system";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "";
} catch (PDOException $e) {
    die("Connection Failed:" . $e->getMessage());
}
