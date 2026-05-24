<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit-role', [UserController::class, 'editRole'])->name('users.edit-role');
    Route::put('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');

    // Gestión de roles (con permisos)
    Route::get('/roles', [RoleController::class, 'index'])->middleware('can:ver roles')->name('roles');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('can:crear roles')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('can:crear roles')->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->middleware('can:editar roles')->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('can:editar roles')->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('can:eliminar roles')->name('roles.destroy');

    // Gestión de permisos
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/role/{roleId}', [PermissionController::class, 'getRolePermissions'])->name('permissions.role');
    Route::put('/permissions/role/{roleId}', [PermissionController::class, 'updateRolePermissions'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('/permissions/user/{userId}', [PermissionController::class, 'userPermissions'])->name('permissions.user');
});

require __DIR__.'/auth.php';
