<?php
// conexao.php

$servername = "localhost";
$username = "root";
$password = "Bunda4542@";
$dbname = "oferta";

function conectarBanco() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexao: " . $conn->connect_error);
    }
    return $conn;
}
?>
