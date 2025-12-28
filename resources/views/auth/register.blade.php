<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-gray-600 font-bold" />
            <x-text-input id="name"
                class="block mt-1 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                placeholder="Nama Lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-600 font-bold" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="alamat@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-600 font-bold" />

            <x-text-input id="password"
                class="block mt-1 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                class="text-gray-600 font-bold" />

            <x-text-input id="password_confirmation"
                class="block mt-1 w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button
                class="ms-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 px-6 py-2.5 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>