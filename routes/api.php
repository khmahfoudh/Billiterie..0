<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Organisateur\OrganisateurController;

Route::prefix('client')->group(function () {
    Route::post('/register', [ClientController::class, 'register']);
    Route::post('/login', [ClientController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [ClientController::class, 'showProfile']);
        Route::put('/profile', [ClientController::class, 'updateProfile']);

        Route::get('/competitions', [ClientController::class, 'listCompetitions']);
        Route::get('/competitions/{id}/matches', [ClientController::class, 'listMatchesByCompetition']);

        Route::post('/tickets', [ClientController::class, 'purchaseTicket']);
        Route::put('/tickets/{id}', [ClientController::class, 'updateTicket']);
        Route::delete('/tickets/{id}', [ClientController::class, 'cancelTicket']);
    });
});



Route::prefix('organisateur')->group(function () {
    Route::post('/register', [OrganisateurController::class, 'register']);
    Route::post('/login', [OrganisateurController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [OrganisateurController::class, 'showProfile']);
        Route::put('/profile', [OrganisateurController::class, 'updateProfile']);

        Route::get('/matches', [OrganisateurController::class, 'listMyMatches']);
        Route::post('/matches', [OrganisateurController::class, 'createMatch']);
        Route::delete('/matches/{id}', [OrganisateurController::class, 'deleteMatch']);
    });
});

