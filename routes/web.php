<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'home'])->name('home');

/* LeagueController */
Route::middleware(['auth'])->group(function () {
    Route::get('/leagues', [LeagueController::class, 'index'])->name('leagues.index');
    Route::get('/leagues/create', [LeagueController::class, 'create'])->name('leagues.create');
    Route::post('/leagues', [LeagueController::class, 'store'])->name('leagues.store');
    Route::get('/leagues/{league}', [LeagueController::class, 'show'])->name('leagues.show');

    Route::get('/join', [LeagueController::class, 'joinForm'])->name('leagues.joinForm');
    Route::post('/join', [LeagueController::class, 'join'])->name('leagues.join');
    Route::get('/join/{invite_code}', [LeagueController::class, 'joinWithLink'])->name('leagues.joinWithLink');
});

/* TeamController */
Route::get('/leagues/{league}/team/create', [TeamController::class, 'create'])->name('teams.create');
Route::post('/leagues/{league}/team', [TeamController::class, 'store'])->name('teams.store');

/* DraftController */
Route::middleware('auth')->group(function () {
    Route::get('/leagues/{league}/draft', [DraftController::class, 'showDraftPage'])->name('draft.show');
    Route::post('/leagues/{league}/draft/start', [DraftController::class, 'startDraft'])->name('draft.start');
});
