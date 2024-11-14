<?php
    include_once 'conexao.php';

    include_once 'professor/professor.php';
    include_once 'disciplina/disciplina.php';

    $resultado = '';

    if(isset($_POST['buscaDisciplina'])){
        $resultado = buscaDisciplina();
    }

    if(isset($_POST['excluirDisciplina'])){
        $resultado = excluirDisciplina();
    }

    if(isset($_POST['criarDisciplina'])){
        $resultado = criarDisciplina();
    }

    if(isset($_POST['editarDisciplina'])){
        $resultado = editarDisciplina();
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
