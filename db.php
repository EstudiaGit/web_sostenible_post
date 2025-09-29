<?php

$host = 'localhost';
$port = '3307'; // <-- ¡Este es el cambio principal!
$dbname = 'post_api';
$user = 'root';
$password = 'micontraseñasecreta'; // <-- Usa la misma contraseña del Paso 1

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}