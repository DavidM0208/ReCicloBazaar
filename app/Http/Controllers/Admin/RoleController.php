<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver roles')->only('index');
        $this->middleware('can:crear roles')->only('create', 'store');
        $this->middleware('can:editar roles')->only('edit', 'update');
        $this->middleware('can:eliminar roles')->only('destroy');
    }

    /**
     * Mostrar listado de roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->paginate(10);

        // Contar usuarios por rol
        foreach ($roles as $role) {
            $role->users_count = DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->count();
        }

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario para crear rol
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Agrupar permisos por categoría
            $prefix = explode(' ', $permission->name)[0] ?? 'otros';
            return $prefix;
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Guardar nuevo rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|min:3|max:50|regex:/^[a-z][a-z0-9\-_]+$/',
            'permissions' => 'array'
        ], [
            'name.regex' => 'El nombre del rol solo puede contener letras minúsculas, números, guiones y guiones bajos'
        ]);

        try {
            // Crear rol
            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

            // Asignar permisos
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            Log::info('Rol creado', [
                'admin' => auth()->user()->email,
                'rol' => $request->name,
                'permisos' => $request->permissions
            ]);

            return redirect()->route('admin.roles')
                ->with('success', "✅ Rol '{$request->name}' creado correctamente");

        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al crear el rol: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar rol
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Verificar si es rol protegido
        $isProtected = in_array($role->name, ['admin']);

        $permissions = Permission::all()->groupBy(function ($permission) {
            $prefix = explode(' ', $permission->name)[0] ?? 'otros';
            return $prefix;
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'isProtected'));
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Proteger rol admin
        if ($role->name === 'admin') {
            return back()->with('error', '❌ El rol "admin" está protegido y no puede ser modificado');
        }

        $request->validate([
            'name' => 'required|min:3|max:50|regex:/^[a-z][a-z0-9\-_]+$/|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        try {
            // Actualizar nombre
            $role->update(['name' => $request->name]);

            // Sincronizar permisos
            $role->syncPermissions($request->permissions ?? []);

            Log::info('Rol actualizado', [
                'admin' => auth()->user()->email,
                'rol' => $request->name,
                'permisos' => $request->permissions
            ]);

            return redirect()->route('admin.roles')
                ->with('success', "✅ Rol '{$request->name}' actualizado correctamente");

        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar rol
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Proteger rol admin
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles')
                ->with('error', '❌ El rol "admin" está protegido y no puede ser eliminado');
        }

        try {
            // Contar usuarios con este rol
            $usersCount = DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->count();

            // Eliminar rol
            $roleName = $role->name;
            $role->delete();

            Log::info('Rol eliminado', [
                'admin' => auth()->user()->email,
                'rol' => $roleName,
                'usuarios_afectados' => $usersCount
            ]);

            $message = "✅ Rol '{$roleName}' eliminado correctamente";
            if ($usersCount > 0) {
                $message .= " ({$usersCount} usuarios quedaron sin rol asignado)";
            }

            return redirect()->route('admin.roles')->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al eliminar el rol: ' . $e->getMessage());
        }
    }
}
