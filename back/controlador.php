<?php
session_start();

// Habilitar relatórios de erro (apenas em desenvolvimento)
// Descomente as linhas abaixo durante o desenvolvimento para ver erros detalhados
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

// Detecta se a requisição é AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Se for AJAX, define o tipo de conteúdo como JSON
if ($isAjax) {
    header('Content-Type: application/json');
}

// Inclui as funções necessárias
require_once 'conexao.php';
require_once 'curso/curso.php';
require_once 'disciplina/disciplina.php';
require_once 'professor/professor.php';
require_once 'sala/sala.php';

// Define as ações disponíveis
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

    'buscarSala'          => 'buscarSala',
    'editarSala'          => 'editarSala',
    'excluirSala'         => 'excluirSala',
    'criarSala'           => 'criarSala',
    // Aulas
    'criarAula'           => 'criarAula',
    'buscarAula'          => 'buscarAula',
    // Adicione outras ações conforme necessário
];

$resultado = [];

// Executa a ação correspondente
foreach ($acoes as $chave => $funcao) {
    if (isset($_POST[$chave])) {
        if (function_exists($funcao)) {
            $resultado = $funcao($_POST);
        } else {
            $resultado = ['erro' => "Função {$funcao} não existe."];
        }
        break; 
    }
}

// Responde com base no tipo de requisição
if ($isAjax) {
    // Responde com JSON para requisições AJAX
    echo json_encode($resultado);
} else {
    // Para requisições de formulário tradicionais
    if (isset($resultado['sucesso'])) {
        // Redireciona para a página administrativa com parâmetro de sucesso
        header('Location: ../painel_admin.php?sucesso=1');
        exit();
    } else if (isset($resultado['erro'])) {
        // Identifica qual ação foi tentada para redirecionar corretamente
        $action = '';
        foreach ($acoes as $chave => $funcao) {
            if (isset($_POST[$chave])) {
                $action = $chave;
                break;
            }
        }

        // Mapeia a ação para a página de formulário correspondente
        $actionToPage = [
            'criarAula' => 'cadastrar_aula.php',
            'criarCurso' => 'cadastrar_curso.php',
            'criarDisciplina' => 'cadastrar_disciplina.php',
            'criarProfessor' => 'cadastrar_professor.php',
            'criarSala' => 'cadastrar_sala.php',
            // Adicione outros mapeamentos conforme necessário
        ];

        if (array_key_exists($action, $actionToPage)) {
            $page = $actionToPage[$action];
            // Adiciona a mensagem de erro como parâmetro na URL
            header("Location: ../{$page}?erro=1");
            exit();
        } else {
            // Se a ação não estiver mapeada, redireciona para o painel administrativo com erro
            header("Location: ../painel_admin.php?erro=1");
            exit();
        }
    } else {
        // Caso não haja resposta específica, redireciona para o painel administrativo
        header("Location: ../painel_admin.php");
        exit();
    }
}
?>
