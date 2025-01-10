<?php

// to connect to the database to the ddev environment

$servername = "db";  // Hostname
$username = "db";    // Username
$password = "db";    // Password
$dbname = "db";      // Database name

try {
    // Create a new PDO instance
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}