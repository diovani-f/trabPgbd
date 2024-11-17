<!-- resources/views/disciplinas/index.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disciplinas</title>
</head>
<body>
    <h1>Lista de Disciplinas</h1>
    <ul>
        @foreach ($disciplinas as $disciplina)
            <li>{{ $disciplina->nome }}</li>
        @endforeach
    </ul>
</body>
</html>
