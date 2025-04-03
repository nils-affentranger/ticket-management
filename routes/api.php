<?php

use App\Http\Controllers\FilmController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Get all films
Route::get('/filme', [FilmController::class, 'index']);

// Create a new film
Route::post('/filme', [FilmController::class, 'store']);

// Get a specific film
Route::get('/filme/{film}', [FilmController::class, 'show']);

// Update a specific film
Route::put('/filme/{film}', [FilmController::class, 'update']);
Route::patch('/filme/{film}', [FilmController::class, 'update']);

// Delete a specific film
Route::delete('/filme/{film}', [FilmController::class, 'destroy']);
