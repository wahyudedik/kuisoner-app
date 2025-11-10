<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Hasil Kuesioner') }}
            </h2>
            <a href="{{ route('admin.results.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Hasil Analisis Kuesioner</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <strong>Responden:</strong> {{ $questionnaire->name }}<br>
                                <strong>Email Responden:</strong> {{ $questionnaire->email }}<br>
                                @if($questionnaire->phone)
                                    <strong>Telepon:</strong> {{ $questionnaire->phone }}
                                @endif
                            </div>
                            <div>
                                <strong>User:</strong> {{ $questionnaire->user->name }}<br>
                                <strong>Email User:</strong> {{ $questionnaire->user->email }}<br>
                                <strong>Tanggal:</strong> {{ $result->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Total Skor</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $result->total_score }}</p>
                            <p class="text-sm text-gray-500">dari 280 poin</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Persentase</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($result->percentage, 2) }}%</p>
                        </div>
                        <div class="text-center p-4 rounded-lg 
                            @if($result->category === 'sangat_siap') bg-green-50
                            @elseif($result->category === 'cukup_siap') bg-yellow-50
                            @else bg-red-50
                            @endif">
                            <p class="text-sm text-gray-600 mb-1">Kategori</p>
                            <p class="text-2xl font-bold 
                                @if($result->category === 'sangat_siap') text-green-700
                                @elseif($result->category === 'cukup_siap') text-yellow-700
                                @else text-red-700
                                @endif">
                                {{ $result->category_label }}
                            </p>
                        </div>
                    </div>

                    <!-- Category Badge -->
                    <div class="text-center">
                        @if($result->category === 'sangat_siap')
                            <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-green-100 text-green-800">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Sangat Siap
                            </span>
                        @elseif($result->category === 'cukup_siap')
                            <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-yellow-100 text-yellow-800">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Cukup Siap
                            </span>
                        @elseif($result->category === 'kurang_siap')
                            <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-orange-100 text-orange-800">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Kurang Siap
                            </span>
                        @else
                            <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-red-100 text-red-800">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tidak Siap
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Breakdown Per Aspek -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Breakdown Per Aspek</h3>
                    <div class="space-y-4">
                        @foreach($categoryScores as $category => $data)
                            @php
                                $percentage = $data['percentage'] ?? 0;
                                $score = $data['score'] ?? 0;
                                $maxScore = $data['max_score'] ?? 40;
                                $label = $categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category));
                                
                                // Determine status
                                if ($percentage >= 75) {
                                    $status = 'Sangat Baik';
                                    $statusColor = 'green';
                                } elseif ($percentage >= 60) {
                                    $status = 'Baik';
                                    $statusColor = 'blue';
                                } elseif ($percentage >= 40) {
                                    $status = 'Cukup';
                                    $statusColor = 'yellow';
                                } else {
                                    $status = 'Perlu Ditingkatkan';
                                    $statusColor = 'red';
                                }
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $label }}</p>
                                        <p class="text-xs text-gray-500">Skor: {{ $score }} / {{ $maxScore }} ({{ number_format($percentage, 1) }}%)</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($statusColor === 'green') bg-green-100 text-green-800
                                        @elseif($statusColor === 'blue') bg-blue-100 text-blue-800
                                        @elseif($statusColor === 'yellow') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $status }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full transition-all duration-300
                                        @if($statusColor === 'green') bg-green-500
                                        @elseif($statusColor === 'blue') bg-blue-500
                                        @elseif($statusColor === 'yellow') bg-yellow-500
                                        @else bg-red-500
                                        @endif" 
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Saran & Solusi -->
            @if($result->suggestions)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Saran & Solusi</h3>
                        <div class="prose max-w-none">
                            <div class="whitespace-pre-line text-gray-700">{{ $result->suggestions }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('admin.results.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

