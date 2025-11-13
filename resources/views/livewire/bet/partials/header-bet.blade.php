{{-- <x-slot name="header"> --}}
<div>
    <div class="w-full bg-gray-900 shadow-lg p-2 border-b-2 border-gray-600">
        <div class="lg:flex lg:items-center">
            <div class="flex-shrink-0">
                <x-logo class="block h-12 w-auto lg:mr-4" />
            </div>
            <div class="flex-shrink-0">
                <input type="date" wire:model.live="dateAt" x-on:change="loading = true">
            </div>
            <div class="flex-shrink-1 w-full lg:px-2">
                <div class="grid lg:grid-cols-3 lg:gap-2">
                    {{-- <div class="col-span-1">
                        <x-select-search wire:model.live="calendarId" x-on:change="loading = true" class="w-full bg-white rounded-lg shadow-lg py-2 px-2 pr-6">
                            <option value="">Seleccione el hipodromo...</option>
                            @forelse ($calendars as $calendar)
                                <option wire:key="jornada-{{ $calendar->id }}" value="{{ $calendar->id }}">
                                    {{ $calendar->track->name }}
                                </option>
                            @empty
                                <option value="not-calendar">No existen jornadas creadas</option>
                            @endforelse
                        </x-select-search>
                    </div> --}}
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

    <div class="w-full bg-gray-900 shadow-lg border-b-2 border-gray-600 p-2">
        <div class="grid lg:grid-cols-12 gap-1 lg:gap-2">
            <div class="col-span-12 lg:col-span-4 lg:pr-2 border-r-0 lg:border-r-2 border-r-gray-600">
                <label class="block text-xs text-white font-bold mb-1">
                    Hipodromo:
                </label>
                <x-select-search wire:model.live="calendarId" x-on:change="loading = true" class="w-full bg-white rounded-lg shadow-lg py-2 px-2 pr-6">
                    @forelse ($calendars as $calendarSel)
                        <option wire:key="calendars-{{ $dateAt }}-{{ $calendarSel->id }}" value="{{ $calendarSel->id }}">
                            {{ $calendarSel->track->name }}
                        </option>
                    @empty
                        <option value="not-calendar">No existen jornadas creadas</option>
                    @endforelse
                </x-select-search>
                <!--<input type="text" class="w-full rounded-lg text-center bg-gray-200" value="1,5" disabled />-->
            </div>

            <div class="col-span-6 lg:col-span-1 lg:pr-2 border-r-0 lg:border-r-2 border-r-gray-600">
                <label class="block text-xs text-white font-bold mb-1">
                    Carrera:
                </label>
                <input type="text" class="w-full rounded-lg text-center bg-gray-200" value="{{ $raceCurrent }}" disabled />
            </div>

            <div class="col-span-6 lg:col-span-1 lg:pr-2 border-r-0 lg:border-r-2 border-r-gray-600">
                <label class="block text-xs text-white font-bold mb-1">
                    Tiempo:
                </label>
                <input type="text" class="w-full rounded-lg text-center bg-gray-200" value="{{ $this->getTimeToStart }} MTP" disabled />
            </div>

            <div class="col-span-12 lg:col-span-2 lg:pr-2 border-r-0 lg:border-r-2 border-r-gray-600">
                <label class="block text-xs text-white font-bold mb-1">
                    Tipo de apuesta:
                </label>
                <input 
                    type="text" 
                    class="w-full rounded-lg text-center bg-gray-200" 
                    value="{{ \Helper::getNameBetType($betTypeId, true) }}" 
                    disabled 
                />
            </div>

            <div class="col-span-12 lg:col-span-2 lg:pr-2 border-r-0 lg:border-r-2 border-r-gray-600">
                <label class="block text-xs text-white font-bold mb-1">
                    Valor de la apuesta:
                </label>
                <input value="{{ $monto }}" type="text" class="w-full rounded-lg text-center bg-gray-200" placeholder="0" disabled />
            </div>

            <div class="col-span-12 lg:col-span-2">
                <label class="block text-xs text-white font-bold mb-1">
                    Total de la apuesta:
                </label>
                @php
                    $cominationsBet = $this->totalPaymentBet;
                @endphp
                <input type="text" class="w-full rounded-lg text-lg font-bold text-center bg-gray-200" value="{{ number_format($cominationsBet, 0, '.', ',') }}" disabled />
            </div>
        </div>
    </div>
</div>
{{-- </x-slot> --}}