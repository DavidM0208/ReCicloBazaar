<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// O si quieres mantener welcome pero redirigir si no está autenticado:
// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect()->route('dashboard');
//     }
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Solo ADMIN puede acceder
    Route::get('/admin/panel', function () {
        return view('admin.panel');
    })->middleware('role:admin')->name('admin.panel');

    // ADMIN y EDITOR pueden acceder
    Route::get('/editor/panel', function () {
        return view('editor.panel');
    })->middleware('role:admin,editor')->name('editor.panel');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit-role', [UserController::class, 'editRole'])->name('users.edit-role');
    Route::put('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
});

require __DIR__.'/auth.php';
