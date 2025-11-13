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
                    Crea tu cuenta
                </h2>
            </div>

            <x-validation-errors class="mb-4 bg-red-50 p-4 rounded-lg border border-red-200" />

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Nombre -->
                <div>
                    <x-label for="name" value="{{ __('Nombre') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="name" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name" 
                        placeholder="Tu nombre completo" />
                </div>

                <!-- UserName -->
                <div>
                    <x-label for="username" value="{{ __('Nombre de usuario') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="username" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all" 
                        type="text" 
                        name="username" 
                        :value="old('username')" 
                        required 
                        autocomplete="username" 
                        placeholder="nombredeusuario" />
                </div>

                <!-- Email -->
                <div>
                    <x-label for="email" value="{{ __('Correo electrónico') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="email" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username" 
                        placeholder="tucorreo@ejemplo.com" />
                </div>

                <!-- Contraseña -->
                <div>
                    <x-label for="password" value="{{ __('Contraseña') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="password" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password" 
                        placeholder="••••••••" />
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="password_confirmation" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-400 transition-all" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" 
                        placeholder="••••••••" />
                </div>

                <!-- Términos y Condiciones -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox 
                                    name="terms" 
                                    id="terms" 
                                    required 
                                    class="h-4 w-4 text-amber-500 focus:ring-amber-400 border-gray-300 rounded" />

                                <div class="ms-2 text-sm text-gray-600">
                                    {!! __('Acepto los :terms_of_service y :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-amber-500 hover:text-amber-600 font-medium">Términos de Servicio</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-amber-500 hover:text-amber-600 font-medium">Política de Privacidad</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-amber-500 hover:text-amber-600">
                        ¿Ya tienes una cuenta?
                    </a>

                    <x-button class="bg-amber-500 hover:bg-amber-600 focus:ring-amber-400">
                        {{ __('Registrarse') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
