@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-key"></i> Gestión de Permisos
                </h5>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Nuevo Permiso
                </a>
            </div>
            <div class="card-body">
                <!-- Selector de roles -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-shield"></i> Seleccionar rol:
                    </label>
                    <select id="role-selector" class="form-select">
                        <option value="">-- Seleccione un rol --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $role->name === 'admin' ? 'disabled' : '' }}>
                                {{ ucfirst($role->name) }}
                                @if($role->name === 'admin')
                                    (Protegido)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">El rol "admin" tiene todos los permisos por defecto</small>
                </div>

                <!-- Indicador de carga -->
                <div id="loading-indicator" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando permisos...</p>
                </div>

                <!-- Panel de permisos -->
                <div id="permissions-panel" style="display: none;">
                    <form id="permissions-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="role-id" name="role_id">

                        @foreach($permissions as $categoria => $perms)
                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="bi bi-folder"></i> {{ ucfirst($categoria) }}
                                </h6>
                                <div class="row">
                                    @foreach($perms as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       class="form-check-input permission-checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       id="perm_{{ $permission->id }}">
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" id="save-permissions" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                <div id="no-role-selected" class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i>
                    Seleccione un rol para ver y editar sus permisos
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const roleSelector = document.getElementById('role-selector');
const permissionsPanel = document.getElementById('permissions-panel');
const loadingIndicator = document.getElementById('loading-indicator');
const noRoleSelected = document.getElementById('no-role-selected');
const saveButton = document.getElementById('save-permissions');
const roleIdInput = document.getElementById('role-id');
const checkboxes = document.querySelectorAll('.permission-checkbox');

// Cargar permisos al seleccionar un rol
roleSelector.addEventListener('change', async function() {
    const roleId = this.value;

    if (!roleId) {
        permissionsPanel.style.display = 'none';
        noRoleSelected.style.display = 'block';
        return;
    }

    // Mostrar loading
    permissionsPanel.style.display = 'none';
    noRoleSelected.style.display = 'none';
    loadingIndicator.style.display = 'block';

    try {
        const response = await fetch(`/admin/permissions/role/${roleId}`);
        const data = await response.json();

        if (data.success) {
            // Resetear checkboxes
            checkboxes.forEach(cb => cb.checked = false);

            // Marcar permisos del rol
            data.permissions.forEach(permName => {
                const checkbox = document.querySelector(`.permission-checkbox[value="${permName}"]`);
                if (checkbox) checkbox.checked = true;
            });

            roleIdInput.value = roleId;
            permissionsPanel.style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar los permisos');
    } finally {
        loadingIndicator.style.display = 'none';
    }
});

// Guardar permisos
saveButton.addEventListener('click', async function() {
    const roleId = roleIdInput.value;
    if (!roleId) return;

    // Recolectar permisos seleccionados
    const selectedPermissions = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);

    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    try {
        const response = await fetch(`/admin/permissions/role/${roleId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ permissions: selectedPermissions })
        });

        const data = await response.json();

        if (data.success) {
            // SweetAlert de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al guardar los permisos'
        });
    } finally {
        saveButton.disabled = false;
        saveButton.innerHTML = '<i class="bi bi-save"></i> Guardar Cambios';
    }
});
</script>
@endsection
