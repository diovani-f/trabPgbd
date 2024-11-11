<?php
// conexao.php

// Configurações de conexão
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Função para conectar ao banco de dados
function conectarBanco() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    return $conn;
}
?>
