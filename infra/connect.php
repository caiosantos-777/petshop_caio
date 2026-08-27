<?php

$host = 'localhost';
$username = 'root';
$pass = 'root';
$dbname = 'caio_petshop';

$conn = new mysqli($host, $username, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

?>