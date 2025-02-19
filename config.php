<?php
$host = 'localhost';
$dbname = 'my_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "✅ Connected to Database!";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>