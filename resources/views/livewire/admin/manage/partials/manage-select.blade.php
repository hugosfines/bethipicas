<!-- Header -->
<div class="w-full bg-gray-900 shadow-lg p-2 border-b-2 border-gray-600">
    <div class="lg:flex lg:items-center">
        <div class="flex-shrink-0">
            <x-logo class="block h-12 w-auto lg:mr-4" />
        </div>
        <div class="flex-shrink-0">
            <div class="grid lg:grid-cols-3 lg:gap-4">
                <div>
                    <label class="block text-white font-bold mb-1">Fecha:</label>
                    <input type="date" wire:model.live="dateAt" class="w-full rounded-lg shadow-lg">
                </div>

                <div class="col-span-2">
                    <label class="block text-white font-bold mb-1">
                        Hipódromo:
                    </label>
                    <x-select-search wire:model.live="calendarId" class="w-full bg-white rounded-lg shadow-lg py-2 px-2 pr-6">
                        <option value="">Seleccione la jornada...</option>
                        @forelse ($calendars as $calendarSel)
                            <option wire:key="calendars-{{ $dateAt }}-{{ $calendarSel->id }}" value="{{ $calendarSel->id }}">
                                {{ $calendarSel->track->name }}
                            </option>
                        @empty
                            <option value="not-calendar">No existen jornadas creadas</option>
                        @endforelse
                    </x-select-search>
                </div>
            </div>
        </div>
        <div class="flex-shrink-1 w-full lg:px-2">
            <div class="grid lg:grid-cols-3 lg:gap-2">
                <div class="col-span-3">
                    <div class="flex items-center justify-end h-full pr-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-100 hover:text-gray-200">
                            <i class="fas fa-arrow-circle-left text-gray-600 mr-2"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenido Principal -->
@if($selectedCalendar)
<div class="container mx-auto p-6">
    <!-- Información de la Jornada -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $selectedCalendar->track->name }}</h2>
                <p class="text-gray-600">Jornada del {{ $selectedCalendar->date_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-500">{{ $racings->count() }} carreras programadas</p>
            </div>
            <div class="mt-4 md:mt-0">
                <!-- Control de Carrera Actual -->
                <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-lg">
                    <div class="text-center">
                        <span class="block text-sm text-gray-600">Carrera Actual</span>
                        <span class="block text-3xl font-bold text-blue-600">{{ $currentRace }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="previousRace" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200 disabled:opacity-50"
                                {{ $currentRace <= 1 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button wire:click="nextRace" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 disabled:opacity-50"
                                {{ $currentRace >= $racings->count() ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Carreras -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($racings as $racing)
        <div class="border border-gray-200 rounded-lg p-4 bg-white hover:shadow-lg transition duration-200 
                    {{ $racing->race == $currentRace ? 'ring-2 ring-blue-500' : '' }}
                    {{ $raceConfig[$racing->race]['status'] == 'closed' ? 'bg-gray-50' : '' }}">
            <!-- Header de la Carrera -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        Carrera #{{ $racing->race }}
                    </span>
                    @if($racing->race == $currentRace)
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">ACTUAL</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if($raceConfig[$racing->race]['status'] == 'open')
                    <button wire:click="closeRace({{ $racing->race }})" 
                            class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition duration-200">
                        Cerrar
                    </button>
                    @else
                    <button wire:click="openRace({{ $racing->race }})" 
                            class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition duration-200">
                        Reabrir
                    </button>
                    @endif
                </div>
            </div>

            <!-- Información de la Carrera -->
            <div class="mb-4 text-sm text-gray-600">
                <div class="flex justify-between mb-2">
                    <span>Hora: {{ $racing->start_time->format('H:i') }}</span>
                    <span>Distancia: {{ $racing->distance }}m</span>
                </div>
                <div class="flex justify-between">
                    <span class="{{ $raceConfig[$racing->race]['status'] == 'closed' ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold' }}">
                        {{ $raceConfig[$racing->race]['status'] == 'closed' ? 'CERRADA' : 'ABIERTA' }}
                    </span>
                    <span>Caballos: {{ $racing->total_horses }}</span>
                </div>
            </div>

            <!-- Lista de Caballos -->
            <div class="mt-4">
                <label class="block text-xs text-gray-600 mb-2 font-semibold">
                    Caballos retirados (scratch):
                </label>
                <div class="grid grid-cols-5 gap-2">
                    @for($horseNumber = 1; $horseNumber <= $raceConfig[$racing->race]['horses']; $horseNumber++)
                    @php
                        $isRetired = in_array($horseNumber, $raceConfig[$racing->race]['retired_horses'] ?? []);
                    @endphp
                    <button type="button"
                            wire:click="toggleRetiredHorse({{ $racing->race }}, {{ $horseNumber }})"
                            class="w-8 h-8 rounded text-xs font-medium transition duration-200 flex items-center justify-center
                                    {{ $isRetired 
                                        ? 'bg-red-500 text-white hover:bg-red-600 border-2 border-red-600' 
                                        : 'bg-green-500 text-white hover:bg-green-600 border-2 border-green-600' }}"
                            title="Caballo {{ $horseNumber }} - {{ $isRetired ? 'Retirado' : 'Activo' }}"
                            {{ $raceConfig[$racing->race]['status'] == 'closed' ? 'disabled' : '' }}>
                        {{ $horseNumber }}
                    </button>
                    @endfor
                </div>
            </div>

            <!-- Resumen -->
            <div class="mt-4 pt-3 border-t border-gray-200">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-600">Total: {{ $raceConfig[$racing->race]['horses'] }}</span>
                    <span class="text-green-600 font-semibold">
                        Activos: {{ $raceConfig[$racing->race]['horses'] - count($raceConfig[$racing->race]['retired_horses'] ?? []) }}
                    </span>
                    <span class="text-red-600 font-semibold">
                        Retirados: {{ count($raceConfig[$racing->race]['retired_horses'] ?? []) }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<!-- Estado vacío -->
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Selecciona una jornada</h3>
        <p class="text-gray-600">Elige una fecha y un hipódromo para gestionar las carreras</p>
    </div>
</div>
@endif

@push('js')
    <script>
        // mutation
        document.addEventListener("DOMContentLoaded", function () {
            let observer = new MutationObserver((mutations) => {
                let hasPrelineElements = false;

                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1) { // Verifica que sea un nodo HTML válido
                            if (node.matches('[data-hs-input], [data-hs-select]') || 
                                node.querySelector('[data-hs-input], [data-hs-select]')) {
                                hasPrelineElements = true;
                            }
                        }
                    });
                });

                if (hasPrelineElements) {
                    setTimeout(() => {
                        // Guardamos los valores actuales antes de reiniciar Preline
                        document.querySelectorAll('[wire\\:model\\.live]').forEach((input) => {
                            input.dataset.currentValue = input.value; // Guardamos el valor en un atributo data
                        });

                        window.HSStaticMethods.autoInit(); // Reinicializamos Preline

                        // Restauramos los valores guardados después de reiniciar Preline
                        document.querySelectorAll('[wire\\:model\\.live]').forEach((input) => {
                            if (input.dataset.currentValue) {
                                input.value = input.dataset.currentValue; // Restauramos el valor original
                            }
                        });
                    }, 10);
                    
                }
            });

            // Observa solo los cambios dentro del contenedor de Livewire
            const targetNode = document.querySelector('main') || document.body;
            if (targetNode) {
                observer.observe(targetNode, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                });
            }

            // Inicializa Preline en la carga inicial
            window.HSStaticMethods.autoInit();
        });
    </script>
@endpush