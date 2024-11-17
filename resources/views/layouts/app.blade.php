<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Semanal</title>
    <!-- Adicione aqui links para CSS e outros recursos -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <!-- Aqui entra o conteúdo específico de cada página -->
        @yield('content')
    </div>
</body>
</html>
