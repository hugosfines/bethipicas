{{-- @for ($i = 1; $i <= $numFollow; $i++)
    <div class="flex justify-center cols-span-1 relative {{ $numFollow > 1 ? 'w-20' : 'w-30' }} {{ $i < $numFollow ? 'border-r-2 border-r-gray-600' : 'border-r-0' }}">
        <div class="relative">
            <label class="block text-xs text-white font-bold mb-2">
                {{ $i }} carr.
            </label>
            <div class="grid gap-y-2">
                @for ($b = 1; $b <= 14; $b++)
                    <div>
                        <label class="inline-flex items-center justify-center">
                            <input type="checkbox" class="hidden peer">
                            <span class="bg-gray-700 text-white rounded-md cursor-pointer 
                                    hover:bg-gray-600 peer-checked:bg-orange-500 peer-checked:text-white 
                                    w-10 text-center border-b-2" style="border-color: {{ $colorNumbers[$b] }};">
                                {{ $b }}
                            </span>
                        </label>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endfor --}}
