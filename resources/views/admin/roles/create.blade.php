@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Rol
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="bi bi-tag"></i> Nombre del Rol
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="ej: supervisor, analista, invitado"
                               required>
                        <small class="text-muted">
                            Solo letras minúsculas, números, guiones y guiones bajos. Mínimo 3 caracteres.
                        </small>
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
                                                               id="perm_{{ $permission->id }}">
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
                            <i class="bi bi-save"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Rol
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="bi bi-tag"></i> Nombre del Rol
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="ej: supervisor, analista, invitado"
                               required>
                        <small class="text-muted">
                            Solo letras minúsculas, números, guiones y guiones bajos. Mínimo 3 caracteres.
                        </small>
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
                                                               id="perm_{{ $permission->id }}">
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
                            <i class="bi bi-save"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
