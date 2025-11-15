<div>
    <!-- Header -->
    <div class="w-full bg-gray-900 shadow-lg p-2 border-b-2 border-gray-600">
        <div class="lg:flex lg:items-center">
            <div class="flex-shrink-0">
                <x-logo class="block h-12 w-auto lg:mr-4" />
            </div>
            <div class="flex-shrink-0 flex-1">
                <div class="grid lg:grid-cols-3 lg:gap-4">
                    <div>
                        <label class="block text-white font-bold mb-1">Fecha:</label>
                        <input type="date" wire:model.live="dateAt" class="w-full rounded-lg shadow-lg">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-white font-bold mb-1">
                            Seleccionar Hipódromos:
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 bg-white rounded-lg shadow-lg p-3 max-h-32 overflow-y-auto">
                                <div class="space-y-2">
                                    @forelse ($calendars as $calendar)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <input type="checkbox" 
                                               wire:model.live="selectedCalendarIds" 
                                               value="{{ $calendar->id }}"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $calendar->track->name }}</span>
                                    </label>
                                    @empty
                                    <p class="text-sm text-gray-500 text-center">No hay jornadas para esta fecha</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <button wire:click="selectAllCalendars" 
                                        class="px-3 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition duration-200">
                                    Todos
                                </button>
                                <button wire:click="unselectAllCalendars" 
                                        class="px-3 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                                    Ninguno
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0 lg:px-2">
                <div class="flex items-center justify-end h-full pr-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-100 hover:text-gray-200">
                        <i class="fas fa-arrow-circle-left text-gray-600 mr-2"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    @if(count($selectedCalendars) > 0)
    <div class="container mx-auto p-6">
        <!-- Grid de Jornadas Seleccionadas -->
        <div class="grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-6">
            @foreach($selectedCalendars as $calendarId => $calendar)
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header de la Jornada -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $calendar->track->name }}</h3>
                            <p class="text-blue-100 text-sm">Jornada del {{ $calendar->date_at->format('d/m/Y') }}</p>
                        </div>
                        <span class="bg-blue-800 text-white px-2 py-1 rounded text-xs">
                            {{ $calendar->racings->count() }} carreras
                        </span>
                    </div>
                </div>

                <!-- Control de Carrera Actual -->
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-center">
                            <span class="block text-sm text-gray-600">Carrera Actual</span>
                            <span class="block text-2xl font-bold text-blue-600">{{ $currentRaces[$calendarId] }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="previousRace({{ $calendarId }})" 
                                    wire:loading.attr="disabled"
                                    class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200 disabled:opacity-50"
                                    {{ $currentRaces[$calendarId] <= 1 ? 'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button wire:click="nextRace({{ $calendarId }})" 
                                    wire:loading.attr="disabled"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 disabled:opacity-50"
                                    {{ $currentRaces[$calendarId] >= $calendar->racings->count() ? 'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Lista de Carreras -->
                <div class="max-h-96 overflow-y-auto p-4">
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($calendar->racings->sortBy('race') as $racing)
                        <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition duration-200 
                                    {{ $racing->race == $currentRaces[$calendarId] ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}
                                    {{ $raceConfig[$calendarId][$racing->race]['status'] == 'close' ? 'bg-gray-50' : '' }}">
                            <!-- Header de la Carrera -->
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                                        #{{ $racing->race }}
                                    </span>
                                    @if($racing->race == $currentRaces[$calendarId])
                                    <span class="bg-green-100 text-green-800 px-1 py-1 rounded text-xs">ACTUAL</span>
                                    @endif
                                </div>
                                <div class="flex gap-1">
                                    @if($raceConfig[$calendarId][$racing->race]['status'] == 'open')
                                    <button wire:click="closeRace({{ $calendarId }}, {{ $racing->race }})" 
                                            class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition duration-200">
                                        Cerrar
                                    </button>
                                    @else
                                    <button wire:click="openRace({{ $calendarId }}, {{ $racing->race }})" 
                                            class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition duration-200">
                                        Abrir
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Información de la Carrera -->
                            <div class="mb-2 text-xs text-gray-600">
                                <div class="flex justify-between">
                                    <span>{{ $racing->start_time->format('H:i') }}</span>
                                    <span class="{{ $raceConfig[$calendarId][$racing->race]['status'] == 'close' ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold' }}">
                                        {{ $raceConfig[$calendarId][$racing->race]['status'] == 'close' ? 'CERRADA' : 'ABIERTA' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Caballos Retirados -->
                            <div class="mt-2">
                                <label class="block text-xs text-gray-600 mb-1 font-semibold">Retirados:</label>
                                <div class="grid grid-cols-6 gap-1">
                                    @for($horseNumber = 1; $horseNumber <= $raceConfig[$calendarId][$racing->race]['horses']; $horseNumber++)
                                    @php
                                        $isRetired = in_array($horseNumber, $raceConfig[$calendarId][$racing->race]['retired_horses'] ?? []);
                                    @endphp
                                    <button type="button"
                                            wire:click="toggleRetiredHorse({{ $calendarId }}, {{ $racing->race }}, {{ $horseNumber }})"
                                            class="w-6 h-6 rounded text-xs font-medium transition duration-200 flex items-center justify-center
                                                   {{ $isRetired 
                                                       ? 'bg-red-500 text-white hover:bg-red-600 border border-red-600' 
                                                       : 'bg-green-500 text-white hover:bg-green-600 border border-green-600' }}"
                                            title="Caballo {{ $horseNumber }}"
                                            {{ $raceConfig[$calendarId][$racing->race]['status'] == 'close' ? 'disabled' : '' }}>
                                        {{ $horseNumber }}
                                    </button>
                                    @endfor
                                </div>
                            </div>

                            <!-- Resumen -->
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">T:{{ $raceConfig[$calendarId][$racing->race]['horses'] }}</span>
                                    <span class="text-green-600">A:{{ $raceConfig[$calendarId][$racing->race]['horses'] - count($raceConfig[$calendarId][$racing->race]['retired_horses'] ?? []) }}</span>
                                    <span class="text-red-600">R:{{ count($raceConfig[$calendarId][$racing->race]['retired_horses'] ?? []) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Selecciona jornadas</h3>
            <p class="text-gray-600">Elige una o más jornadas para gestionar las carreras</p>
        </div>
    </div>
    @endif
</div>