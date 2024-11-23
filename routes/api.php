<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::prefix('listings')->group(function () {
    Route::get('/', [ListingController::class, 'index'])->name('listings');
    Route::get('/{ref}', [ListingController::class, 'show'])->name('listings.show');

});

Route::post('/contact', [ContactController::class, 'store']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix("messages")->group(function () {
        Route::get('/', [MessageController::class, 'index']);
        Route::get('/{message}', [MessageController::class, 'chats']);
        Route::post('/message', [MessageController::class, 'store']);

    });
    Route::prefix('listings')->group(function () {
        Route::post('/store', [ListingController::class, 'store'])->name('listings.store');
        Route::put('/update', [ListingController::class, 'update'])->name('listings.update');
        Route::delete('/delete/{ref}', [ListingController::class, 'update'])->name('listings.delete');
        Route::get('/user/listings', [ListingController::class, 'userListings'])->name('user.listings');
    });
});

require __DIR__ . '/auth.php';


