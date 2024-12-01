<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('tickets', TicketController::class)->except('update');
    Route::apiResource('authors', AuthorController::class);

    Route::patch('tickets/{ticket}', [TicketController::class, 'update']);
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);

    Route::apiResource('authors.tickets', AuthorTicketsController::class)->except('update');
    Route::patch('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'update']);
    Route::put('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace']);

});

Route::get('/author', [TestController::class, 'index'])->middleware('auth:sanctum');







// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
