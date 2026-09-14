<?php

use App\Http\Controllers\CustomerReviewController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/avis-clients', [CustomerReviewController::class, 'index'])->name('vvs.reviews.index');
Route::post('/avis-clients', [CustomerReviewController::class, 'store'])->middleware('throttle:3,60')->name('vvs.reviews.store');
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/dashboard/avis', [CustomerReviewController::class, 'moderate'])->name('vvs.reviews.moderate');
    Route::patch('/dashboard/avis/{review}', [CustomerReviewController::class, 'update'])->whereNumber('review')->middleware('throttle:60,1')->name('vvs.reviews.update');
});
