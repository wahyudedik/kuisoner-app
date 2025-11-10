<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kuesioner Kesiapan Menikah - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-900">Kuesioner Kesiapan Menikah</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Evaluasi Kesiapan Menikah Anda
                    </h2>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                        Kuesioner ini dirancang untuk membantu calon pasangan menikah dalam mengevaluasi tingkat
                        kesiapan mereka melalui 70 pertanyaan yang mencakup 7 aspek penting dalam kehidupan berumah
                        tangga.
                    </p>
                    @auth
                        <a href="{{ route('questionnaires.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Mulai Kuesioner
                        </a>
                    @else
                        <div class="space-x-4">
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-base text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Login
                            </a>
                        </div>
                        <p class="mt-4 text-sm text-gray-500">
                            Anda harus login atau register terlebih dahulu untuk mengisi kuesioner
                        </p>
                    @endauth
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">70 Pertanyaan Komprehensif</h3>
                        <p class="text-gray-600">Kuesioner mencakup 7 aspek penting: Keuangan, Emosional, Pendidikan,
                            Pengasuhan Anak, Komunikasi, Sosial, dan Tanggung Jawab.</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Analisis Otomatis</h3>
                        <p class="text-gray-600">Sistem akan menghitung skor secara otomatis dan memberikan analisis
                            mendalam tentang tingkat kesiapan Anda.</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Saran & Solusi</h3>
                        <p class="text-gray-600">Dapatkan rekomendasi spesifik berdasarkan hasil analisis untuk
                            meningkatkan kesiapan Anda.</p>
                    </div>
                </div>

                <!-- How It Works -->
                <div class="mt-16 bg-white rounded-lg shadow-sm p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Cara Kerja</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full mx-auto mb-4 font-bold text-lg">
                                1</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Login/Register</h4>
                            <p class="text-sm text-gray-600">Daftar atau login ke akun Anda</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full mx-auto mb-4 font-bold text-lg">
                                2</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Isi Data</h4>
                            <p class="text-sm text-gray-600">Isi data diri Anda</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full mx-auto mb-4 font-bold text-lg">
                                3</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Jawab Pertanyaan</h4>
                            <p class="text-sm text-gray-600">Jawab 70 pertanyaan dengan jujur</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full mx-auto mb-4 font-bold text-lg">
                                4</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Lihat Hasil</h4>
                            <p class="text-sm text-gray-600">Dapatkan analisis dan saran</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Kuesioner Kesiapan Menikah. Dibuat dengan ❤️ oleh wahyu dedik by
                    noteds.com.
                </p>
            </div>
        </footer>
    </div>
</body>

</html>
