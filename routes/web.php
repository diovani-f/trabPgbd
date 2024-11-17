<?php

use App\Http\Controllers\DisciplinaController;

Route::get('/disciplinas', [DisciplinaController::class, 'index'])->name('disciplinas.index');
