<?php

use App\Http\Controllers\PersonasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home.index', [
        'totalPersonas' => \App\Models\Personas::count(),
        'enPapelera'    => \App\Models\Personas::onlyTrashed()->count(),
    ]);
})->name('home.index');

Route::prefix('people')->group(function () {
    Route::get('/', [PersonasController::class, 'index'])->name('personas.index');
    Route::get('/create', [PersonasController::class, 'create'])->name('personas.create');
    Route::post('/store', [PersonasController::class, 'store'])->name('personas.store');
    Route::get('/edit/{id}', [PersonasController::class, 'edit'])->name('personas.edit');
    Route::put('/update/{id}', [PersonasController::class, 'update'])->name('personas.update');
    Route::get('/show', [PersonasController::class, 'show'])->name('personas.show');
    Route::get('/destroy/{id}', [PersonasController::class, 'destroy'])->name('personas.destroy');
    Route::get('/trash', [PersonasController::class, 'trash'])->name('personas.trash');
    Route::get('/restore/{id}', [PersonasController::class, 'restore'])->name('personas.restore');
    Route::get('/force-delete/{id}', [PersonasController::class, 'forceDelete'])->name('personas.forceDelete');
});
