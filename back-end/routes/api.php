<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\OrdonnanceController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\PatientController;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);


    // 🌟 Spécialités
    Route::apiResource('specialites', SpecialiteController::class);

    // 🌟 Médecins
    Route::apiResource('medecins', MedecinController::class);

    // 🌟 Patients
    Route::apiResource('patients', PatientController::class);

    // 🌟 Rendez-vous (on peut utiliser apiResource pour tout gérer)
    Route::apiResource('rendezvous', RendezVousController::class);

    // Consultations, ordonnances, paiements
    Route::apiResource('consultations', ConsultationController::class);
    Route::apiResource('ordonnances', OrdonnanceController::class);
    Route::apiResource('paiements', PaiementController::class);

    // Users
    Route::get('/users',           [UserController::class, 'index']);
    Route::get('/users/{user}',    [UserController::class, 'show']);
    Route::put('/users/{user}',    [UserController::class, 'update']);
    Route::patch('/users/{user}',  [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
