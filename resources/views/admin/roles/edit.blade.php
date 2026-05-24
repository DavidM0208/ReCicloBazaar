@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Editar Rol: {{ $role->name }}
                </h5>
            </div>
            <div class="card-body">
                @if($isProtected)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Este rol está protegido por el sistema. Solo puedes modificar sus permisos.
                    </div>
                @endif

                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="bi bi-tag"></i> Nombre del Rol
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $role->name) }}"
                               {{ $isProtected ? 'readonly' : '' }}
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-key"></i> Permisos del Rol
                        </label>
                        <div class="card">
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @forelse($permissions as $categoria => $perms)
                                    <div class="mb-3">
                                        <h6 class="text-primary">
                                            <i class="bi bi-folder"></i> {{ ucfirst($categoria) }}
                                        </h6>
                                        <div class="row">
                                            @foreach($perms as $permission)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox"
                                                               class="form-check-input"
                                                               name="permissions[]"
                                                               value="{{ $permission->name }}"
                                                               id="perm_{{ $permission->id }}"
                                                               {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No hay permisos disponibles</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.roles') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
