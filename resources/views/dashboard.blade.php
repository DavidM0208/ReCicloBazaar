<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del usuario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>

                    <div class="border-t pt-4">
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>ID:</strong> {{ Auth::user()->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Mostrar roles del usuario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-3">🎭 Tus Roles</h4>
                    <div class="flex gap-2">
                        @forelse(Auth::user()->roles as $role)
                            <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm">
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="px-3 py-1 bg-gray-500 text-white rounded-full text-sm">
                                Sin rol asignado
                            </span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Mostrar permisos del usuario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-3">🔑 Tus Permisos</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse(Auth::user()->getAllPermissions() as $permission)
                            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm">
                                {{ $permission->name }}
                            </span>
                        @empty
                            <span class="px-3 py-1 bg-gray-500 text-white rounded-full text-sm">
                                Sin permisos asignados
                            </span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
