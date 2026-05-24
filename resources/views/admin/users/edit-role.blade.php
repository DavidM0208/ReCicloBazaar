@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Editar rol de usuario
                </h5>
            </div>
            <div class="card-body">
                <!-- Información del usuario -->
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="bi bi-person"></i> Nombre:</strong>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="bi bi-envelope"></i> Email:</strong>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mt-2">
                            <strong><i class="bi bi-hash"></i> ID:</strong>
                            <p class="mb-0">{{ $user->id }}</p>
                        </div>
                        <div class="col-md-6 mt-2">
                            <strong><i class="bi bi-tag"></i> Rol actual:</strong>
                            <p class="mb-0">
                                @php
                                    $currentRole = $userRole->name ?? 'Sin rol';
                                    $roleBadgeClass = 'secondary';
                                    if($currentRole == 'admin') $roleBadgeClass = 'danger';
                                    elseif($currentRole == 'editor') $roleBadgeClass = 'primary';
                                    elseif($currentRole == 'lector') $roleBadgeClass = 'success';
                                @endphp
                                <span class="badge bg-{{ $roleBadgeClass }} fs-6">
                                    {{ $currentRole }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Formulario para cambiar rol -->
                <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="role" class="form-label fw-bold">
                            <i class="bi bi-shield-shaded"></i> Nuevo rol:
                        </label>
                        <select name="role" id="role"
                                class="form-select @error('role') is-invalid @enderror"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="">Seleccione un rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ $userRole && $userRole->name == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>

                        @if($user->id === auth()->id())
                            <div class="alert alert-warning mt-2 mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                No puedes cambiar tu propio rol de administrador
                            </div>
                        @endif

                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Información de permisos por rol -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-info-circle"></i> Permisos por rol:
                        </label>
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="fw-bold text-danger">Admin</div>
                                <small>Acceso total al sistema - Puede gestionar usuarios, roles y permisos</small>
                            </div>
                            <div class="list-group-item">
                                <div class="fw-bold text-primary">Editor</div>
                                <small>Puede ver usuarios y dashboard</small>
                            </div>
                            <div class="list-group-item">
                                <div class="fw-bold text-success">Lector</div>
                                <small>Solo puede ver el dashboard</small>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        @if($user->id !== auth()->id())
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar cambios
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
