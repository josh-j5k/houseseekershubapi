<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::prefix('listings')->group(function () {
    Route::get('/', [ListingController::class, 'index'])->name('listings');
    Route::get('/{listing}', [ListingController::class, 'show'])->name('listings');
});
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware(['auth:sanctum'])->group(function () {

});

require __DIR__ . '/auth.php';


