@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Permiso
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="bi bi-tag"></i> Nombre del Permiso
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="ej: exportar reportes, generar certificados"
                               required>
                        <small class="text-muted">
                            Usa el formato: "acción recurso" (ej: "crear usuarios", "exportar reportes")
                        </small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-shield"></i> Asignar a roles:
                        </label>
                        <div class="card">
                            <div class="card-body">
                                @foreach($roles as $role)
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               name="roles[]"
                                               value="{{ $role->id }}"
                                               id="role_{{ $role->id }}">
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                            @if($role->name === 'admin')
                                                <span class="badge bg-warning ms-1">Tiene todos los permisos</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.permissions') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Permiso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
