<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('id')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostrar formulario para editar rol de un usuario
     */
    public function editRole(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->first();
        return view('admin.users.edit-role', compact('user', 'roles', 'userRole'));
    }

    /**
     * Actualizar el rol del usuario
     */
    public function updateRole(Request $request, User $user)
    {
        // Validar que el rol existe
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // NO permitir que un admin se quite su propio rol admin
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()
                ->route('admin.users')
                ->with('error', '❌ No puedes cambiar tu propio rol de administrador');
        }

        // Guardar el rol anterior para el log
        $oldRole = $user->roles->first()->name ?? 'sin rol';

        // Asignar el nuevo rol
        $user->syncRoles([$request->role]);

        // Registrar en log la actividad
        Log::info('Rol de usuario actualizado', [
            'admin' => auth()->user()->email,
            'usuario' => $user->email,
            'rol_anterior' => $oldRole,
            'rol_nuevo' => $request->role
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', "✅ Rol de {$user->name} actualizado a: {$request->role}");
    }
}
