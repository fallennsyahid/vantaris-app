<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,401,500,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body
    class="min-h-screen bg-linear-to-br from-indigo-50 to-cyan-50 flex items-center justify-center overflow-hidden relative">

    <div class="absolute inset-0 -z-50 login-bg pointer-events-none"></div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="absolute inset-0 -z-50 login-bg pointer-events-none"></div>

    <div class="flex items-center justify-center relative px-4">
        <div
            class="bg-white/50 w-full max-w-md sm:max-w-lg md:max-w-xl p-6 sm:p-8 md:p-10 backdrop-blur-md border-0 rounded-2xl shadow-2xl">

            <!-- Icon -->
            <div
                class="flex justify-center items-center bg-linear-to-tr from-primary to-secondary w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-2xl text-white text-2xl sm:text-3xl md:text-4xl mx-auto mb-4">
                <i class="fas fa-lock"></i>
            </div>

            <!-- Title -->
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-heading text-center mb-3">
                Selamat Datang Kembali
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-text text-center mb-6">
                Masuk ke dashboard admin untuk mengelola website Anda
            </p>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="mb-2 sm:mb-3 text-sm sm:text-base" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-text/50"></i>
                        </div>
                        <x-text-input id="email"
                            class="block pl-10 pr-10 py-3 sm:py-4 text-lg text-text font-medium rounded-xl w-full border-2 border-text/50 focus:border-primary transition-colors duration-300"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="Masukkan email anda" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="mb-2 sm:mb-3 text-sm sm:text-base" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-text/50"></i>
                        </div>
                        <x-text-input id="password"
                            class="block pl-10 pr-10 py-3 sm:py-4 text-lg text-text font-medium rounded-xl w-full border-2 border-text/50 focus:border-secondary transition-colors duration-300"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Masukkan password anda" />
                        <div id="show-password"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                            <i class="fas fa-eye text-text/50" id="password-icon"></i>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember + Forgot -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-primary shadow-sm w-4 h-4" name="remember">
                        <span class="ml-2 text-sm sm:text-base text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="underline text-sm sm:text-base text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <div>
                    <x-primary-button
                        class="bg-linear-to-r from-primary to-secondary text-white flex items-center justify-center font-semibold text-lg sm:text-xl w-full py-3 sm:py-4 rounded-xl hover:from-secondary hover:to-primary transform hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out">
                        <i class="fas fa-right-to-bracket mr-2"></i>
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</body>
<script>
    document.getElementById('show-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    });
</script>

</html>
