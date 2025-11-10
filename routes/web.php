<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $questionnaires = auth()->user()->questionnaires()->with('result')->latest()->get();
    return view('dashboard', compact('questionnaires'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Questionnaire routes
    Route::resource('questionnaires', \App\Http\Controllers\QuestionnaireController::class)->only(['create', 'store', 'show', 'update']);
    
    // Result routes
    Route::get('/results/{result}', [\App\Http\Controllers\ResultController::class, 'show'])->name('results.show');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Questions routes - IMPORTANT: Put specific routes BEFORE resource route
    Route::get('/questions/export', [\App\Http\Controllers\Admin\QuestionController::class, 'export'])->name('questions.export');
    Route::get('/questions/import', [\App\Http\Controllers\Admin\QuestionController::class, 'showImport'])->name('questions.import.show');
    Route::post('/questions/import', [\App\Http\Controllers\Admin\QuestionController::class, 'import'])->name('questions.import.store');
    Route::get('/questions/template', [\App\Http\Controllers\Admin\QuestionController::class, 'downloadTemplate'])->name('questions.template');
    Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class);
    
    // Results routes
    Route::get('/results/export/excel', [\App\Http\Controllers\Admin\ExportController::class, 'exportExcel'])->name('results.export.excel');
    Route::resource('results', \App\Http\Controllers\Admin\ResultController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
