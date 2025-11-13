<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-2xl border border-gray-200">
            <!-- Logo -->
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <a href="{{ url('/') }}">
                        <span class="text-3xl font-serif font-bold text-gray-800">
                            Bet<span class="text-amber-500">Hípicas</span>
                        </span>
                    </a>
                </div>
                <h2 class="mt-6 text-2xl font-serif font-bold text-gray-900">
                    Inicia sesión en tu cuenta
                </h2>
            </div>

            <x-validation-errors class="mb-4 bg-red-50 p-4 rounded-lg border border-red-200" />

            <!-- Formulario -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <input 
                        id="username" 
                        name="username" 
                        type="username" 
                        value="{{ old('username') }}"
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all"
                        placeholder="tuusuario">
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all"
                        placeholder="••••••••">
                </div>

                <!-- Recordar sesión -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-amber-500 focus:ring-amber-400 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Recordar sesión
                        </label>
                    </div>

                    <!-- Olvidé contraseña -->
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-amber-500 hover:text-amber-600">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>

                <!-- Botón Submit -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400 transition-all hover:scale-105">
                        Acceder
                    </button>
                </div>
            </form>

            <!-- Registro -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta? 
                    <a href="{{ route('register') }}" class="font-medium text-amber-500 hover:text-amber-600">
                        Regístrate gratis
                    </a>
                </p>
            </div>
        </div>
    </div>

</x-guest-layout>