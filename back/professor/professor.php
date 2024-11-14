<?php
    
    header('Content-Type: application/json');
    
    function buscaProfessor(){
        
        $sql = "SELECT * FROM Professor";
        $conn = conectarBanco();    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $dados = [];
    
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);
                }
                $dados[] = $row;
            }
        }
    
        $stmt->close();
        $conn->close();
    
        echo json_encode($dados);
    }

    function editaProfessor(){
    };

    function excluiProfessor(){
    };

    function inserirProfessor(){

    };
?>