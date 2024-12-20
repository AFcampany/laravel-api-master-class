<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TestController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('tickets', TicketController::class)->except('update');
    Route::patch('tickets/{ticket}', [TicketController::class, 'update']);
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);

    Route::apiResource('authors', AuthorController::class)
        ->except(['store', 'update', 'destroy']);

    Route::apiResource('authors.tickets', AuthorTicketsController::class)->except('update');
    Route::patch('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'update']);
    Route::put('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace']);

    Route::apiResource('users', UserController::class)->except('update');
    Route::patch('users/{user}', [UserController::class, 'update']);
    Route::put('users/{user}', [UserController::class, 'replace']);
});

Route::get('/author', [TestController::class, 'index'])->middleware('auth:sanctum');







// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
