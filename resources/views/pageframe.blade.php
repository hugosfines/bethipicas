<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} {{ env('COMPANY_NAME', 'LUVANIA') }}</title>

    {{-- @if( env('COMPANY_NAME', 'LUVANIA') == 'LUVANIA')
        <link rel="icon" href="{{ asset('images\icons\iconluvFINAL.ico') }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ asset('images\icons\iconoipsFINAL.ico') }}" type="image/x-icon">
    @endif --}}

    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Hipismo Online">
    <meta property="og:description" content="Apuestas Hipicas.">
    <!-- <meta property="og:image" content="https://cifes.com/image/clinic.png"> -->
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Estilos personalizados para el drawer (sustituto de DaisyUI) */
        .drawer {
            position: relative;
            overflow: hidden;
        }
        .drawer-toggle {
            position: absolute;
            opacity: 0;
        }
        .drawer-content {
            transition: margin-left 0.3s ease;
        }
        .drawer-side {
            position: fixed;
            left: -100%;
            top: 0;
            height: 100vh;
            transition: left 0.3s ease;
            z-index: 20;
        }
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 10;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .drawer-toggle:checked ~ .drawer-content {
            margin-left: 16rem;
        }
        .drawer-toggle:checked ~ .drawer-side {
            left: 0;
        }
        .drawer-toggle:checked ~ .drawer-overlay {
            opacity: 1;
            pointer-events: auto;
        }
        @media (min-width: 1024px) {
            .drawer-content {
                margin-left: 16rem;
            }
            .drawer-side {
                left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <!-- NUEVO HEADER CON LOGIN -->
    <header class="absolute top-0 left-0 right-0 z-10 hidden lg:block">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-end">
            <a href="{{ route('login') }}" class="flex items-center space-x-2 text-white hover:text-amber-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span class="font-medium">Acceso Clientes</span>
            </a>
        </div>
    </header>

    <!-- HERO SECTION CON BOTÓN DUAL -->
    <div class="flex items-center justify-center bg-gradient-to-r from-gray-900 to-gray-800 text-white py-4 sm:py-6">
        <div class="w-full max-w-7xl px-4">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-center sm:items-center sm:space-x-4">
                <!-- Botón Login -->
                <a href="{{ route('login') }}" 
                class="inline-block text-center bg-transparent hover:bg-white/20 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-full border-2 border-white transition-all hover:scale-105 text-sm sm:text-base">
                    Iniciar Sesión
                </a>
                
                <!-- Botón Register -->
                <a href="{{ route('register') }}" 
                class="inline-block text-center bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-full transition-all hover:scale-105 text-sm sm:text-base">
                    Regístrate Gratis
                </a>
            </div>
        </div>
    </div>

    <!-- Drawer Structure (Tailwind puro) -->
        <div class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('img/hipodromo-hero.jpg') }}" alt="Gulfstream Park" class="w-full h-full object-cover opacity-30">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2">
                    <h1 class="flex items-center justify-center space-x-2 text-4xl md:text-6xl font-serif font-bold mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-amber-400">Bet</span>Hípicas
                    </h1>
                </div>
                <p class="text-xl md:text-2xl max-w-2xl mx-auto">
                    La plataforma definitiva para apuestas hípicas en los mejores hipódromos de América
                </p>
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-full transition-transform hover:scale-105">
                        Regístrate Gratis
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hipódromos Destacados -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-serif font-bold text-center mb-12">
                Hipódromos <span class="text-amber-500">Destacados</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Gulfstream Park -->
                <div class="relative rounded-xl overflow-hidden shadow-lg group">
                    <img src="{{ asset('img/gulfstream-park.jpg') }}" alt="Gulfstream Park" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-amber-400 transition-colors">Gulfstream Park</h3>
                        <p class="text-sm opacity-90">Florida, USA • 38% favoritos ganadores</p>
                    </div>
                    <div class="absolute top-4 right-4 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                        LIVE
                    </div>
                </div>

                <!-- Santa Anita -->
                <div class="relative rounded-xl overflow-hidden shadow-lg group">
                    <img src="{{ asset('img/santa-anita.jpg') }}" alt="Santa Anita" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-amber-400 transition-colors">Santa Anita</h3>
                        <p class="text-sm opacity-90">California, USA • 48% favoritos ganadores</p>
                    </div>
                </div>

                <!-- Churchill Downs -->
                <div class="relative rounded-xl overflow-hidden shadow-lg group">
                    <img src="{{ asset('img/churchill-downs.jpg') }}" alt="Churchill Downs" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-amber-400 transition-colors">Churchill Downs</h3>
                        <p class="text-sm opacity-90">Kentucky, USA • Kentucky Derby</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de Apuestas -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-serif font-bold text-center mb-12">
                Apuestas <span class="text-amber-500">Exóticas</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Exacta -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold">E</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Exacta</h3>
                    <p class="text-gray-600">Primero y segundo en orden exacto</p>
                </div>

                <!-- Trifecta -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold">T</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Trifecta</h3>
                    <p class="text-gray-600">Primero, segundo y tercero en orden exacto</p>
                </div>

                <!-- Superfecta -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold">S</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Superfecta</h3>
                    <p class="text-gray-600">Primeros cuatro en orden exacto</p>
                </div>

                <!-- Pick 6 -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold">6</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Pick 6</h3>
                    <p class="text-gray-600">Acierta los ganadores de 6 carreras</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-serif font-bold text-center mb-12">
                Opiniones de <span class="text-amber-500">Nuestros Clientes</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonio 1 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('img/testimonio-1.jpg') }}" alt="Juan Pérez" class="w-12 h-12 rounded-full object-cover border-2 border-amber-400">
                        <div class="ml-4">
                            <h4 class="font-bold">Juan Pérez</h4>
                            <p class="text-sm text-amber-600">Apostador Profesional</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "Gané $2,500 con una superfecta en Santa Anita. La plataforma es intuitiva y las estadísticas en tiempo real son increíbles."
                    </p>
                    <div class="mt-3 flex text-amber-400">
                        ★★★★★
                    </div>
                </div>

                <!-- Testimonio 2 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('img/testimonio-2.jpg') }}" alt="María González" class="w-12 h-12 rounded-full object-cover border-2 border-amber-400">
                        <div class="ml-4">
                            <h4 class="font-bold">María González</h4>
                            <p class="text-sm text-amber-600">Nueva en apuestas</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "Empecé hace un mes y ya recuperé mi inversión. Las guías para principiantes me ayudaron mucho a entender las apuestas exóticas."
                    </p>
                    <div class="mt-3 flex text-amber-400">
                        ★★★★☆
                    </div>
                </div>

                <!-- Testimonio 3 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('img/testimonio-3.jpg') }}" alt="Carlos Rojas" class="w-12 h-12 rounded-full object-cover border-2 border-amber-400">
                        <div class="ml-4">
                            <h4 class="font-bold">Carlos Rojas</h4>
                            <p class="text-sm text-amber-600">Fanático del Derby</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "Nunca falla para el Kentucky Derby. El año pasado acerté el exacta y me pagaron 3 veces más que en otras casas."
                    </p>
                    <div class="mt-3 flex text-amber-400">
                        ★★★★★
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Llamada a la Acción -->
    <section class="py-16 bg-gradient-to-r from-amber-500 to-amber-600 text-white">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl md:text-4xl font-serif font-bold mb-6">
                ¿Listo para vivir la emoción de las carreras?
            </h2>
            <p class="text-xl mb-8">
                Regístrate hoy y obtén un <span class="font-bold">bono de bienvenida de $20</span> en tu primera apuesta.
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-gray-900 hover:bg-black text-white font-bold py-3 px-8 rounded-full transition-all hover:scale-105 shadow-lg">
                Comenzar Ahora
            </a>
        </div>
    </section>
</body>
</html>