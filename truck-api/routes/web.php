<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RaceController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\RaceParticipantController;
use App\Http\Controllers\Admin\OddController;
use App\Http\Controllers\Admin\BetController;
use App\Http\Controllers\Admin\BetTypeController;



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    
// CRUD Usuários
Route::resource('/users', UserController::class)->except(['show', 'create', 'store']);
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Corridas e Pilotos
Route::resource('races', RaceController::class);
Route::resource('drivers', DriverController::class);

// Participantes das Corridas
Route::get('races/participants/select', [RaceParticipantController::class, 'selectRace'])
    ->name('races.participants.selectRace');
Route::resource('races.participants', RaceParticipantController::class)
    ->names('races.participants');
Route::resource('races', RaceController::class); // Inclui o método show

// rota Odd
Route::prefix('odds')->name('odds.')->group(function () {
    Route::get('select-race', [OddController::class, 'selectRace'])->name('selectRace');
    Route::get('create/{race}', [OddController::class, 'create'])->name('create');
    Route::post('store/{race}', [OddController::class, 'store'])->name('store');
    Route::get('show/{race}', [OddController::class, 'show'])->name('show');
});

// Rotas de Apostas (configuração das bets)

Route::resource('bets', BetController::class)
    ->only(['index', 'create', 'store', 'show']);
//Rota tipos aposta
Route::resource('bettypes', BetTypeController::class);
});

require __DIR__.'/auth.php';
