<!-- resources/views/disciplinas/grade.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <!-- Botões para navegar entre semanas -->
        <a href="{{ route('disciplinas.index', ['semana' => 'anterior']) }}" class="btn btn-primary">Semana Anterior</a>
        <h2>Grade Semanal: {{ $dataSemanaAtual->format('d/m/Y') }} - {{ $dataSemanaAtual->addDays(6)->format('d/m/Y') }}</h2>
        <a href="{{ route('disciplinas.index', ['semana' => 'seguinte']) }}" class="btn btn-primary">Semana Seguinte</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Horário</th>
                <th>Segunda</th>
                <th>Terça</th>
                <th>Quarta</th>
                <th>Quinta</th>
                <th>Sexta</th>
                <th>Sábado</th>
                <th>Domingo</th>
            </tr>
        </thead>
        <tbody>
            @for ($hora = 0; $hora < 24; $hora++)
                <tr>
                    <td>{{ $hora }}:00</td>
                    @foreach(range(0, 6) as $dia)
                        <td>
                            @php
                                $diaSemana = $dataSemanaAtual->copy()->addDays($dia);  // Calcula a data do dia da semana
                            @endphp
                            <div>{{ $diaSemana->format('d/m') }}</div>
                            <div>Exemplo de evento</div>
                        </td>
                    @endforeach
                </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection
