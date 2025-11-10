<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Kuesioner Kesiapan Menikah - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Sweet Alert 2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-50 via-white to-pink-50">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="/" class="flex items-center space-x-3">
                                <x-application-logo class="w-10 h-10 fill-current text-blue-600" />
                                <h1 class="text-xl font-bold text-gray-900">Kuesioner Kesiapan Menikah</h1>
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="/" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Beranda
                            </a>
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="w-full max-w-md">
                    <!-- Logo & Title -->
                    <div class="text-center mb-8">
                        <a href="/" class="inline-block mb-4">
                            <x-application-logo class="w-24 h-24 fill-current text-blue-600 mx-auto" />
                        </a>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Kuesioner Kesiapan Menikah</h2>
                        <p class="text-sm text-gray-600">Evaluasi kesiapan Anda untuk kehidupan berumah tangga</p>
                    </div>

                    <!-- Form Card -->
                    <div class="bg-white rounded-lg shadow-xl p-8 border border-gray-100">
                        {{ $slot }}
                    </div>

                    <!-- Footer Link -->
                    <div class="mt-6 text-center">
                        <a href="/" class="text-sm text-gray-600 hover:text-gray-900">
                            ← Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sweet Alert Notifications -->
        <script>
            // Handle session flash messages with Sweet Alert
            @if(session('status'))
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: '{{ session('status') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Error!',
                    html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
        </script>
    </body>
</html>
