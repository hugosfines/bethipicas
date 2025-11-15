<!-- Modal de Configuración de Jornadas o Calendars -->
<div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-40" x-data x-on:keydown.escape.window="$wire.showConfigModal = false">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden" x-on:click.outside="$wire.showConfigModal = false">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <h3 class="text-lg font-semibold">Configurar Jornadas - Detalle por Carrera</h3>
            <p class="text-blue-100 text-sm mt-1">Configura carreras, caballos y retirados por cada hipódromo</p>
        </div>
        
        <div class="overflow-y-auto max-h-[70vh]">
            <!-- Controles Globales -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carreras por default</label>
                        <input type="number" wire:model="defaultRaces" min="1" max="20"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Caballos por default</label>
                        <input type="number" wire:model="defaultHorses" min="1" max="20"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button wire:click="setDefaultsForAll" 
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200">
                            Aplicar Defaults
                        </button>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button wire:click="selectAllTracks" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200 text-sm">
                        Seleccionar Todos
                    </button>
                    <button wire:click="unselectAllTracks" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 text-sm">
                        Deseleccionar Todos
                    </button>
                </div>
            </div>

            <!-- Lista de Hipódromos -->
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($tracks as $track)
                    @if(isset($trackConfig[$track->id]))
                    <div class="border border-gray-200 rounded-lg overflow-hidden" x-data="{ isOpen: {{ $trackConfig[$track->id]['selected'] ? 'true' : 'false' }} }">
                        <!-- Header del Hipódromo - Siempre visible -->
                        <div class="bg-gray-50 p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" 
                                        wire:model="trackConfig.{{ $track->id }}.selected"
                                        x-on:click="$wire.updateRaceConfig('{{ $track->id }}', document.getElementById('total-carrs-{{ $track->id }}').value)"
                                        x-on:change="isOpen = !isOpen"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="font-semibold text-gray-900 text-lg">{{ $track->name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">ID: {{ $track->id }}</span>
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm text-gray-700">Carreras:</label>
                                        <input type="number" 
                                            wire:model="trackConfig.{{ $track->id }}.races" 
                                            wire:change="updateRaceConfig({{ $track->id }}, $event.target.value)"
                                            id="total-carrs-{{ $track->id }}"
                                            min="1" max="20"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                    <!-- Botón del acordeón -->
                                    <button type="button" 
                                            x-on:click="isOpen = !isOpen"
                                            class="p-1 rounded-md text-gray-400 hover:text-gray-500 transition duration-200"
                                            :class="{ 'text-blue-500': isOpen }">
                                        <svg class="w-5 h-5 transform transition duration-200" 
                                            :class="{ 'rotate-180': isOpen }" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carreras del Hipódromo - Se expande/contrae -->
                        <div x-show="isOpen" x-collapse>
                            @if($trackConfig[$track->id]['selected'] && isset($raceConfig[$track->id]))
                            <div class="p-4 bg-white border-t border-gray-200">
                                <!-- Resumen rápido -->
                                <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-blue-800">Resumen del Hipódromo:</span>
                                        <div class="flex gap-4">
                                            <span class="text-blue-700">{{ $trackConfig[$track->id]['races'] }} carreras configuradas</span>
                                            @php
                                                $totalHorses = 0;
                                                $totalRetired = 0;
                                                foreach($raceConfig[$track->id] ?? [] as $raceConfigItem) {
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
                                    @for($raceNumber = 1; $raceNumber <= $trackConfig[$track->id]['races']; $raceNumber++)
                                    @if(isset($raceConfig[$track->id][$raceNumber]))
                                    <div class="border border-gray-200 rounded-lg p-4 bg-white hover:border-blue-300 transition duration-200">
                                        <div class="flex justify-between items-center mb-3">
                                            <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Carrera #{{ $raceNumber }}</span>
                                            </h4>
                                            <div class="flex items-center gap-2">
                                                <label class="text-xs text-gray-600">Caballos:</label>
                                                <input type="number" 
                                                    wire:model.live="raceConfig.{{ $track->id }}.{{ $raceNumber }}.horses" 
                                                    min="1" max="20"
                                                    class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            </div>
                                        </div>
                                        
                                        <!-- Lista de Caballos -->
                                        <div class="mt-3">
                                            <label class="block text-xs text-gray-600 mb-2">Caballos retirados (scratch):</label>
                                            <div class="grid grid-cols-5 gap-2">
                                                @for($horseNumber = 1; $horseNumber <= $raceConfig[$track->id][$raceNumber]['horses']; $horseNumber++)
                                                @php
                                                    $isRetired = in_array($horseNumber, $raceConfig[$track->id][$raceNumber]['retired_horses'] ?? []);
                                                @endphp
                                                <button type="button"
                                                        wire:click="toggleRetiredHorse({{ $track->id }}, {{ $raceNumber }}, {{ $horseNumber }})"
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
                                                <span class="text-gray-600">Total: {{ $raceConfig[$track->id][$raceNumber]['horses'] }}</span>
                                                <span class="text-green-600 font-semibold">
                                                    Activos: {{ $raceConfig[$track->id][$raceNumber]['horses'] - count($raceConfig[$track->id][$raceNumber]['retired_horses'] ?? []) }}
                                                </span>
                                                <span class="text-red-600 font-semibold">
                                                    Retirados: {{ count($raceConfig[$track->id][$raceNumber]['retired_horses'] ?? []) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endfor
                                </div>
                            </div>
                            @elseif($trackConfig[$track->id]['selected'])
                            <div class="p-8 text-center border-t border-gray-200">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm">Configura el número de carreras para ver las opciones de configuración</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- <div class="p-6">
                <div class="space-y-6">
                    @php
                        $trackIds = [52,4,9,18,22,25,119,26,27,30,32,38,55,59,60,61,68,76,82,83,89,90,91,103,105,116,23,8];
                        $tracks = App\Models\Track::whereIn('id', $trackIds)
                            ->orderByRaw('FIELD(id, ' . implode(',', $trackIds) . ')')
                            ->get();
                    @endphp

                    @foreach($tracks as $track)
                    @if(isset($trackConfig[$track->id]))
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Header del Hipódromo -->
                        <div class="bg-gray-50 p-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" 
                                        wire:model="trackConfig.{{ $track->id }}.selected"
                                        x-on:click="$wire.updateRaceConfig('{{ $track->id }}', document.getElementById('total-carrs-{{ $track->id }}').value)"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="font-semibold text-gray-900 text-lg">{{ $track->name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">ID: {{ $track->id }}</span>
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm text-gray-700">Carreras:</label>
                                        <input type="number" 
                                            wire:model="trackConfig.{{ $track->id }}.races" 
                                            wire:change="updateRaceConfig({{ $track->id }}, $event.target.value)"
                                            id="total-carrs-{{ $track->id }}"
                                            min="1" max="20"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carreras del Hipódromo -->
                        @if($trackConfig[$track->id]['selected'] && isset($raceConfig[$track->id]))
                        <div class="p-4 bg-white">
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                                @for($raceNumber = 1; $raceNumber <= $trackConfig[$track->id]['races']; $raceNumber++)
                                @if(isset($raceConfig[$track->id][$raceNumber]))
                                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-gray-800">Carrera #{{ $raceNumber }}</h4>
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs text-gray-600">Caballos:</label>
                                            <input type="number" 
                                                wire:model.live="raceConfig.{{ $track->id }}.{{ $raceNumber }}.horses" 
                                                min="1" max="20"
                                                class="w-16 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    
                                    <!-- Lista de Caballos -->
                                    <div class="mt-3">
                                        <label class="block text-xs text-gray-600 mb-2">Selecciona caballos retirados (scratch):</label>
                                        <div class="grid grid-cols-5 gap-2">
                                            @for($horseNumber = 1; $horseNumber <= $raceConfig[$track->id][$raceNumber]['horses']; $horseNumber++)
                                            @php
                                                $isRetired = in_array($horseNumber, $raceConfig[$track->id][$raceNumber]['retired_horses'] ?? []);
                                            @endphp
                                            <button type="button"
                                                    wire:click="toggleRetiredHorse({{ $track->id }}, {{ $raceNumber }}, {{ $horseNumber }})"
                                                    class="w-8 h-8 rounded text-xs font-medium transition duration-200 flex items-center justify-center
                                                        {{ $isRetired 
                                                            ? 'bg-red-500 text-white hover:bg-red-600' 
                                                            : 'bg-green-500 text-white hover:bg-green-600' }}"
                                                    title="Caballo {{ $horseNumber }} - {{ $isRetired ? 'Retirado' : 'Activo' }}">
                                                {{ $horseNumber }}
                                            </button>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <!-- Resumen -->
                                    <div class="mt-3 pt-3 border-t border-gray-200 text-xs">
                                        <div class="flex justify-between">
                                            <span>Total: {{ $raceConfig[$track->id][$raceNumber]['horses'] }}</span>
                                            <span class="text-green-600">Activos: {{ $raceConfig[$track->id][$raceNumber]['horses'] - count($raceConfig[$track->id][$raceNumber]['retired_horses'] ?? []) }}</span>
                                            <span class="text-red-600">Retirados: {{ count($raceConfig[$track->id][$raceNumber]['retired_horses'] ?? []) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endfor
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                </div>
            </div> --}}
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
            <span class="text-sm text-gray-600">
                {{ count(collect($trackConfig)->where('selected', true)) }} hipódromos seleccionados
            </span>
            <div class="flex gap-3">
                <button wire:click="$set('showModal', false)" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                    Cancelar
                </button>
                <button wire:click="createJornadas" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Crear Jornadas
                </button>
            </div>
        </div>
    </div>
</div>