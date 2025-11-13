<nav class="fixed top-0 z-50 w-full bg-primary border-b border-primary dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end text-white">
                <button x-on:click="sidebarOpen = !sidebarOpen" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                    aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="/dashboard" class="flex ms-2 md:me-24">
                    <span class="hidden md:block">
                        <x-logo class="block h-10 w-auto" />
                    </span>
                    {{-- <img src="{{ asset('image/logo/logo-hipismo.png') }}" class="h-8 me-3 hidden md:block" alt="FlowBite Logo" />
                    <span class="hidden md:block self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                        {{ env('APP_NAME', 'Apuestas Hipicas') }}
                    </span> --}}
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <span class="inline-flex rounded-md">
                                    <button class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        <span class="text-white ml-2 hidden md:block">{{ Auth::user()->name }}</span>
                                    </button>
                                </span> 
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{-- MyRole() --}}
                            </div>

                            {{-- @if (Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('Admin'))
                                <x-dropdown-link href="{{ route('admin.dashboard') }}" target="_blank">
                                    {{ __('Administrador') }}
                                </x-dropdown-link>
                            @endif --}}
                            
                            <!-- ERP -->
                            @php
                                $erpAccess = false;
                                $currentUser = auth()->user();
                                $myRole = '';//MyRole();

                                $userHeadquarters = [];//$currentUser->headquarters()->pluck('headquarter_id');

                                /* if((\App\Models\ApiConfigureHeadquarter::whereIn('headquarter_id', $userHeadquarters)->exists() && $myRole != 'Telemarketer') || $myRole == 'SuperAdmin') {
                                    $erpAccess = true;
                                } */
                            @endphp
                            @if($erpAccess && Gate::any(['erp-commercial-valuation', 'erp-medical-record', 'erp-appointment-master']))
                                <x-dropdown-link href="{{ route('nova.valuation.commercial.list') }}" target="_blank">
                                    {{ __('ERP') }}
                                </x-dropdown-link>
                            @endif
                            <!-- ./ERP -->

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                Mi perfil {{-- __('Profile') --}}
                            </x-dropdown-link>

                            @can('create-audit')
                                <x-dropdown-link href="{{ route('admin.audits.index') }}">
                                    Auditoria
                                </x-dropdown-link>
                            @endcan

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    Cerrar sesión {{-- __('Log Out') --}}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                </div>
            </div>
        </div>
    </div>
</nav>
