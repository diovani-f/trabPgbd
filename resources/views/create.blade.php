@extends('layouts.app')

@section('content')
    <h1>Adicionar Disciplina</h1>
    <form action="{{ route('disciplinas.store') }}" method="POST">
        @csrf
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" id="codigo" required>
        <label for="creditos">Créditos:</label>
        <input type="number" name="creditos" id="creditos" required>
        <button type="submit">Salvar</button>
    </form>
@endsection
