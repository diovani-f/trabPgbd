<?php
    include_once 'C:/wamp/www/back/conexao.php';

    include_once 'professor/professor.php';
    include_once 'disciplina/disciplina.php';


    $resultado = '';
    if(isset($_POST['buscaDisciplina'])){
        $resultado = buscaDisciplina();
    }

    if(isset($_POST['buscaProfessor'])){
        $resultado = buscaProfessor();
    }

    if(isset($_POST['editaProfessor'])){
        $resultado = editaProfessor();
    }

    if(isset($_POST['excluiProfessor'])){
        $resultado = excluiProfessor();
    }

    echo $resultado;
?>
