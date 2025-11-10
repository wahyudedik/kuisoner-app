<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jawab Pertanyaan') }}
            </h2>
            <div class="text-sm text-gray-600">
                Progress: <span id="progress-count">0</span> / {{ $questionsByCategory->flatten()->count() }} pertanyaan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('questionnaires.update', $questionnaire) }}" method="POST" id="questionnaire-form">
                @csrf
                @method('PUT')

                @php
                    $questionNumber = 1; // Counter global untuk nomor pertanyaan
                @endphp

                @foreach($questionsByCategory as $category => $questions)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 category-section" data-category="{{ $category }}">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                {{ $categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category)) }}
                                <span class="text-sm font-normal text-gray-500">({{ $questions->count() }} pertanyaan)</span>
                            </h3>

                            <div class="space-y-6">
                                @foreach($questions as $index => $question)
                                    <div class="border-b border-gray-200 pb-6 last:border-b-0 question-item" data-question-id="{{ $question->id }}">
                                        <div class="mb-3">
                                            <label class="text-base font-medium text-gray-900">
                                                {{ $questionNumber }}. {{ $question->question_text }}
                                            </label>
                                        </div>
                                        @php
                                            $questionNumber++; // Increment nomor untuk pertanyaan berikutnya
                                        @endphp

                                        <div class="space-y-2">
                                            @php
                                                $answerOptions = [
                                                    'a' => 'Sangat siap',
                                                    'b' => 'Cukup siap',
                                                    'c' => 'Kurang siap',
                                                    'd' => 'Tidak siap',
                                                ];
                                                $currentAnswer = $responses[$question->id] ?? null;
                                            @endphp

                                            @foreach($answerOptions as $key => $label)
                                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-gray-50 transition-colors {{ $currentAnswer === $key ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                                    <input 
                                                        type="radio" 
                                                        name="answers[{{ $question->id }}]" 
                                                        value="{{ $key }}"
                                                        {{ $currentAnswer === $key ? 'checked' : '' }}
                                                        required
                                                        class="mr-3 text-blue-600 focus:ring-blue-500 question-answer"
                                                        data-question-id="{{ $question->id }}">
                                                    <span class="text-sm text-gray-700">
                                                        <strong>{{ strtoupper($key) }})</strong> {{ $label }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Progress Bar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 sticky bottom-0">
                    <div class="p-4">
                        <div class="mb-2 flex justify-between text-sm text-gray-600">
                            <span>Progress</span>
                            <span id="progress-text">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                Pastikan semua pertanyaan sudah dijawab sebelum submit.
                            </p>
                            <button 
                                type="submit" 
                                id="submit-btn"
                                class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                                Submit Kuesioner
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('questionnaire-form');
            const totalQuestions = {{ $questionsByCategory->flatten()->count() }};
            const progressCount = document.getElementById('progress-count');
            const progressText = document.getElementById('progress-text');
            const progressBar = document.getElementById('progress-bar');
            const submitBtn = document.getElementById('submit-btn');

            function updateProgress() {
                const answered = document.querySelectorAll('.question-answer:checked').length;
                const percentage = Math.round((answered / totalQuestions) * 100);
                
                progressCount.textContent = answered;
                progressText.textContent = percentage + '%';
                progressBar.style.width = percentage + '%';

                // Enable/disable submit button
                if (answered === totalQuestions) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }

            // Update progress on answer change
            document.querySelectorAll('.question-answer').forEach(radio => {
                radio.addEventListener('change', updateProgress);
            });

            // Initial progress update
            updateProgress();

            // Form validation
            form.addEventListener('submit', function(e) {
                const answered = document.querySelectorAll('.question-answer:checked').length;
                if (answered !== totalQuestions) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan jawab semua pertanyaan terlebih dahulu.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return false;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

