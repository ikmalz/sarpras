<x-guest-layout>
    <!-- Logo dan Header -->
    <div class="text-center">
        <div class="mx-auto h-16 w-16 bg-gradient-to-br rounded-2xl flex items-center justify-center shadow-lg">
            <img src="{{ asset('images/logoTb.png') }}" alt="Ilustrasi" class="w-20 mt-6">
        </div>
        <h2 class="mt-6 text-3xl font-bold text-gray-900">Sistem Sarpras</h2>
        <p class="mt-2 text-sm text-gray-600">Manajemen Sarana & Prasarana</p>
    </div>

    <!-- Form Login -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-900">Masuk ke Dashboard</h3>
            <p class="text-sm text-gray-500 mt-1">Silakan masukkan kredensial Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <x-text-input id="email"
                        type="email"
                        name="email"
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        placeholder="nama@email.com"
                        required
                        autofocus />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <x-text-input id="password"
                        type="password"
                        name="password"
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        placeholder="••••••••"
                        required />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 text-gray-600 border-gray-300 rounded focus:ring-gray-500 focus:ring-2" />
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">
                        {{ __('Ingat saya') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 hover:underline transition-colors duration-200">
                    {{ __('Lupa password?') }}
                </a>
                @endif
            </div>

            <!-- Tombol Login -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-lg">
                    {{ __('Masuk ke Dashboard') }}
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>