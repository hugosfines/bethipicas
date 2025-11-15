<!-- Modal de Edición de Jornada -->
@if($showEditModal && $editingCalendar)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-40" x-data x-on:keydown.escape.window="$wire.showEditModal = false">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden" x-on:click.outside="$wire.showEditModal = false">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white">
            <h3 class="text-lg font-semibold">Editar Jornada - {{ $editingCalendar->track->name ?? 'Hipódromo' }}</h3>
            <p class="text-green-100 text-sm mt-1">Actualiza carreras, caballos y retirados para esta jornada</p>
        </div>
        
        <div class="overflow-y-auto max-h-[70vh]">
            <!-- Controles Globales -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carreras por default</label>
                        <input type="number" wire:model="editDefaultRaces" min="1" max="20"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Caballos por default</label>
                        <input type="number" wire:model="editDefaultHorses" min="1" max="20"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="flex items-end">
                        <button wire:click="$set('editTrackConfig.{{ $editingCalendar->track_id }}.races', $editDefaultRaces); $wire.updateEditRaceConfig({{ $editingCalendar->track_id }}, $editDefaultRaces)" 
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200">
                            Aplicar Defaults
                        </button>
                    </div>
                </div>
            </div>

            <!-- Configuración del Hipódromo -->
            <div class="p-6">
                @php
                    $track = $editingCalendar->track;
                    $trackId = $track->id;
                @endphp
                
                @if(isset($editTrackConfig[$trackId]))
                <div class="border border-gray-200 rounded-lg overflow-hidden" x-data="{ isOpen: true }">
                    <!-- Header del Hipódromo -->
                    <div class="bg-green-50 p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="font-semibold text-gray-900 text-lg">{{ $track->name }}</span>
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Editando</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-500">ID: {{ $track->id }}</span>
                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-700">Carreras:</label>
                                    <input type="number" 
                                        wire:model.live="editTrackConfig.{{ $trackId }}.races" 
                                        wire:change="updateEditRaceConfig({{ $trackId }}, $event.target.value)"
                                        min="1" max="20"
                                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                                </div>
                                <button type="button" 
                                        x-on:click="isOpen = !isOpen"
                                        class="p-1 rounded-md text-gray-400 hover:text-gray-500 transition duration-200"
                                        :class="{ 'text-green-500': isOpen }">
                                    <svg class="w-5 h-5 transform transition duration-200" 
                                        :class="{ 'rotate-180': isOpen }" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carreras del Hipódromo -->
                    <div x-show="isOpen" x-collapse>
                        @if(isset($editRaceConfig[$trackId]))
                        <div class="p-4 bg-white border-t border-gray-200">
                            <!-- Resumen rápido -->
                            <div class="mb-4 p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-green-800">Resumen de la Jornada:</span>
                                    <div class="flex gap-4">
                                        <span class="text-green-700">{{ $editTrackConfig[$trackId]['races'] }} carreras configuradas</span>
                                        @php
                                            $totalHorses = 0;
                                            $totalRetired = 0;
                                            foreach($editRaceConfig[$trackId] ?? [] as $raceConfigItem) {
                                                $totalHorses += $raceConfigItem['horses'];
                                                $totalRetired += count($raceConfigItem['retired_horses'] ?? []);
                                            }
                                        @endphp
                                        <span class="text-green-600">{{ $totalHorses - $totalRetired }} caballos activos</span>
                                        <span class="text-red-600">{{ $totalRetired }} retirados</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid de carreras -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                                @for($raceNumber = 1; $raceNumber <= $editTrackConfig[$trackId]['races']; $raceNumber++)
                                @if(isset($editRaceConfig[$trackId][$raceNumber]))
                                <div class="border border-gray-200 rounded-lg p-4 bg-white hover:border-green-300 transition duration-200">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Carrera #{{ $raceNumber }}</span>
                                        </h4>
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs text-gray-600">Caballos:</label>
                                            <input type="number" 
                                                wire:model.live="editRaceConfig.{{ $trackId }}.{{ $raceNumber }}.horses" 
                                                min="1" max="20"
                                                class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                                        </div>
                                    </div>
                                    
                                    <!-- Lista de Caballos -->
                                    <div class="mt-3">
                                        <label class="block text-xs text-gray-600 mb-2">Caballos retirados (scratch):</label>
                                        <div class="grid grid-cols-5 gap-2">
                                            @for($horseNumber = 1; $horseNumber <= $editRaceConfig[$trackId][$raceNumber]['horses']; $horseNumber++)
                                            @php
                                                $isRetired = in_array($horseNumber, $editRaceConfig[$trackId][$raceNumber]['retired_horses'] ?? []);
                                            @endphp
                                            <button type="button"
                                                    wire:click="toggleEditRetiredHorse({{ $trackId }}, {{ $raceNumber }}, {{ $horseNumber }})"
                                                    class="w-8 h-8 rounded text-xs font-medium transition duration-200 flex items-center justify-center
                                                        {{ $isRetired 
                                                            ? 'bg-red-500 text-white hover:bg-red-600 border-2 border-red-600' 
                                                            : 'bg-green-500 text-white hover:bg-green-600 border-2 border-green-600' }}"
                                                    title="Caballo {{ $horseNumber }} - {{ $isRetired ? 'Retirado' : 'Activo' }}"
                                                    x-tooltip="'Caballo {{ $horseNumber }} - {{ $isRetired ? 'Retirado' : 'Activo' }}'">
                                                {{ $horseNumber }}
                                            </button>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <!-- Resumen de la carrera -->
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-600">Total: {{ $editRaceConfig[$trackId][$raceNumber]['horses'] }}</span>
                                            <span class="text-green-600 font-semibold">
                                                Activos: {{ $editRaceConfig[$trackId][$raceNumber]['horses'] - count($editRaceConfig[$trackId][$raceNumber]['retired_horses'] ?? []) }}
                                            </span>
                                            <span class="text-red-600 font-semibold">
                                                Retirados: {{ count($editRaceConfig[$trackId][$raceNumber]['retired_horses'] ?? []) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endfor
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
            <span class="text-sm text-gray-600">
                Editando jornada del {{ $editingCalendar->date_at->format('d/m/Y') }}
            </span>
            <div class="flex gap-3">
                <button wire:click="showEditModal = false" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                    Cancelar
                </button>
                <button wire:click="updateCalendar" 
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Jornada
                </button>
            </div>
        </div>
    </div>
</div>
@endif