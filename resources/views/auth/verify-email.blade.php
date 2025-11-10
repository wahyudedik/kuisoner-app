<x-guest-layout>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Verifikasi Email</h3>
        <div class="text-sm text-gray-600 text-center mb-4">
            <p class="mb-2">Terima kasih telah mendaftar!</p>
            <p>Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan ke email Anda.</p>
            <p class="mt-2">Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan email verifikasi baru.</p>
        </div>
    </div>

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                {{ __('Kirim Ulang Email Verifikasi') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
