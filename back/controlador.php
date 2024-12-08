<?php
header('Content-Type: application/json');

// Inclui as funções
include_once 'conexao.php';
include_once 'curso/curso.php';
include_once 'disciplina/disciplina.php';
include_once 'professor/professor.php';
include_once 'sala/sala.php';



$acoes = [
    'buscarDisciplina'    => 'buscarDisciplina',
    'excluirDisciplina'   => 'excluirDisciplina',
    'criarDisciplina'     => 'criarDisciplina',
    'editarDisciplina'    => 'editarDisciplina',

    'buscarProfessor'     => 'buscarProfessor',
    'editarProfessor'     => 'editarProfessor',
    'excluirProfessor'    => 'excluirProfessor',
    'criarProfessor'      => 'criarProfessor',

    'buscarCurso'         => 'buscarCurso',
    'editarCurso'         => 'editarCurso',
    'excluirCurso'        => 'excluirCurso',
    'criarCurso'          => 'criarCurso',

    'criarAula'       => 'criarAula',
    'buscarAula'      => 'buscarAula',
    'editarAula'      => 'editarAula',
    'excluirAula'     => 'excluirAula',

    'buscarSala'          => 'buscarSala',
    'editarSala'          => 'editarSala',
    'excluirSala'         => 'excluirSala',
    'criarSala'           => 'criarSala',
];

$resultado = "";

// Verifica qual ação chegou via POST e executa
foreach ($acoes as $chave => $funcao) {
    if (isset($_POST[$chave])) {
        $resultado = $funcao($_POST);
        break; 
    }
}

// Retorno final (o echo já está nas funções, então aqui pode não ter nada)
// Caso a função retorne dados usando echo, já basta.
