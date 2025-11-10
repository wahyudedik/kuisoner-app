<x-guest-layout>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Lupa Password</h3>
        <p class="text-sm text-gray-600 text-center mb-4">
            Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
        </p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" 
                         class="block mt-1 w-full" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autofocus
                         placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            {{ __('Kirim Link Reset Password') }}
        </button>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                ← Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
