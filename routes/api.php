<?php

use App\Http\Controllers\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('listings')->group(function () {
    Route::get('/', [ListingController::class, 'index'])->name('listings');
    Route::get('/{listing}', [ListingController::class, 'show'])->name('listings');
});
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::prefix('listings')->group(function () {
//         Route::get('/',[ListingController::class,'index'])->name('listings');
//         Route::get('/{listing}',[ListingController::class,'show'])->name('listings');
//     });
// });


