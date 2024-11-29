<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('authors', AuthorController::class);

    Route::apiResource('tickets', TicketController::class)->except('update');
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);

    Route::apiResource('authors.tickets', AuthorTicketsController::class)->except('update');
    Route::put('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace']);
});








// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
