<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pertanyaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.questions.store') }}" method="POST">
                        @csrf

                        <!-- Question Text -->
                        <div class="mb-6">
                            <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Teks Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="question_text" 
                                id="question_text" 
                                rows="4" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('question_text') border-red-500 @enderror"
                                placeholder="Masukkan teks pertanyaan...">{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="category" 
                                id="category" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div class="mb-6">
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="order" 
                                id="order" 
                                value="{{ old('order', $maxOrder + 1) }}" 
                                required
                                min="1"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('order') border-red-500 @enderror">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Urutan pertanyaan dalam kuesioner</p>
                        </div>

                        <!-- Is Active -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    id="is_active" 
                                    value="1"
                                    {{ old('is_active', $canActivate) ? 'checked' : '' }}
                                    {{ !$canActivate ? 'disabled' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ !$canActivate ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 {{ !$canActivate ? 'text-gray-400' : '' }}">
                                    Aktifkan pertanyaan ini
                                </label>
                            </div>
                            @if($canActivate)
                                <p class="mt-1 text-sm text-gray-500">
                                    Pertanyaan yang tidak aktif tidak akan muncul dalam kuesioner
                                </p>
                            @else
                                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Peringatan:</strong> Maksimal 70 soal aktif. Saat ini sudah ada <strong>{{ $activeCount }}</strong> soal aktif.
                                        Soal baru akan dibuat dalam status tidak aktif. Soal ke-71 dan seterusnya akan otomatis dinonaktifkan.
                                    </p>
                                </div>
                            @endif
                            <p class="mt-1 text-xs text-gray-400">
                                Status saat ini: {{ $activeCount }}/{{ $maxActiveQuestions }} soal aktif
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

