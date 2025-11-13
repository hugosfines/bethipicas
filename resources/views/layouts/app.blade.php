@props(['breadcrumbs' => []])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} {{ env('COMPANY_NAME', 'LUVANIA') }}</title>

    <link rel="icon" href="{{ asset('image\icon\icon_horse.ico') }}" type="image/x-icon">

    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="CRM">
    <meta property="og:description" content="CRM Online.">
    <meta property="og:image" content="https://cifes.com/image/clinic.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    
</head>

<body class="font-sans antialiased" 
    x-data="{ sidebarOpen: false }"
    :class="{
        'overflow-y-hidden': sidebarOpen
    }">
    
    <livewire:toasts />

    {{-- pantalla que se muestra detras en modo mobile --}}
    <div x-show="sidebarOpen" x-on:click="sidebarOpen = false"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 sm:hidden" 
        style="display: none;">

    </div>

    @include('layouts.partials.app.navigation')

    @include('layouts.partials.app.sidebar')


    @if (Auth::check())
        {{-- @if (Auth::user()->hasRole('Telemarketer'))
            <div 
                x-data="{
                    isOpen: false,
                    toggleMenu() {
                        this.isOpen = !this.isOpen;
                        this.icon = this.isOpen ? 'up' : 'down';
                    },
                    icon: 'down'
                }"
                class="fixed hidden lg:block w-full bg-primary-2 text-white p-4 sm:pl-64 z-30 top-[50px]"
                :class="{'lg:h-36': !isOpen, 'lg:h-12': isOpen}"
            >
                <!-- Contenido del menú -->
                <div class="relative py-2 px-4 h-36 overflow-auto" id="contentSsubMenu">

                    @livewire('metric.goal-progress')

                </div>
                <!-- ./Contenido del menú -->

                <button 
                    class="absolute right-2" 
                    :class="{'bottom-1 mb-1': !isOpen, 'bottom-0 mb-0': isOpen}" 
                    @click="toggleMenu"
                    onclick="changeMarginSubMenu()">
                    <i :class="icon === 'down' ? 'fas fa-sort-down' : 'fas fa-sort-up'"></i>
                </button>
            </div>
            <div class="relative block lg:mb-32" id="divsubMenu"></div>
        @endif --}}
    @endif
    
    <div class="p-2 lg:p-4 sm:ml-64">
        <div class="mt-12">
            
            <div class="flex justify-between items-center lg:pl-6 mb-4">

                {{-- @include('layouts.partials.admin.breadcrumb') --}}

                @isset($action)
                    <div class="mb-3">
                        {{ $action }}
                    </div>
                @endisset
            </div>
        
            <div class="p-0 md:p-4 pt-0 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                {{-- <div class="max-w-7xl mx-auto sm:px-4 lg:px-8 p-4 sm:p-1">
                    <x-application-logo class="block h-12 w-auto" />
                </div> --}}

                {{ $slot }}

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    

    @livewireScripts

    @stack('js')

    @if (session('swal'))
        <script>
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif

    <script>

        // Activar swal con livewire
        Livewire.on('swal', data => {
            Swal.fire(data[0]);
        });

        // Activar toastr con livewire
        Livewire.on('toastr', data => {
            switch (data[0]['icon']) {
                case 'success':
                    Toast.success(data[0]['text'], data[0]['title']);
                    break;

                    case 'warning':
                    Toast.warning(data[0]['text'], data[0]['title']);
                    break;

                    case 'danger':
                    Toast.danger(data[0]['text'], data[0]['title']);
                    break;

                    case 'info':
                    Toast.info(data[0]['text'], data[0]['title']);
                    break;
            
                default:
                    break;
            }
            
        });

    </script>
    
</body>

</html>