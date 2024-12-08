<?php
include_once 'back/conexao.php';

$conn = conectarBanco();

// Ajuste do SELECT para incluir id_disciplina
$sql = "SELECT aula.*, 
               disciplina.id AS id_disciplina, 
               disciplina.nome AS nome_disciplina, 
               professor.nome AS nome_professor, 
               sala.numero AS sala_numero
        FROM aula 
        JOIN disciplina ON aula.id_disciplina = disciplina.id
        JOIN professor ON disciplina.id_professor = professor.id
        JOIN sala ON disciplina.id_sala = sala.numero";

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
    <title>Página Inicial</title>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
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

        .login-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .login-button:hover {
            background-color: #45a049;
        }

        #calendar {
            width: 80%; /* Reduzido para 80% da largura */
            height: 80%; /* Reduzido para 80% da altura */
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para destaque */
            background-color: #fff; /* Fundo branco */
            border-radius: 8px; /* Bordas arredondadas */
            padding: 10px; /* Espaçamento interno */
        }

        /* Estilos para o tooltip */
        .fc-event-tooltip {
            position: absolute;
            z-index: 9999;
            background: rgba(0,0,0,0.75);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
            pointer-events: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var tooltipEl;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // Exibe a grade semanal
                events: <?php echo $horario; ?>,
                eventMouseEnter: function(info) {
                    var extendedProps = info.event.extendedProps;
                    var tooltipContent = 
                        "Disciplina: " + info.event.title + "<br>" +
                        "ID da Disciplina: " + extendedProps.id_disciplina + "<br>" +
                        "Professor: " + extendedProps.professor + "<br>" +
                        "Sala: " + extendedProps.sala;

                    tooltipEl = document.createElement('div');
                    tooltipEl.className = 'fc-event-tooltip';
                    tooltipEl.innerHTML = tooltipContent;
                    document.body.appendChild(tooltipEl);

                    var rect = info.el.getBoundingClientRect();
                    tooltipEl.style.top = rect.top + window.scrollY + "px";
                    tooltipEl.style.left = rect.left + window.scrollX + "px";
                },
                eventMouseLeave: function(info) {
                    if (tooltipEl) {
                        tooltipEl.remove();
                        tooltipEl = null;
                    }
                },
                eventMouseMove: function(info) {
                    if (tooltipEl) {
                        var rect = info.el.getBoundingClientRect();
                        tooltipEl.style.top = (rect.top + window.scrollY - tooltipEl.offsetHeight - 5) + "px";
                        tooltipEl.style.left = (rect.left + window.scrollX) + "px";
                    }
                }
            });
            calendar.render();
        });
    </script>
</head>
<body>
    <a href="login.php" class="login-button">Login</a>
    <div id="calendar"></div>
</body>
</html>
