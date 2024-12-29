<?php
include_once 'back/conexao.php';

$conn = conectarBanco();

// Obtenção dos cursos para o filtro
$sql_cursos = "SELECT id, nome FROM curso";
$resultado_cursos = $conn->query($sql_cursos);
$cursos = $resultado_cursos->fetch_all(MYSQLI_ASSOC);

// Obtenção dos filtros
$id_curso = isset($_GET['id_curso']) ? (int)$_GET['id_curso'] : null;
$nome_professor = isset($_GET['nome_professor']) ? $_GET['nome_professor'] : '';
$id_disciplina = isset($_GET['id_disciplina']) ? (int)$_GET['id_disciplina'] : null;
$nome_disciplina = isset($_GET['nome_disciplina']) ? $_GET['nome_disciplina'] : '';

// Construção da query com base nos filtros
$sql = "SELECT aula.*, 
               disciplina.id AS id_disciplina, 
               disciplina.nome AS nome_disciplina, 
               professor.nome AS nome_professor, 
               sala.numero AS sala_numero
        FROM aula 
        JOIN disciplina ON aula.id_disciplina = disciplina.id
        JOIN professor ON disciplina.id_professor = professor.id
        JOIN sala ON disciplina.id_sala = sala.numero
        WHERE 1=1";

// Filtros opcionais
if ($id_curso) {
    $sql .= " AND disciplina.id_curso = $id_curso";
}
if (!empty($nome_professor)) {
    $sql .= " AND professor.nome LIKE '%" . $conn->real_escape_string($nome_professor) . "%'";
}
if ($id_disciplina) {
    $sql .= " AND disciplina.id = $id_disciplina";
}
if (!empty($nome_disciplina)) {
    $sql .= " AND disciplina.nome LIKE '%" . $conn->real_escape_string($nome_disciplina) . "%'";
}

$resultado = $conn->query($sql);
$horarios = $resultado->fetch_all(MYSQLI_ASSOC);

$horario = array();
$dias_da_semana = array(
    'Segunda' => 1,
    'Terça' => 2,
    'Quarta' => 3,
    'Quinta' => 4,
    'Sexta' => 5,
    'Sabado' => 6,
    'Domingo' => 7
);

foreach ($horarios as $evento) {
    $data_inicio = new DateTime($evento['data_inicio']);
    $data_final = new DateTime($evento['data_final']);
    $dias = $data_inicio->diff($data_final)->days;
    $dia = $data_inicio->format('Y-m-d');
    
    for ($i = 0; $i <= $dias; $i++) {
        $dia_da_semana = date('N', strtotime($dia));
        
        if ($dia_da_semana == $dias_da_semana[$evento['dia_da_semana']]) {
            $horario[] = array(
                'id' => $evento['id'],
                'title' => $evento['nome_disciplina'],
                'start' => date('Y-m-d H:i:s', strtotime($dia . ' ' . $evento['horario_inicio'])),
                'end' => date('Y-m-d H:i:s', strtotime($dia . ' ' . $evento['horario_fim'])),
                'description' => "Professor: " . $evento['nome_professor'] . " | Sala: " . $evento['sala_numero'],
                'color' => "green",
                'url' => 'detalhes_evento.php?id=' . $evento['id'],
                'extendedProps' => array(
                    'id_disciplina' => $evento['id_disciplina'],
                    'professor' => $evento['nome_professor'],
                    'sala' => $evento['sala_numero']
                )
            );
        }
        $dia = date('Y-m-d', strtotime($dia . ' +1 day'));
    }
}

$horario = json_encode($horario);
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial com Filtros</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background-color: #f4f4f9;
        }

        .filters {
            margin: 20px;
            display: flex;
            gap: 10px;
        }

        .filters input, .filters select {
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .filters button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .filters button:hover {
            background-color: #45a049;
        }

        #calendar {
            width: 80%;
            height: 80%;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            padding: 10px;
        }

        /* Tooltip */
        .fc-event-tooltip {
            position: absolute;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9rem;
            pointer-events: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<form class="filters" id="filters-form">
        <select name="id_curso" id="id_curso">
            <option value="">Todos os cursos</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id']); ?>" <?= $id_curso == $curso['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($curso['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="nome_professor" placeholder="Nome do professor" value="<?= htmlspecialchars($nome_professor); ?>">
        <input type="number" name="id_disciplina" placeholder="ID da disciplina" value="<?= ($id_disciplina); ?>">
        <input type="text" name="nome_disciplina" placeholder="Nome da disciplina" value="<?= htmlspecialchars($nome_disciplina); ?>">
        <button type="submit">Filtrar</button>
        <button type="button" onclick="window.location.href='painel_admin.php'">Logar</button>
    </form>
    <div id="calendar"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var tooltipEl; // Armazena o elemento do tooltip

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pt-br',
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia',
                list: 'Lista'
            },
            allDayText: 'Horas',
            initialView: 'timeGridWeek',
            events: <?php echo $horario; ?>,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,dayGridMonth',
            },
            eventMouseEnter: function (info) {
                // Verifica se as propriedades adicionais estão disponíveis
                var extendedProps = info.event.extendedProps;
                var tooltipContent =
                    `<strong>Disciplina:</strong> ${info.event.title}<br>` +
                    `<strong>ID:</strong> ${extendedProps.id_disciplina}<br>` +
                    `<strong>Professor:</strong> ${extendedProps.professor}<br>` +
                    `<strong>Sala:</strong> ${extendedProps.sala}`;

                // Cria o elemento do tooltip
                tooltipEl = document.createElement('div');
                tooltipEl.className = 'fc-event-tooltip';
                tooltipEl.innerHTML = tooltipContent;
                document.body.appendChild(tooltipEl);

                // Posiciona o tooltip
                var rect = info.el.getBoundingClientRect();
                tooltipEl.style.top = rect.top + window.scrollY + 5 + 'px';
                tooltipEl.style.left = rect.left + window.scrollX + 5 + 'px';
            },
            eventMouseLeave: function () {
                // Remove o tooltip quando o mouse sai
                if (tooltipEl) {
                    tooltipEl.remove();
                    tooltipEl = null;
                }
            },
            eventMouseMove: function (info) {
                // Atualiza a posição do tooltip enquanto o mouse se move
                if (tooltipEl) {
                    var rect = info.el.getBoundingClientRect();
                    tooltipEl.style.top = rect.top + window.scrollY + 5 + 'px';
                    tooltipEl.style.left = rect.left + window.scrollX + 5 + 'px';
                }
            },
        });

        calendar.render();
    });
    </script>
</body>
</html>

