<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TreeImageController;
use App\Http\Controllers\Admin\ImageApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TreeController::class, 'index'])->name('trees.index');
Route::get('/trees', [TreeController::class, 'index']);
Route::get('/trees/{tree}', [TreeController::class, 'show'])->name('trees.show');
Route::get('/api/trees', [TreeController::class, 'getTreesJson'])->name('trees.json');
Route::post('/trees/sync', [TreeController::class, 'sync'])->name('trees.sync');

Route::get('/dashboard', function () {
    return redirect()->route('trees.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/trees/{tree}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    Route::post('/trees/{tree}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    
    Route::post('/trees/{tree}/images', [TreeImageController::class, 'store'])->name('images.store');
    Route::delete('/images/{image}', [TreeImageController::class, 'destroy'])->name('images.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/images', [ImageApprovalController::class, 'index'])->name('images.index');
    Route::post('/images/{image}/approve', [ImageApprovalController::class, 'approve'])->name('images.approve');
    Route::post('/images/{image}/reject', [ImageApprovalController::class, 'reject'])->name('images.reject');
});

require __DIR__.'/auth.php';
