<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== CREAR PERMISOS (evitando duplicados) ==========
        $permissions = [
            'ver roles', 'crear roles', 'editar roles', 'eliminar roles',
            'ver usuarios', 'editar usuarios', 'eliminar usuarios',
            'asignar roles', 'asignar permisos',
            'ver dashboard', 'ver reportes',
        ];

        foreach ($permissions as $permission) {
            // Usar firstOrCreate en lugar de create
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ========== CREAR ROLES (evitando duplicados) ==========
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $lectorRole = Role::firstOrCreate(['name' => 'lector', 'guard_name' => 'web']);

        // Asignar permisos (sync sin duplicar)
        $adminRole->syncPermissions(Permission::all());

        $editorRole->syncPermissions([
            'ver usuarios', 'ver dashboard', 'ver reportes'
        ]);

        $lectorRole->syncPermissions(['ver dashboard']);

        // ========== CREAR USUARIOS DE PRUEBA (evitando duplicados) ==========
        // Usuario Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@reciclo.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password123')
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Usuario Editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@reciclo.com'],
            [
                'name' => 'Editor',
                'password' => bcrypt('password123')
            ]
        );
        if (!$editor->hasRole('editor')) {
            $editor->assignRole('editor');
        }

        // Usuario Lector
        $lector = User::firstOrCreate(
            ['email' => 'lector@reciclo.com'],
            [
                'name' => 'Lector',
                'password' => bcrypt('password123')
            ]
        );
        if (!$lector->hasRole('lector')) {
            $lector->assignRole('lector');
        }

        $this->command->info('✅ Roles y permisos creados/actualizados exitosamente!');
        $this->command->info('📧 admin@reciclo.com / password123');
        $this->command->info('📧 editor@reciclo.com / password123');
        $this->command->info('📧 lector@reciclo.com / password123');
    }
}
