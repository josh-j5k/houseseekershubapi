<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ProfileController;

Route::prefix('listings')->group(function () {
    Route::get('/', [ListingController::class, 'index'])->name('listings');
    Route::get('/{id}', [ListingController::class, 'show'])->name('listings.show');

});

Route::post('/contact', [ContactController::class, 'store']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/authorized/{id}', [ListingController::class, 'isAuthenticated'])->name('listings.show.authenticate');
    Route::prefix("bookmark")->group(function () {
        Route::get('/', [BookmarkController::class, 'index']);
        Route::post('/{listing}', [BookmarkController::class, 'store']);

    });
    Route::prefix("messages")->group(function () {
        Route::get('/users', [MessageController::class, 'getUsers']);
        Route::get('/', [MessageController::class, 'index']);
        Route::get('/{message}', [MessageController::class, 'chats']);
        Route::post('/', [MessageController::class, 'store']);

    });
    Route::prefix("profile")->group(function () {
        Route::post('/update/{ref}/avatar', [ProfileController::class, 'updateAvatar']);
        Route::post('/update/{ref}/user', [ProfileController::class, 'update']);


    });
    Route::prefix('listings')->group(function () {
        Route::post('/store', [ListingController::class, 'store'])->name('listings.store');
        Route::post('/update/{id}', [ListingController::class, 'update'])->name('listings.update');
        Route::delete('/delete/{id}', [ListingController::class, 'destroy'])->name('listings.delete');
        Route::get('/user/listings', [ListingController::class, 'userListings'])->name('user.listings');
    });
});

require __DIR__ . '/auth.php';


