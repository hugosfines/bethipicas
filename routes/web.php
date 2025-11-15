<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Bet\BetController;
use App\Http\Controllers\Ticket\TicketController;
use App\Livewire\Admin\Calendar\CalendarCreate;
use App\Livewire\Admin\Calendar\CalendarCrud;
use App\Livewire\Admin\Result\ResultCreate;
use App\Livewire\Admin\Manage\ManageCalendars;
use App\Livewire\Admin\Manage\ManageMultipleCalendars;

Route::get('/', function () {
    return view('pageframe');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'can:sales',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('apuestas-hipicas', [BetController::class, 'viewFormBetMenu'])
        ->name('ticket.office');

    /* Route::get('/tickets/imprimir/{ticket}', [TicketController::class, 'print'])
        ->name('tickets.imprimir'); */
    Route::post('/tickets/imprimir', [TicketController::class, 'imprimir'])
        ->name('tickets.imprimir');

    Route::get('/results/create', ResultCreate::class)
        ->name('results.create');

    Route::get('/calendarios', CalendarCreate::class)
        ->middleware(['role:SuperAdmin|Admin'])
        ->name('calendars.index');

    Route::get('/administrar-jornadas', ManageCalendars::class)
        ->middleware(['role:SuperAdmin|Admin'])
        ->name('manage.selected');
        
    Route::get('/administrar', ManageMultipleCalendars::class)
        ->middleware(['role:SuperAdmin|Admin'])
        ->name('manage.index');
});
