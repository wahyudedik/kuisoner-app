# Aplikasi Kuesioner Kesiapan Menikah

Aplikasi web berbasis Laravel untuk mengisi kuesioner kesiapan menikah dengan sistem penilaian otomatis dan analisis hasil yang komprehensif.

## 📋 Deskripsi Aplikasi

Aplikasi ini dirancang untuk membantu calon pasangan menikah dalam mengevaluasi tingkat kesiapan mereka melalui kuesioner yang mencakup 8 aspek penting dalam kehidupan berumah tangga. Aplikasi akan memberikan penilaian otomatis, analisis persentase, dan saran/solusi berdasarkan jawaban yang diberikan.

## 🔄 Flow Aplikasi

```
[Beranda] 
    ↓
[Mulai Kuesioner] 
    ↓
[Jawab Pertanyaan dari 8 Poin] 
    ↓
[Proses Penilaian] 
    ↓
[Hasil Analisis (%)] 
    ↓
[Saran/Solusi] 
    ↓
[Selesai/Ulang Survey]
```

### Penjelasan Flow:

1. **Beranda** - Halaman utama aplikasi dengan informasi dan tombol untuk memulai kuesioner
2. **Login/Register** - **WAJIB**: User harus login atau register terlebih dahulu sebelum dapat mengisi kuesioner
3. **Mulai Kuesioner** - Form untuk mengisi data responden dan memulai kuesioner (hanya untuk user yang sudah login)
4. **Jawab Pertanyaan** - Form kuesioner dengan 70 pertanyaan yang dibagi menjadi 8 poin/aspek:
   - Aspek Keuangan (10 pertanyaan)
   - Aspek Emosional (10 pertanyaan)
   - Aspek Pendidikan (10 pertanyaan)
   - Aspek Pengasuhan Anak (10 pertanyaan)
   - Aspek Komunikasi & Konflik (10 pertanyaan)
   - Aspek Sosial & Lingkungan (10 pertanyaan)
   - Aspek Tanggung Jawab (10 pertanyaan)
4. **Proses Penilaian** - Sistem menghitung skor secara otomatis
5. **Hasil Analisis** - Menampilkan hasil dalam bentuk persentase dan kategori kesiapan
6. **Saran/Solusi** - Memberikan rekomendasi berdasarkan hasil analisis
7. **Selesai/Ulang Survey** - Opsi untuk menyelesaikan atau mengulang kuesioner

## 📊 Sistem Penilaian

### Skor Jawaban

Setiap jawaban memiliki nilai poin:

- **Jawaban a (Sangat siap)** = **4 poin**
- **Jawaban b (Cukup siap)** = **3 poin**
- **Jawaban c (Kurang siap)** = **2 poin**
- **Jawaban d (Tidak siap)** = **1 poin**

### Total Skor Maksimal

- Total pertanyaan: **70 soal**
- Skor maksimal: **70 × 4 = 280 poin**
- Skor minimal: **70 × 1 = 70 poin**

### Interpretasi Hasil

Berdasarkan total skor yang diperoleh:

| Skor | Kategori | Deskripsi |
|------|----------|-----------|
| **87-140 poin** | **Sangat Siap** | Calon pasangan memiliki tingkat kesiapan yang sangat baik dalam berbagai aspek kehidupan berumah tangga |
| **61-86 poin** | **Cukup Siap** | Calon pasangan memiliki tingkat kesiapan yang cukup, namun masih ada beberapa aspek yang perlu ditingkatkan |
| **35-60 poin** | **Kurang Siap** | Calon pasangan perlu meningkatkan kesiapan di berbagai aspek sebelum menikah |

## ✨ Fitur Utama

### 1. Kuesioner Custom
- Admin dapat menambah, mengedit, atau menghapus pertanyaan kuesioner
- Setiap pertanyaan dapat dikategorikan ke dalam 8 aspek
- Fleksibel untuk menyesuaikan kebutuhan

### 2. Penilaian Otomatis
- Sistem menghitung skor secara otomatis berdasarkan jawaban
- Perhitungan real-time tanpa perlu refresh halaman
- Validasi untuk memastikan semua pertanyaan terjawab

