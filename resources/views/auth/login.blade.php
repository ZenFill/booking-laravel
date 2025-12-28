<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-600 font-bold" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="masukkan email anda..." />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-600 font-bold" />

            <x-text-input id="password"
                class="block mt-1 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif

            <x-primary-button
                class="ms-3 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 px-6 py-2.5 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5">
                {{ __('Masuk Sekarang') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar
                di sini</a>
        </div>
    </form>
</x-guest-layout>