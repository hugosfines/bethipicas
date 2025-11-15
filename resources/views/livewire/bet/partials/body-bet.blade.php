@if ($errors->any())
    <div class="relative mx-1 bg-gray-800 text-red-300 rounded shadow px-6 py-2 -mb-2">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="w-ful">
    <div class="flex items-center bg-gray-950 px-4 py-1">
        <div>
            <label class="block text-white font-semibold mb-0.5">Fecha</label>
            <input type="date" 
                wire:model="dateTicket"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="w-60 ml-1">
            <label class="block text-white font-semibold mb-0.5">Código ticket</label>
            {{-- <input type="text" class="px-2 py-1 rounded shadow-lg" maxlength="6"> --}}
            <form wire:submit.prevent="searchTicket">   
                <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input 
                        type="search" 
                        wire:model="codeTicket"
                        id="search" 
                        class="block w-full ps-10 text-sm text-white border border-gray-300 rounded-lg bg-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Código" maxlength="6" autocomplete="off" required />
                    <button type="submit" class="text-white absolute end-2.5 bottom-0.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Aceptar</button>
                </div>
            </form>
        </div>

        <div class="px-4 text-white border-white border-r-2 h-14"></div>

        <div class="ml-6">
            <label class="block text-white font-semibold pl-2 mb-0.5">Anular ticket</label>
            {{-- <input type="text" class="px-2 py-1 rounded shadow-lg" maxlength="6"> --}}
            <div class="w-60 ml-1">
                <form>   
                    <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" id="search" class="block w-full ps-10 text-sm text-white border border-gray-300 rounded-lg bg-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Código" maxlength="6" autocomplete="off" required />
                        <button type="submit" class="text-white absolute end-2.5 bottom-0.5 bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Anular</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<div x-data="{ loadingTwo: @entangle('loadingTwo') }" class="w-full flex justify-center overflow-x-auto bg-white shadow-lg p-1 border-b-2 border-gray-600">    
    <div class="w-full bg-gray-900 pl-4 py-6">

        <div class="lg:flex space-y-6 lg:space-y-0">
            <!-- Carreras -->
            <div class="flex-shrink-0 pl-4 lg:pl-0 px-2 border-r-0 lg:border-r-2 border-r-gray-600 space-y-1">
                <div class="w-full text-center border-b-2 border-b-gray-600 pb-1 mb-2">
                    <label class="block text-base text-white font-bold">Carrera(s)</label>
                </div>
                @if ($calendarData && $calendarData->total_races > 0)
                    @for ($i = 1; $i <= ($calendarData ? $calendarData->total_races : 0); $i++)
                        <label wire:key="carrera-{{ $i }}" class="inline-flex items-center pr-2">
                            <input type="radio" 
                                wire:model.live="raceCurrent"
                                wire:click="addCorredores()" 
                                name="racing-radio" 
                                value="{{ $i }}"
                                x-on:change="loading = true"
                                class="hidden peer"
                            >
                            <span class="px-4 py-2 bg-gray-700 text-white rounded-md cursor-pointer 
                                    hover:bg-gray-600 peer-checked:bg-orange-500 peer-checked:text-white 
                                    w-32 lg:w-20 text-center">
                                {{ $i.$codeNumbers[$i] }}
                            </span>
                        </label>
                        
                        @if($i % 2 === 0)
                            <div class="w-full"></div>
                        @endif
                    @endfor
                @else
                    <div class="inline-flex items-center pr-2">
                        <span class="text-white">No existe jornada creada.</span>
                    </div>
                @endif
            </div>

            {{-- @if ($racing && $racing->status=='open' && $racing->start_time->format('Y-m-d') >= now()->format('Y-m-d')) --}}
            @if ($racing && $racing->status=='open' && $racing->calendar->date_at->format('Y-m-d') >= now()->format('Y-m-d'))
                <!-- Apuesta -->
                <div class="flex-shrink-0 pl-4 pr-2 border-r-0 lg:border-r-2 border-r-gray-600 space-y-2 lg:space-y-1">
                    <div class="w-full text-center border-b-2 border-b-gray-600 pb-1 mb-2">
                        <label class="block text-base text-white font-bold">Apuesta</label>
                    </div>
                    @foreach ($betTypes as $key => $betType)
                        @if ($betType->bet_type_id == 1 || $betType->bet_type_id > 3)
                            <label wire:key="tipo-de-apuestas-{{ $betType->id }}-{{ $betType->bet_type_id }}" class="inline-flex items-center w-full lg:w-auto pr-2">
                                <input type="radio" 
                                    wire:model.live="betTypeId" 
                                    value="{{ $betType->bet_type_id }}"
                                    x-on:change="loadingTwo = true"
                                    name="type-bet-radio" 
                                    class="hidden peer">
                                <span class="px-4 py-2 bg-gray-700 text-white rounded-md cursor-pointer 
                                        hover:bg-gray-600 peer-checked:bg-orange-500 peer-checked:text-white 
                                        w-full lg:w-40 text-center">
                                    {{ $betType->bet_type_id == 1 ? 'G/P/S' : $betType->bet_type->name }}
                                </span>
                            </label>

                            @if(($key + 1) % 2 === 0)
                                <div class="w-full"></div>
                            @endif
                        @endif
                    @endforeach
                </div>

                <!-- Monto de la apuesta -->
                <div class="flex-shrink-0 pl-4 pr-4 border-r-0 lg:border-r-2 border-r-gray-600 space-y-2 lg:space-y-1">
                    <div class="w-full text-center border-b-2 border-b-gray-600 pb-1 mb-2">
                        <label class="block text-base text-white font-bold">Monto de la Apuesta</label>
                    </div>
                    @foreach ($defaultPrices as $key => $defaultPrice)
                        <label class="inline-flex items-center">
                            <input 
                                wire:model.live="montoSelect"
                                type="radio" 
                                value="{{ $defaultPrice }}"
                                name="amount-bet-radio" 
                                class="hidden peer"
                            >
                            <span class="px-4 py-2 bg-gray-700 text-white rounded-md cursor-pointer 
                                    hover:bg-gray-600 peer-checked:bg-orange-500 peer-checked:text-white 
                                    w-32 text-center">
                                {{ number_format($defaultPrice, 0, '.', ',') }} <span class="text-xs text-gray-300">$</span>
                            </span>
                        </label>
                        
                        @if(($key + 1) % 2 === 0)
                            <div class="w-full"></div>
                        @endif
                    @endforeach
                    <div class="w-full border-b-2 border-b-gray-600 pb-1 pt-4">
                        <label class="block text-base text-white font-bold text-right pr-4">Monto:</label>
                        <input 
                        wire:model.live="monto"
                            type="text" 
                            x-mask:dynamic="$money($el.value, '.')" 
                            class="w-full rounded-lg text-right" 
                            placeholder="0.00"
                        >
                    </div>
                </div>

                <!-- Corredores -->
                <div class="relative">
                    <x-loading-two/>
                        
                    <div class="flex-shrink-0 h-full border-r-0 lg:border-r-2 border-r-gray-600 px-2 space-y-2 lg:space-y-1">
                        @php
                            $numFollow = $corredores ? count(get_object_vars($corredores->follows)) : 0;
                        @endphp
                        <div class="w-full text-center pb-1 mb-2 pl-4 pr-4">
                            <label class="block text-base text-white font-bold border-b-2 border-b-gray-600 pb-1">
                                Corredores
                            </label>
                        </div>
                        <div :class="{
                            'grid grid-cols-1 mx-1 gap-x-8': {{ $numFollow }} == 1,
                            'grid grid-cols-2 mx-1 gap-x-8': {{ $numFollow }} == 2,
                            'grid grid-cols-3 mx-1 gap-x-8': {{ $numFollow }} == 3,
                            'grid grid-cols-4 mx-1 gap-x-8': {{ $numFollow }} == 4,
                            'grid grid-cols-5 mx-1 gap-x-8': {{ $numFollow }} == 5,
                            'mr-10': '{{ $betTypeData->follow > 1 && $betTypeData->category->type_follow == 'current' }}'
                        }">
                            @foreach ($corredores->follows ?? [] as $key => $item)
                                @php
                                    $step = ($key + 1);
                                @endphp
                                <div class="flex justify-center cols-span-1 relative w-full">
                                    <div class="relative">
                                        <label class="block text-sm text-white font-bold mb-2">
                                            {{ $item->title }}
                                        </label>
                                        <div class="grid gap-y-2">
                                            @php
                                                $keyWireBetTypeId = isset($corredores->bet_type_id) ? $corredores->bet_type_id : $item->bet_type_id;
                                            @endphp
                                            @foreach ($item->ejemplares as $ejemplar)
                                                @if ($ejemplar->status == 'run')
                                                    <label wire:key="ejemplar-{{ $keyWireBetTypeId }}-{{ $ejemplar->nro }}-{{ $step }}" 
                                                            class="relative inline-flex items-center justify-center opa">
                                                        <input 
                                                            type="checkbox" 
                                                            value="{{ $ejemplar->nro }}"
                                                            id="nro-{{ $ejemplar->nro }}-{{ $step }}"
                                                            class="hidden peer disabled:bg-red-600" 
                                                            @disabled($ejemplar->status == 'scratch' || $ejemplar->status == 'invalid')
                                                            wire:click="createPlayings('{{ isset($corredores->bet_type_id) ? $corredores->bet_type_id : $item->bet_type_id }}', '{{ $ejemplar->nro }}', '{{ $item->race }}', '{{ $step }}')"
                                                            wire:target="createPlayings('{{ isset($corredores->bet_type_id) ? $corredores->bet_type_id : $item->bet_type_id }}', '{{ $ejemplar->nro }}', '{{ $item->race }}', '{{ $step }}')"
                                                            wire:loading.attr="disabled" 
                                                            wire:loading.class="opacity-50 pointer-events-none"
                                                            name="check-apuestas"
                                                        >
                                                        <span class="rounded-md flex items-center justify-center
                                                                peer-checked:bg-orange-500 peer-checked:text-white w-12 h-8 text-center border-b-2 
                                                                {{ ($ejemplar->status != 'scratch' && $ejemplar->status != 'invalid') ? 'text-white bg-gray-700 hover:bg-gray-600 cursor-pointer' : 'text-gray-400 bg-red-900 hover:bg-red-900 cursor-not-allowed' }}" 
                                                                style="border-color: {{ $colorNumbers[$ejemplar->nro] }};">
                                                            <span wire:loading.remove wire:target="createPlayings('{{ isset($corredores->bet_type_id) ? $corredores->bet_type_id : $item->bet_type_id }}', '{{ $ejemplar->nro }}', '{{ $item->race }}', '{{ $step }}')">
                                                                {{ $ejemplar->nro }}
                                                            </span>
                                                            <span wire:loading wire:target="createPlayings('{{ isset($corredores->bet_type_id) ? $corredores->bet_type_id : $item->bet_type_id }}', '{{ $ejemplar->nro }}', '{{ $item->race }}', '{{ $step }}')">
                                                                <i class="fa-solid fa-spinner"></i>
                                                            </span>
                                                        </span>
                                                        <!-- checked all -->
                                                        @if ($step == $numFollow && $betTypeData->follow > 1 && $betTypeData->category->type_follow == 'current')
                                                            <div class="absolute inline-flex -right-8">
                                                                <input 
                                                                    type="checkbox" 
                                                                    wire:change="selectAllExotics('{{ $ejemplar->nro }}', $event.target.checked)"
                                                                    value="{{ $ejemplar->nro }}" 
                                                                    id="check-selectAll-{{ $ejemplar->nro }}"
                                                                    x-on:change="loadingTwo = true"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                <label for="disabled-checkbox" class="ms-2 text-sm font-medium text-gray-400 dark:text-gray-500"></label>
                                                            </div>
                                                        @endif
                                                    </label>
                                                    <div></div>
                                                @else
                                                    <label class="inline-flex items-center justify-center">
                                                        <input 
                                                            type="checkbox" 
                                                            value="{{ $ejemplar->nro }}"
                                                            class="hidden peer disabled:bg-red-600" 
                                                            name="check-apuestas"
                                                            disabled
                                                        >
                                                        <span class="rounded-md 
                                                                peer-checked:bg-orange-500 peer-checked:text-white w-10 text-center border-b-2 
                                                                text-gray-400 bg-red-900 hover:bg-red-900 cursor-not-allowed" 
                                                                style="border-color: {{ $colorNumbers[$ejemplar->nro] }};">
                                                            {{ $ejemplar->nro }}
                                                        </span>
                                                    </label>
                                                @endif
                                                
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Accion -->
                <div class="flex-shrink-1 w-60 pl-4 pr-4 space-y-2 lg:space-y-1">
                    <div class="w-full text-center border-b-2 border-b-gray-600 pb-1 mb-2">
                        <label class="block text-base text-white font-bold">Acción</label>
                    </div>
                    <div class="w-full">
                        <div class="mb-10">
                            <button wire:click="saveAndPrint" 
                                    class="flex items-center justify-between text-sm font-semibold px-4 py-2 bg-gray-700 text-white rounded-md cursor-pointer 
                                        hover:bg-gray-800 w-full text-center border-b-2 border-b-orange-600">
                                Guardar e imprimir <i class="fas fa-save"></i>
                            </button>
                        </div>

                        <div>
                            <button class="flex items-center justify-between text-sm px-4 py-2 bg-gray-700 text-white rounded-md cursor-pointer 
                                        hover:bg-gray-800 w-full text-center border-b-2 border-b-orange-600" title="Reimprimir ultimo ticket">
                                Reimprimir <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @elseif ($racing && $racing->status=='result')
                
            @else
                <div class="inline-flex items-center px-4">
                    @if ($racing && $racing->status == 'close' )
                        <span class="text-white">Esperando resultados</span>
                    @elseif($racing && $racing->status == 'open' && $racing->start_time->format('Y-m-d') < now()->format('Y-m-d'))
                        <span class="text-white">Race out range date</span>
                    @else
                        <span class="text-white">{{ $racing ? ('Carrera ' . $racing->status) : '' }}</span>
                    @endif
                </div>
            @endif

        </div>

    </div>
</div>