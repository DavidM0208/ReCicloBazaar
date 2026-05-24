<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Mostrar vista de gestión de permisos
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Agrupar por categoría (primera palabra del nombre)
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'otros';
        });

        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Obtener permisos de un rol específico (para AJAX)
     */
    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = $role->permissions->pluck('name')->toArray();

        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
    }

    /**
     * Actualizar permisos de un rol (para AJAX)
     */
    public function updateRolePermissions(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($roleId);

        // Proteger rol admin
        if ($role->name === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'El rol admin está protegido'
            ], 403);
        }

        // Sincronizar permisos
        $role->syncPermissions($request->permissions ?? []);

        // Registrar en log
        Log::info('Permisos actualizados para rol', [
            'admin' => auth()->user()->email,
            'rol' => $role->name,
            'permisos' => $request->permissions
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permisos actualizados correctamente'
        ]);
    }

    /**
     * Mostrar formulario para crear nuevo permiso
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.permissions.create', compact('roles'));
    }

    /**
     * Guardar nuevo permiso
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name|min:3|max:50',
            'roles' => 'array'
        ]);

        try {
            // Crear permiso
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            // Asignar a roles seleccionados
            if ($request->has('roles')) {
                foreach ($request->roles as $roleId) {
                    $role = Role::find($roleId);
                    if ($role) {
                        $role->givePermissionTo($permission);
                    }
                }
            }

            Log::info('Nuevo permiso creado', [
                'admin' => auth()->user()->email,
                'permiso' => $request->name,
                'roles_asignados' => $request->roles
            ]);

            return redirect()->route('admin.permissions')
                ->with('success', "✅ Permiso '{$request->name}' creado correctamente");

        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al crear el permiso: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar permiso
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Proteger permisos esenciales
        $essentialPermissions = ['ver usuarios', 'ver roles', 'gestionar permisos'];
        if (in_array($permission->name, $essentialPermissions)) {
            return redirect()->route('admin.permissions')
                ->with('error', '❌ No se puede eliminar un permiso esencial del sistema');
        }

        try {
            $permissionName = $permission->name;
            $permission->delete();

            Log::info('Permiso eliminado', [
                'admin' => auth()->user()->email,
                'permiso' => $permissionName
            ]);

            return redirect()->route('admin.permissions')
                ->with('success', "✅ Permiso '{$permissionName}' eliminado correctamente");

        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al eliminar el permiso');
        }
    }

    /**
     * Ver permisos de un usuario específico
     */
    public function userPermissions($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $permissions = $user->getAllPermissions();

        return response()->json([
            'success' => true,
            'user' => $user->name,
            'rol' => $user->roles->first()->name ?? 'Sin rol',
            'permissions' => $permissions->pluck('name')
        ]);
    }
}
