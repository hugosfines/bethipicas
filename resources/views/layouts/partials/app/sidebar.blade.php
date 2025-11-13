@php
    $links = [
        [
            //'icon' => 'fa-solid fa-gauge',
            'icon' => 'fa-solid fa-home',
            'name' => 'Dashboard',
            'route' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
        ],
        [
            //'role' => ['SuperAdmin','Admin'],
            //'can' => 'view-campaigns',
            'icon' => 'fa-solid fa-mountain-sun',
            'name' => 'Taquilla',
            'route' => route('ticket.office'),
            'active' => request()->routeIs('ticket.office'),
        ],
        /* [
            'can' => 'do-audit',
            'icon' => 'fa-solid fa-circle-notch',
            'name' => 'Auditoria',
            'route' => route('audits.index'),
            'active' => request()->routeIs('audits.index'),
        ],
        [
            'can' => 'view-contacts',
            'icon' => 'fa-solid fa-users-rays',
            'name' => 'Contactos',
            'route' => route('leads.contacts.index'),
            'active' => request()->routeIs('leads.contacts.*'),
        ],
        [
            'icon' => 'fa-solid fa-newspaper',
            'name' => 'Nuevo Negocio',
            'route' => route('negotiations.create'),
            'active' => request()->routeIs('negotiations.*'),
        ],
        [
            'can' => 'import-negotiations',
            'icon' => 'fa-solid fa-file-import',
            'name' => 'Importar',
            'route' => route('admin.negotiations.import'),
            'active' => request()->routeIs('admin.negotiations.import'),
        ],
        [
            'dropdown' => [
                [
                    'name' => 'Tickets',
                    'route' => route('admin.tickets.index'),
                    'active' => request()->routeIs('admin.tickets.index'),
                ],
                [
                    'can' => 'create-tickets',
                    'name' => 'Crear Ticket',
                    'route' => route('admin.ticket.index'),
                    'active' => request()->routeIs('admin.ticket.*'),
                ],
            ],
            'can' => 'view-tickets',
            'icon' => 'fa-solid fa-network-wired',
            'name' => 'Servicio al cliente',
        ],
        [
            'can' => 'view-leads',
            'title' => 'LEADS',
            'icon' => 'fa-solid fa-box-open',
            'name' => 'No Tomados',
            'route' => route('leads.notakes.index'),
            'active' => request()->routeIs('leads.notakes.*'),
        ],
        [
            'can' => 'view-leads',
            'icon' => 'fa-solid fa-tags',
            'name' => 'Mis Leads',
            'route' => route('leads.takes.index'),
            'active' => request()->routeIs('leads.takes.*'),
        ],
        [
            'can' => 'view-leads',
            'icon' => 'fa-solid fa-lock',
            'name' => 'Mis Cerrados',
            'route' => route('leads.closeds.index'),
            'active' => request()->routeIs('leads.closeds.*'),
        ],
        [
            'can' => 'view-leads',
            'info' => true,
            'icon' => 'fa-solid fa-clock',
            'name' => 'Gestiones del día',
            'route' => route('programmings.managements.index'),
            'active' => request()->routeIs('programmings.managements.*'),
        ],
        [
            'can' => 'view-appointments',
            'title' => 'CITAS AGENDADAS',
            'icon' => 'fa-solid fa-box',
            'name' => 'Mis agendas',
            'route' => route('appointments.index'),
            'active' => request()->routeIs('appointments.index'),
        ],
        [
            'can' => 'view-appointments',
            'icon' => 'fa-solid fa-calendar-check',
            'name' => 'Mis Citas',
            'route' => route('appointments.schedule'),
            'active' => request()->routeIs('appointments.schedule'),
        ],
        [
            'can' => 'view-appointments',
            'icon' => 'fa-solid fa-calendar-days',
            'name' => 'Primeras Citas',
            'route' => route('appointments.first'),
            'active' => request()->routeIs('appointments.first'),
        ],
        [
            'title' => 'PAGOS',
            'can' => 'view-payments',
            'icon' => 'fa-solid fa-money-check',
            'name' => 'Mis Ventas',
            'route' => route('sales.index'),
            'active' => request()->routeIs('sales.*'),
        ],
        [
            'can' => 'view-sales',
            'title' => 'CARTERAS',
            'icon' => 'fa-solid fa-file-invoice-dollar',
            'name' => 'Cuentas x Cobrar',
            'route' => route('receivables.index'),
            'active' => request()->routeIs('receivables.*'),
        ],
        [
            'title' => 'REPORTES',
            'icon' => 'fa-solid fa-file-invoice-dollar',
            'name' => 'Reportes',
            'dropdown' => [
                [
                    'can' => 'view-networks',
                    'name' => 'Comentarios R.S.',
                    'route' => route('admin.networks.urgent'),
                    'active' => request()->routeIs('admin.networks.urgent'),
                ],
                [
                    'can' => 'view-networks',
                    'name' => 'Redes S. (Nuevo)',
                    'route' => route('admin.networks.index'),
                    'active' => request()->routeIs('admin.networks.index'),
                ],
                [
                    'name' => 'Cumpleañeros',
                    'route' => route('birthday.filter'),
                    'active' => request()->routeIs('birthday.filter'),
                ],
            ],
        ], */

    ];
