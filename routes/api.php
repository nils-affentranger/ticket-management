<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\KinoController;
use App\Http\Controllers\BesuchController;
use App\Http\Controllers\SpracheController;
use App\Http\Controllers\TypController;
use App\Http\Controllers\SaalController;
use App\Http\Controllers\EinstellungController;
use Illuminate\Support\Facades\Route;
use OpenApi\Annotations as OA;

// Film routes
Route::get('/filme', [FilmController::class, 'index']);
Route::post('/filme', [FilmController::class, 'store']);
Route::get('/filme/{id}', [FilmController::class, 'show']);
Route::put('/filme/{id}', [FilmController::class, 'update']);
Route::patch('/filme/{id}', [FilmController::class, 'update']);
Route::delete('/filme/{id}', [FilmController::class, 'destroy']);

// Kino routes
Route::get('/kinos', [KinoController::class, 'index']);
Route::post('/kinos', [KinoController::class, 'store']);
Route::get('/kinos/{kino}', [KinoController::class, 'show']);
Route::put('/kinos/{kino}', [KinoController::class, 'update']);
Route::patch('/kinos/{kino}', [KinoController::class, 'update']);
Route::delete('/kinos/{kino}', [KinoController::class, 'destroy']);

// Besuch routes
Route::get('/besuche', [BesuchController::class, 'index']);
Route::post('/besuche', [BesuchController::class, 'store']);
Route::get('/besuche/{besuch}', [BesuchController::class, 'show']);
Route::put('/besuche/{besuch}', [BesuchController::class, 'update']);
Route::patch('/besuche/{besuch}', [BesuchController::class, 'update']);
Route::delete('/besuche/{besuch}', [BesuchController::class, 'destroy']);

// Sprache routes
Route::get('/sprachen', [SpracheController::class, 'index']);
Route::post('/sprachen', [SpracheController::class, 'store']);
Route::get('/sprachen/{sprache}', [SpracheController::class, 'show']);
Route::put('/sprachen/{sprache}', [SpracheController::class, 'update']);
Route::patch('/sprachen/{sprache}', [SpracheController::class, 'update']);
Route::delete('/sprachen/{sprache}', [SpracheController::class, 'destroy']);

// Typ routes
Route::get('/typen', [TypController::class, 'index']);
Route::post('/typen', [TypController::class, 'store']);
Route::get('/typen/{typ}', [TypController::class, 'show']);
Route::put('/typen/{typ}', [TypController::class, 'update']);
Route::patch('/typen/{typ}', [TypController::class, 'update']);
Route::delete('/typen/{typ}', [TypController::class, 'destroy']);

// Saal routes
Route::get('/saele', [SaalController::class, 'index']);
Route::post('/saele', [SaalController::class, 'store']);
Route::get('/saele/{saal}', [SaalController::class, 'show']);
Route::put('/saele/{saal}', [SaalController::class, 'update']);
Route::patch('/saele/{saal}', [SaalController::class, 'update']);
Route::delete('/saele/{saal}', [SaalController::class, 'destroy']);

// Einstellungen routes
Route::get('/einstellungen', [EinstellungController::class, 'index']);
Route::post('/einstellungen', [EinstellungController::class, 'store']);
Route::get('/einstellungen/{key}', [EinstellungController::class, 'show']);
Route::put('/einstellungen/{key}', [EinstellungController::class, 'update']);
Route::delete('/einstellungen/{key}', [EinstellungController::class, 'destroy']);
Route::get('/einstellungen/value/{key}', [EinstellungController::class, 'getValue']);
