<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')
    ->apiResource('tickets', TicketController::class);

Route::middleware('auth:sanctum')
    ->apiResource('authors', AuthorController::class);

Route::middleware('auth:sanctum')
    ->apiResource('authors.tickets', AuthorTicketsController::class);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
