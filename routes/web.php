<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportController;

// Redirigir la página principal a /reports
Route::get('/', function () {
    return redirect()->route('reports.index');
});

Route::get('/student/{id}', [StudentController::class, 'show'])->name('student.show');
Route::post('/student/record/{recordId}/update', [StudentController::class, 'updateRecord'])->name('student.updateRecord');
Route::post('/student/{id}/record/add', [StudentController::class, 'storeRecord'])->name('student.storeRecord');

Route::get('/student/{student}/performance/{record}/recommend', [StudentController::class, 'recommend'])->name('student.recommend');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports/predict', [ReportController::class, 'predictRecord'])->name('reports.predictRecord');
Route::post('/reports/batch-predict', [ReportController::class, 'batchPredict'])->name('reports.batchPredict');

Route::get('/reports/{student}/performance/{record}/details', [ReportController::class, 'viewDetails'])->name('reports.viewDetails');

// Ruta fallback para páginas no encontradas (404)
Route::fallback(function(){
    return response()->view('errors.404', [], 404);
});
