<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aula</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            margin-top: 50px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 1em;
            color: #555;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            padding: 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            font-size: 1.1em;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
            font-size: 1.1em;
        }

        .back-link:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastrar Aula</h2>
    <form method="POST" action="back/controlador.php">
        <input type="hidden" name="criarAula" value="criarAula">

        <label for="id_disciplina">ID da Disciplina:</label>
        <input type="number" id="id_disciplina" name="id_disciplina" required>

        <label for="dia_da_semana">Dia da Semana:</label>
        <select id="dia_da_semana" name="dia_da_semana" required>
            <option value="">Selecione...</option>
            <option value="Segunda">Segunda</option>
            <option value="Terça">Terça</option>
            <option value="Quarta">Quarta</option>
            <option value="Quinta">Quinta</option>
            <option value="Sexta">Sexta</option>
            <option value="Sábado">Sábado</option>
            <option value="Domingo">Domingo</option>
        </select>

        <label for="horario_inicio">Horário Início (HH:MM:SS):</label>
        <input type="time" id="horario_inicio" name="horario_inicio" required>

        <label for="horario_fim">Horário Fim (HH:MM:SS):</label>
        <input type="time" id="horario_fim" name="horario_fim" required>

        <label for="data_inicio">Data Início (YYYY-MM-DD):</label>
        <input type="date" id="data_inicio" name="data_inicio" required>

        <label for="data_final">Data Final (YYYY-MM-DD):</label>
        <input type="date" id="data_final" name="data_final" required>

        <button type="submit">Cadastrar Aula</button>
    </form>

    <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
</div>

</body>
</html>
