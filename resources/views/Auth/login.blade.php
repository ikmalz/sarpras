<x-guest-layout>
    <div class="flex max-w-5xl mx-auto my-36 shadow-lg rounded-lg overflow-hidden">
        <!-- Kiri: Ilustrasi -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 to-teal-500 items-center justify-center text-white p-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold mb-3">Sistem Sarpras</h1>
                <p class="text-base opacity-90">Manajemen Sarana & Prasarana Terpusat</p>
                <img src="{{ asset('images/logoTb.png') }}" alt="Ilustrasi" class="w-20 mt-6">
            </div>
        </div>

        <!-- Kanan: Form Login -->
        <div class="w-full lg:w-1/2 p-8 flex items-center justify-center bg-white">
            <div class="w-full max-w-sm">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <h2 class="text-xl font-bold text-gray-800 mb-4">Login Admin</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" class="w-full mt-1" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password" class="w-full mt-1" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" />
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</label>
                    </div>

                    <!-- Tombol Login -->
                    <div class="flex justify-between items-center">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-emerald-600 hover:underline">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif

                        <x-primary-button class="ml-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>

                <p class="mt-5 text-sm text-gray-600 text-center">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">Daftar</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>