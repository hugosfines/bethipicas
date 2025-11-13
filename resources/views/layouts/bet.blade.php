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

    <style>
        .fl-wrapper {
            z-index: 1000 !important;
        }

        .loader {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    
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
    
    <div class="p-1 lg:px-4">
        <div class="mt-0">

            @isset($action)
                <div class="flex justify-between items-center lg:pl-6 mb-4">
                    <div class="mb-3">
                        {{ $action }}
                    </div>
                </div>
            @endisset
            
        
            <div class="border-2 border-gray-200 rounded-lg dark:border-gray-700">
                {{-- <div class="w-full flex items-center justify-between bg-gray-800 p-2 sm:px-2 lg:px-4">
                    <x-logo class="block h-12 w-auto" />

                    <x-nav-link href="{{ route('dashboard') }}" class="text-gray-100 hover:text-gray-200">
                        <i class="fas fa-arrow-circle-left text-gray-600 mr-2"></i> Dashboard
                    </x-nav-link>
                </div> --}}

                {{-- @if (isset($header))
                    {!! $header !!}
                @endif --}}

                <div class="p-0">
                    {{ $slot }}
                </div>

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