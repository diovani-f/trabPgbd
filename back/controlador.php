<?php
    include_once 'conexao.php';

    include_once 'professor/professor.php';
    include_once 'disciplina/disciplina.php';
    include_once 'curso/curso.php';
    include_once 'sala/sala.php';


    $acoes = [
        'buscarDisciplina'    => 'buscarDisciplina',
        'excluirDisciplina'  => 'excluirDisciplina',
        'criarDisciplina'    => 'criarDisciplina',
        'editarDisciplina'   => 'editarDisciplina',

        'buscarProfessor'     => 'buscarProfessor',
        'editarProfessor'     => 'editarProfessor',
        'excluirProfessor'    => 'excluirProfessor',
        'criarProfessor'     => 'criarProfessor',

        'buscarCurso'     => 'buscarCurso',
        'editarCurso'     => 'editarCurso',
        'excluirCurso'    => 'excluirCurso',
        'criarCurso'     => 'criarCurso',

        'buscarSala'     => 'buscarSala',
        'editarSala'     => 'editarSala',
        'excluirSala'    => 'excluirSala',
        'criarSala'     => 'criarSala',
        'criarAula'     => 'criarAula',
        'buscarDisciplina_listagem' => 'buscarDisciplina_listagem'
    ];

    $resultado = "";

    foreach ($acoes as $chave => $funcao) {
        if (isset($_POST[$chave])) {
            $resultado = $funcao($_POST);
            break; 
        }
    }

    file_put_contents('resultado.txt',print_r($resultado, true) . PHP_EOL ,FILE_APPEND);


    echo $resultado;
?>
