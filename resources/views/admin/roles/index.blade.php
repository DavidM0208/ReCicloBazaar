@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-shield-lock"></i> Gestión de Roles
        </h5>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Nuevo Rol
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Nombre del Rol</th>
                        <th>Usuarios Asignados</th>
                        <th>Permisos</th>
                        <th>Fecha Creación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="ps-3"><span class="badge bg-secondary">{{ $role->id }}</span></td>
                        <td>
                            @php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'editor' => 'primary',
                                    'lector' => 'success'
                                ];
                                $badgeColor = $roleColors[$role->name] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} p-2">
                                <i class="bi bi-shield"></i> {{ $role->name }}
                            </span>
                            @if($role->name === 'admin')
                                <span class="badge bg-warning ms-1">Protegido</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-people"></i> {{ $role->users_count ?? 0 }} usuarios
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $role->permissions->count() }} permisos
                            </span>
                        </td>
                        <td>
                            <small>
                                <i class="bi bi-calendar"></i> {{ $role->created_at->format('d/m/Y') }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if($role->name !== 'admin')
                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}', {{ $role->users_count ?? 0 }})">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                    <i class="bi bi-lock"></i> Protegido
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No hay roles registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-center">
            {{ $roles->links() }}
        </div>
    </div>
</div>

<script>
function confirmDelete(roleId, roleName, usersCount) {
    let message = `¿Estás seguro de eliminar el rol "${roleName}"?`;
    if (usersCount > 0) {
        message += `\n\n⚠️ ADVERTENCIA: Este rol tiene ${usersCount} usuario(s) asignado(s).\nAl eliminarlo, estos usuarios quedarán sin rol.`;
    }

    if (confirm(message)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/roles') }}/${roleId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
