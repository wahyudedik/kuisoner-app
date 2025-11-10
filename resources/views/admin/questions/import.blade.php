<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import Pertanyaan dari Excel') }}
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Import Pertanyaan dari File Excel</h3>
                        <p class="text-sm text-gray-600">Upload file Excel (.xlsx atau .xls) yang berisi daftar pertanyaan.</p>
                    </div>

                    <!-- Format Info -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Format File Excel:</h4>
                        <p class="text-xs text-blue-800 mb-2">File Excel harus memiliki header pada baris pertama dengan kolom berikut:</p>
                        <ul class="text-xs text-blue-800 list-disc list-inside space-y-1">
                            <li><strong>Pertanyaan</strong> - Teks pertanyaan (wajib)</li>
                            <li><strong>Kategori</strong> - Kategori pertanyaan: Keuangan, Emosional, Pendidikan, Pengasuhan Anak, Komunikasi & Konflik, Sosial & Lingkungan, atau Tanggung Jawab (wajib)</li>
                            <li><strong>Urutan</strong> - Nomor urut (opsional, default: auto increment)</li>
                            <li><strong>Status Aktif</strong> - Aktif/Tidak Aktif (opsional, default: Aktif)</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('admin.questions.template') }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Template Excel
                            </a>
                        </div>
                    </div>

                    <!-- Import Form -->
                    <form action="{{ route('admin.questions.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih File Excel <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="file" 
                                id="file" 
                                accept=".xlsx,.xls"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('file') border-red-500 @enderror">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Format yang didukung: .xlsx, .xls (Maksimal 2MB)</p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Import Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

