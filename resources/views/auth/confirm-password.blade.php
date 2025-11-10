<x-guest-layout>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Konfirmasi Password</h3>
        <p class="text-sm text-gray-600 text-center mb-4">
            Ini adalah area aman. Silakan konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" 
                         class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required 
                         autocomplete="current-password"
                         placeholder="Masukkan password Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            {{ __('Konfirmasi') }}
        </button>
    </form>
</x-guest-layout>
