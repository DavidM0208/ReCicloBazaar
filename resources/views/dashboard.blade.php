@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Bienvenido, {{ Auth::user()->name }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>ID:</strong> {{ Auth::user()->id }}</p>
            </div>
        </div>

        <!-- Roles del usuario -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-tags"></i> Tus Roles</h5>
            </div>
            <div class="card-body">
                @forelse(Auth::user()->roles as $role)
                    <span class="badge bg-primary fs-6 me-2">
                        {{ $role->name }}
                    </span>
                @empty
                    <span class="badge bg-secondary">Sin rol asignado</span>
                @endforelse
            </div>
        </div>

        <!-- Permisos del usuario -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-key"></i> Tus Permisos</h5>
            </div>
            <div class="card-body">
                @forelse(Auth::user()->getAllPermissions() as $permission)
                    <span class="badge bg-success fs-6 me-2 mb-2">
                        {{ $permission->name }}
                    </span>
                @empty
                    <span class="badge bg-secondary">Sin permisos asignados</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
