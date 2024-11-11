<?php
// conexao.php

// Configura��es de conex�o
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Fun��o para conectar ao banco de dados
function conectarBanco() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conex�o foi bem-sucedida
    if ($conn->connect_error) {
        die("Falha na conex�o: " . $conn->connect_error);
    }
    return $conn;
}
?>
