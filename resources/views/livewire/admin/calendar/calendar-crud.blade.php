<div class="container mx-auto p-6" x-data="{ 
    showConfirm: false, 
    deleteId: null,
    confirmCallback: null,
    confirmAction(callback, id = null) {
        this.deleteId = id;
        this.showConfirm = true;
        this.confirmCallback = () => callback(id);
    },
    executeAction() {
        if (this.confirmCallback) {
            this.confirmCallback();
        }
        this.showConfirm = false;
    }
 }">
    
    <!-- Notifications -->
    <div x-data="{
        show: false,
        message: '',
        type: 'success',
        notify(event) {
            this.message = event.detail.message;
            this.type = event.detail.type;
            this.show = true;
            setTimeout(() => this.show = false, 3000);
        }
    }" x-on:notify.window="notify($event)">
        <div x-show="show" x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform translate-y-2" 
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 transform translate-y-0" 
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="fixed top-4 right-4 z-50 max-w-sm w-full"
             :class="{
                 'bg-green-500': type === 'success',
                 'bg-red-500': type === 'error',
                 'bg-blue-500': type === 'info'
             }">
            <div class="flex items-center p-4 rounded-lg shadow-lg text-white">
                <span x-text="message" class="flex-1"></span>
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Jornadas</h1>
        <p class="text-gray-600 mt-2">Administra las jornadas de carreras</p>
    </div>

    <!-- Create Jornadas Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">Crear Jornadas Masivas</h3>
                <p class="text-gray-600 text-sm">Crea jornadas para todas las pistas configuradas</p>
            </div>
            <div class="flex items-center gap-4">
                <input type="date" wire:model.live="dateAt" 
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button wire:click="createJornadas" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Jornadas
                </button>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Buscar..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <button wire:click="create" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nueva Jornada
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carreras</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($calendars as $calendar)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $calendar->track->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $calendar->date_at->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $calendar->total_races }} carreras
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleStatus({{ $calendar->id }})" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition duration-200
                                           {{ $calendar->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $calendar->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <button wire:click="edit({{ $calendar->id }})" 
                                        class="text-blue-600 hover:text-blue-900 transition duration-200 p-1 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="confirmAction((id) => $wire.delete(id), {{ $calendar->id }})" 
                                        class="text-red-600 hover:text-red-900 transition duration-200 p-1 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No hay jornadas registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $calendars->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-40" x-data x-on:keydown.escape.window="$wire.showModal = false">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full" x-on:click.outside="$wire.showModal = false">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ $modalTitle }}</h3>
            </div>
            
            <form wire:submit.prevent="save" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pista</label>
                    <select wire:model="form.track_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Seleccionar pista</option>
                        @foreach($tracks as $track)
                        <option value="{{ $track->id }}">{{ $track->name }}</option>
                        @endforeach
                    </select>
                    @error('form.track_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" wire:model="form.date_at" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('form.date_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total de Carreras</label>
                    <input type="number" wire:model="form.total_races" min="1" max="20"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('form.total_races') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" wire:model="form.is_active" id="is_active"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Jornada activa</label>
                </div>
            </form>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button wire:click="showModal = false" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                    Cancelar
                </button>
                <button wire:click="save" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Confirmation Modal -->
    <div x-show="showConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-transition>
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmar Eliminación</h3>
            <p class="text-gray-600 mb-4">¿Estás seguro de que quieres eliminar esta jornada? Esta acción no se puede deshacer.</p>
            <div class="flex justify-end gap-3">
                <button @click="showConfirm = false" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                    Cancelar
                </button>
                <button @click="executeAction()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

