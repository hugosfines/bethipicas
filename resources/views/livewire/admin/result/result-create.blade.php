<div class="w-full mx-auto py-6 pt-0 px-4 sm:px-6 lg:px-8">
    <!-- Header Mejorado -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 shadow-xl rounded-2xl p-6 mb-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">🏇 Resultados Hípicos</h1>
                <p class="text-blue-100 opacity-90">Gestión de resultados y dividendos</p>
            </div>
            <div class="mt-4 md:mt-0">
                <!--<button wire:click="saveResults" 
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Guardar Resultados</span>
                </button>-->
            </div>
        </div>
    </div>

    <!-- Filtros Mejorados -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mb-8 border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
            </svg>
            Filtros de Búsqueda
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Fecha
                </label>
                <input type="date" wire:model.live="dateAt" 
                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 p-3">
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Hipódromo
                </label>
                <select wire:model.live="calendarId" 
                        class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 p-3 bg-white">
                    <option value="">Seleccionar hipódromo...</option>
                    @foreach($calendars as $calendar)
                        <option value="{{ $calendar->id }}">
                            {{ $calendar->track->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Carrera
                </label>
                <input type="number" wire:model.live="raceCurrent" min="1" 
                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 p-3">
            </div>

            <div class="flex items-end">
                <div class="w-full bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-100 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $raceCurrent }}</div>
                    <div class="text-sm text-blue-500 font-medium">Carrera Actual</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Carrera -->
    @if($calendarData)
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 shadow-xl rounded-2xl p-6 mb-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-bold mb-2">{{ $calendarData->track->name ?? 'Hipódromo' }}</h2>
                <p class="text-green-100 opacity-90 text-lg">Carrera #{{ $raceCurrent }} • {{ \Carbon\Carbon::parse($calendarData->date_at)->format('d/m/Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-white bg-opacity-20 rounded-xl px-4 py-2">
                    <!--<div class="text-sm opacity-90">Estado</div>
                    <div class="font-semibold">Activa</div>-->
                    <button wire:click="saveResults" 
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Guardar Resultados</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mensajes Flash Mejorados -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-400 to-emerald-500 border border-green-400 text-white rounded-xl shadow-lg transform transition-all duration-300">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-gradient-to-r from-red-400 to-pink-500 border border-red-400 text-white rounded-xl shadow-lg transform transition-all duration-300">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Grid de Resultados -->
    @if(count($betTypes) > 0)
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            @foreach($betTypes as $betType)
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Header de la Apuesta -->
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    {{ $betType->name ?? 'Tipo de Apuesta' }}
                                </h3>
                                <p class="text-purple-100 opacity-90 text-sm mt-1">Ingresa el resultado ganador</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido Colapsable -->
                    <div class="p-6">
                        <!-- Resultado Principal -->
                        <div class="mb-6">
                            <label class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ganador Principal
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500">#</span>
                                        </div>
                                        <input type="number" 
                                               wire:model="results.{{ $betType->id }}.number"
                                               min="1"
                                               placeholder="Número del caballo"
                                               class="pl-8 w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200 p-3 bg-gray-50">
                                    </div>
                                    @error('results.'.$betType->id.'.number')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01"
                                               wire:model="results.{{ $betType->id }}.dividendo"
                                               placeholder="Dividendo ganador"
                                               class="pl-8 w-full border-2 border-gray-200 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200 p-3 bg-gray-50">
                                    </div>
                                    @error('results.'.$betType->id.'.dividendo')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Empates -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-sm font-semibold text-gray-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    Empates
                                    @if(isset($ties[$betType->id]) && count($ties[$betType->id]) > 0)
                                        <span class="ml-2 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                                            {{ count($ties[$betType->id]) }} activo(s)
                                        </span>
                                    @endif
                                </label>
                                <button type="button" 
                                        wire:click="addTie({{ $betType->id }})"
                                        class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium py-2 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>Agregar Empate</span>
                                </button>
                            </div>
                            
                            @if(isset($ties[$betType->id]) && count($ties[$betType->id]) > 0)
                                <div class="space-y-4">
                                    @foreach($ties[$betType->id] as $index => $tie)
                                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-100 rounded-xl p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500">#</span>
                                                        </div>
                                                        <input type="number" 
                                                               wire:model="ties.{{ $betType->id }}.{{ $index }}.number"
                                                               min="1"
                                                               placeholder="Número empatado"
                                                               class="pl-8 w-full border-2 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 p-3">
                                                    </div>
                                                    @error('ties.'.$betType->id.'.'.$index.'.number')
                                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="flex gap-3">
                                                    <div class="flex-1 relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500">$</span>
                                                        </div>
                                                        <input type="number" 
                                                               step="0.01"
                                                               wire:model="ties.{{ $betType->id }}.{{ $index }}.dividendo"
                                                               placeholder="Dividendo empatado"
                                                               class="pl-8 w-full border-2 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 p-3">
                                                    </div>
                                                    <button type="button" 
                                                            wire:click="removeTie({{ $betType->id }}, {{ $index }})"
                                                            class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white p-3 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-md">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('ties.'.$betType->id.'.'.$index.'.dividendo')
                                                <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="text-gray-500 mt-2">No hay empates registrados</p>
                                    <p class="text-gray-400 text-sm">Haz clic en "Agregar Empate" si hay caballos empatados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 shadow-xl rounded-2xl p-8 text-center text-white">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="text-2xl font-bold mb-2">No hay apuestas disponibles</h3>
            <p class="text-yellow-100 opacity-90">Selecciona una carrera con tipos de apuesta activos</p>
        </div>
    @endif
</div>

{{-- <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Resultados Hípicos</h1>
        
        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" wire:model.live="dateAt" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hipódromo</label>
                <select wire:model.live="calendarId" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccionar...</option>
                    @foreach($calendars as $calendar)
                        <option value="{{ $calendar->id }}">
                            {{ $calendar->track->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                <input type="number" wire:model.live="raceCurrent" min="1" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Información de la Carrera -->
        @if($calendarData)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-blue-900">
                    {{ $calendarData->track->name ?? 'Hipódromo' }} - Carrera {{ $raceCurrent }}
                </h3>
                <p class="text-blue-700">{{ \Carbon\Carbon::parse($calendarData->date_at)->format('d/m/Y') }}</p>
            </div>
        @endif
    </div>

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario de Resultados -->
    @if(count($betTypes) > 0)
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Ingresar Resultados</h2>
                <button wire:click="saveResults" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                    Guardar Resultados
                </button>
            </div>

            <div class="space-y-6">
                @foreach($betTypes as $betType)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            {{ $betType->name }}
                        </h3>
                        
                        <!-- Resultado Principal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número Ganador</label>
                                <input type="number" 
                                       wire:model="results.{{ $betType->id }}.number"
                                       min="1"
                                       placeholder="Ej: 5"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('results.'.$betType->id.'.number')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dividendo</label>
                                <input type="number" 
                                       step="0.01"
                                       wire:model="results.{{ $betType->id }}.dividendo"
                                       placeholder="Ej: 2.50"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('results.'.$betType->id.'.dividendo')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Empates -->
                        <div class="mb-2">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Empates</label>
                                <button type="button" 
                                        wire:click="addTie({{ $betType->id }})"
                                        class="text-sm bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded transition duration-200">
                                    + Agregar Empate
                                </button>
                            </div>
                            
                            @if(isset($ties[$betType->id]) && count($ties[$betType->id]) > 0)
                                <div class="space-y-3">
                                    @foreach($ties[$betType->id] as $index => $tie)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                            <div>
                                                <input type="number" 
                                                       wire:model="ties.{{ $betType->id }}.{{ $index }}.number"
                                                       min="1"
                                                       placeholder="Número empatado"
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @error('ties.'.$betType->id.'.'.$index.'.number')
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="flex gap-2">
                                                <input type="number" 
                                                       step="0.01"
                                                       wire:model="ties.{{ $betType->id }}.{{ $index }}.dividendo"
                                                       placeholder="Dividendo empatado"
                                                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <button type="button" 
                                                        wire:click="removeTie({{ $betType->id }}, {{ $index }})"
                                                        class="text-red-500 hover:text-red-700 transition duration-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @error('ties.'.$betType->id.'.'.$index.'.dividendo')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No hay empates registrados</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-yellow-700">No hay tipos de apuesta disponibles para esta carrera.</p>
        </div>
    @endif
</div> --}}
