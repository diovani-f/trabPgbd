<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DisciplinaController extends Controller
{
    // Método para exibir a grade semanal
    public function index(Request $request)
    {
        // Data da segunda-feira da semana atual
        $dataSemanaAtual = Carbon::now()->startOfWeek();

        // Verifica se a semana anterior ou seguinte foi solicitada e ajusta a data
        if ($request->has('semana')) {
            if ($request->semana == 'anterior') {
                // Subtrai uma semana
                $dataSemanaAtual = $dataSemanaAtual->subWeek();
            } elseif ($request->semana == 'seguinte') {
                // Adiciona uma semana
                $dataSemanaAtual = $dataSemanaAtual->addWeek();
            }
        }

        // Passa a data da semana para a view
        return view('disciplinas.grade', compact('dataSemanaAtual'));
    }
}