@endphp

<aside id="logo-sidebar" 
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 transition-transform -translate-x-full bg-primary border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" 
    :class="{ 
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-primary dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @php
                $myRole = '';//MyRole();
            @endphp
            @foreach ($links as $link)
                @php
                    $continue = true;
                @endphp

                @if (isset($link['role']))
                    @php
                        $continue = in_array($myRole, $link['role']) ? true : false;
                    @endphp
                @endif

                @if (isset($link['can']))
                    @php
                        $continue = auth()->user()->can($link['can']) ? true : false;
                    @endphp
                @endif

                @if (count($link))
                    @if ($continue)
                        @if (isset($link['title']))
                            @if ($link['title'])
                                <li>
                                    <div class="text-base text-gray-500 font-semibold">
                                        {{ ucwords(strtolower($link['title']) ) }}
                                    </div>
                                </li>
                            @endif
                        @endif

                        @if (!isset($link['dropdown']))
                            <li> 
                                <a href="{{ $link['route'] }}"
                                    class="flex items-center justify-between p-2 text-gray-200 rounded-lg dark:text-white h-bg-primary-2 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-primary-2' : '' }}">
                                    <div>
                                        <span class="inline-flex w-6 h-6 justify-center items-center">
                                            <i class="{{ $link['icon'] }} text-gray-200"></i> 
                                        </span>
                                        <span class="ms-2">{{ $link['name'] }}</span>
                                    </div>
                                    {{-- @if (MyRole() == 'Telemarketer')
                                        @if (isset($link['info']))
                                            @php
                                                $countCalls = callNumbersToday();
                                            @endphp
                                            <div>
                                                <span class="px-1 py-0.5 rounded-full bg-white text-gray-600 text-xs">
                                                    {{ $countCalls }}
                                                </span>
                                            </div>
                                        @endif
                                    @endif --}}
                                </a>
                            </li> 
                        @else
                            <li x-data="{ open: false }">
                                <!-- Dropdown Trigger -->
                                <button @click="open = !open" class="flex items-center justify-between w-full p-2 text-base font-normal text-white rounded-lg hover:bg-gray-700 focus:outline-none">
                                    <div>
                                        <span class="inline-flex w-6 h-6 justify-center items-center">
                                            <i class="{{ $link['icon'] }} text-gray-200"></i> 
                                        </span>
                                        <span class="ms-2">{{ $link['name'] }}</span>
                                    </div>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- Dropdown Links -->
                                <ul x-show="open" x-transition class="space-y-2 pl-5 mt-2">
                                    @foreach ($link['dropdown'] as $itemDropdown)
                                        @php
                                            $continueDropdown = true;
                                        @endphp
                                        @if (isset($itemDropdown['can']))
                                            @php
                                                $continueDropdown = auth()->user()->can($itemDropdown['can']) ? true : false;
                                            @endphp
                                        @endif
                                        @if ($continueDropdown)
                                            <li>
                                                <a href="{{ $itemDropdown['route'] }}" class="block p-2 text-white rounded-lg hover:bg-gray-700 {{ $itemDropdown['active'] ? 'bg-primary-2' : '' }}">
                                                    {{ $itemDropdown['name'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                            
                    @endif
                @endif
            @endforeach
        </ul>
    </div>

    <!-- Footer Sidebar -->
    {{-- <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center">
                <!-- <span class="font-bold">JH</span> -->
                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-200">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">Premium Member</p>
            </div>
        </div>
    </div> --}}
</aside>