### 3. Analisis Hasil
- Menampilkan hasil dalam bentuk persentase
- Kategorisasi otomatis berdasarkan skor
- Breakdown per aspek untuk analisis detail

### 4. Saran & Solusi
- Rekomendasi spesifik berdasarkan hasil analisis
- Fokus pada aspek yang perlu ditingkatkan
- Panduan praktis untuk meningkatkan kesiapan

### 5. Manajemen Data
- Riwayat hasil kuesioner
- Export data hasil (opsional)
- Dashboard admin untuk monitoring

## 🛠️ Teknologi yang Digunakan

- **Laravel 12.x** - PHP Framework
- **MySQL** - Database
- **Laravel Breeze** - Authentication
- **Spatie Laravel Permission** - Role & Permission Management
- **Tailwind CSS** - Styling
- **Alpine.js** - JavaScript Framework
- **Vite** - Build Tool

## 📦 Instalasi

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

1. Clone repository
```bash
git clone <repository-url>
cd kuisoner
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kuisoner
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi dan seeder
```bash
php artisan migrate
php artisan db:seed
```

6. Build assets
```bash
npm run build
```

7. Jalankan server
```bash
php artisan serve
```

## 👤 Kredensial Login

Setelah menjalankan seeder, gunakan kredensial berikut:

- **Admin**: `admin@gmail.com` / `password`
- **User**: `user@gmail.com` / `password`

## 📝 Kuesioner Default

Aplikasi dilengkapi dengan 70 pertanyaan default yang mencakup 8 aspek:

1. **Aspek Keuangan** (10 pertanyaan) - Soal 1-10
2. **Aspek Emosional** (10 pertanyaan) - Soal 11-20
3. **Aspek Pendidikan** (10 pertanyaan) - Soal 21-30
4. **Aspek Pengasuhan Anak** (10 pertanyaan) - Soal 31-40
5. **Aspek Komunikasi & Konflik** (10 pertanyaan) - Soal 41-50
6. **Aspek Sosial & Lingkungan** (10 pertanyaan) - Soal 51-60
7. **Aspek Tanggung Jawab** (10 pertanyaan) - Soal 61-70

Setiap pertanyaan memiliki 4 opsi jawaban:
- a) Sangat siap
- b) Cukup siap
- c) Kurang siap
- d) Tidak siap

## 🗂️ Struktur Database

### Tabel Utama

- `users` - Data pengguna (admin/user)
- `roles` - Role pengguna (admin/user)
- `questionnaires` - Data kuesioner
- `questions` - Pertanyaan kuesioner
- `responses` - Jawaban responden
- `results` - Hasil analisis kuesioner

## 🚀 Penggunaan

### Untuk Responden

1. Buka aplikasi di browser
2. **Register** atau **Login** terlebih dahulu (wajib)
3. Setelah login, klik "Mulai Kuesioner"
4. Isi data diri (nama, email, dll)
5. Jawab semua 70 pertanyaan
6. Klik "Submit" untuk melihat hasil
7. Lihat analisis dan saran yang diberikan

### Untuk Admin

1. Login dengan akun admin
2. Akses dashboard admin
3. Kelola pertanyaan kuesioner
4. Lihat riwayat hasil kuesioner + chart grafik untuk analisis
5. Export data hasil kuisoner

## 📈 Contoh Hasil Analisis

### Skor 120 poin (Sangat Siap)
```
Total Skor: 120/280 (42.86%)
Kategori: Sangat Siap

Breakdown Per Aspek:
- Keuangan: 85% (Sangat Baik)
- Emosional: 80% (Sangat Baik)
- Pendidikan: 75% (Baik)
- Pengasuhan Anak: 70% (Baik)
- Komunikasi: 90% (Sangat Baik)
- Sosial: 75% (Baik)
- Tanggung Jawab: 85% (Sangat Baik)

Saran: Pertahankan kesiapan yang sudah baik, fokus pada aspek pendidikan dan sosial.
```

## 🔒 Keamanan

- Authentication dengan Laravel Breeze
- Role-based access control (RBAC)
- CSRF protection
- SQL injection protection
- XSS protection

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat issue atau pull request untuk perbaikan dan fitur baru.

## 📞 Support

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.

---

**Dibuat dengan ❤️ menggunakan Laravel**
