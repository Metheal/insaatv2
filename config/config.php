<?php
$host = 'localhost';
$dbname = 'insaat_app';
$username = 'mysql';
$password = 'mysql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo; 
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit; 
}
