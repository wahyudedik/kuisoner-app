<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Aspek Keuangan (1-10)
            ['question_text' => 'Saya memiliki sumber penghasilan tetap atau rencana jelas untuk memenuhi kebutuhan keluarga.', 'category' => 'keuangan', 'order' => 1],
            ['question_text' => 'Saya mampu mengatur pengeluaran dan membuat rencana keuangan bulanan.', 'category' => 'keuangan', 'order' => 2],
            ['question_text' => 'Saya memiliki tabungan atau dana darurat pribadi.', 'category' => 'keuangan', 'order' => 3],
            ['question_text' => 'Saya bisa membedakan antara kebutuhan dan keinginan dalam pengeluaran.', 'category' => 'keuangan', 'order' => 4],
            ['question_text' => 'Saya memiliki pemahaman tentang cara mengelola hutang dengan bijak.', 'category' => 'keuangan', 'order' => 5],
            ['question_text' => 'Saya sudah menyiapkan rencana finansial jangka panjang setelah menikah.', 'category' => 'keuangan', 'order' => 6],
            ['question_text' => 'Saya merasa mampu menjadi penanggung jawab finansial dalam keluarga.', 'category' => 'keuangan', 'order' => 7],
            ['question_text' => 'Saya memiliki kebiasaan menabung untuk kebutuhan masa depan.', 'category' => 'keuangan', 'order' => 8],
            ['question_text' => 'Saya siap berbagi dan mengelola keuangan bersama pasangan dengan transparan.', 'category' => 'keuangan', 'order' => 9],
            ['question_text' => 'Saya sudah mempertimbangkan biaya-biaya rumah tangga dan kehidupan setelah menikah.', 'category' => 'keuangan', 'order' => 10],

            // Aspek Emosional (11-20)
            ['question_text' => 'Saya mampu mengendalikan emosi ketika menghadapi masalah dengan pasangan.', 'category' => 'emosional', 'order' => 11],
            ['question_text' => 'Saya terbuka terhadap kritik dan saran dari pasangan.', 'category' => 'emosional', 'order' => 12],
            ['question_text' => 'Saya dapat memaafkan kesalahan pasangan tanpa menyimpan dendam.', 'category' => 'emosional', 'order' => 13],
            ['question_text' => 'Saya siap menghadapi tekanan atau tanggung jawab baru dalam rumah tangga.', 'category' => 'emosional', 'order' => 14],
            ['question_text' => 'Saya memiliki cara positif untuk mengatasi stres dan konflik.', 'category' => 'emosional', 'order' => 15],
            ['question_text' => 'Saya bisa menahan diri untuk tidak berkata kasar atau menyakiti pasangan saat marah.', 'category' => 'emosional', 'order' => 16],
            ['question_text' => 'Saya merasa percaya diri dengan kemampuan diri sendiri.', 'category' => 'emosional', 'order' => 17],
            ['question_text' => 'Saya bisa menempatkan ego pribadi demi kebaikan bersama.', 'category' => 'emosional', 'order' => 18],
            ['question_text' => 'Saya siap berbagi perasaan dan pikiran secara terbuka dengan pasangan.', 'category' => 'emosional', 'order' => 19],
            ['question_text' => 'Saya bisa menerima kekurangan pasangan tanpa mudah menuntut perubahan.', 'category' => 'emosional', 'order' => 20],

            // Aspek Pendidikan (21-30)
            ['question_text' => 'Saya memahami pentingnya pendidikan bagi kehidupan keluarga di masa depan.', 'category' => 'pendidikan', 'order' => 21],
            ['question_text' => 'Saya berkomitmen untuk terus belajar dan mengembangkan diri.', 'category' => 'pendidikan', 'order' => 22],
            ['question_text' => 'Saya memiliki rencana untuk menyeimbangkan pendidikan dan kehidupan rumah tangga.', 'category' => 'pendidikan', 'order' => 23],
            ['question_text' => 'Saya siap mendukung pasangan dalam melanjutkan pendidikan atau karier.', 'category' => 'pendidikan', 'order' => 24],
            ['question_text' => 'Saya yakin tingkat pendidikan saya cukup untuk menopang kebutuhan keluarga.', 'category' => 'pendidikan', 'order' => 25],
            ['question_text' => 'Saya memiliki wawasan tentang pentingnya pendidikan anak di masa depan.', 'category' => 'pendidikan', 'order' => 26],
            ['question_text' => 'Saya mampu mengatur waktu antara belajar, bekerja, dan kehidupan rumah tangga.', 'category' => 'pendidikan', 'order' => 27],
            ['question_text' => 'Saya percaya bahwa pendidikan membantu memperkuat hubungan dalam keluarga.', 'category' => 'pendidikan', 'order' => 28],
            ['question_text' => 'Saya siap beradaptasi dengan perubahan yang diperlukan demi pendidikan keluarga.', 'category' => 'pendidikan', 'order' => 29],
            ['question_text' => 'Saya bersedia berkorban waktu atau tenaga demi pendidikan diri dan keluarga.', 'category' => 'pendidikan', 'order' => 30],

            // Aspek Pengasuhan Anak (31-40)
            ['question_text' => 'Saya memiliki pengetahuan dasar tentang cara merawat bayi dan anak kecil.', 'category' => 'pengasuhan_anak', 'order' => 31],
            ['question_text' => 'Saya memahami bahwa anak membutuhkan waktu, perhatian, dan kesabaran yang besar.', 'category' => 'pengasuhan_anak', 'order' => 32],
            ['question_text' => 'Saya siap menunda memiliki anak jika kondisi belum stabil secara ekonomi atau mental.', 'category' => 'pengasuhan_anak', 'order' => 33],
            ['question_text' => 'Saya tahu pentingnya memberikan kasih sayang dan komunikasi pada anak.', 'category' => 'pengasuhan_anak', 'order' => 34],
            ['question_text' => 'Saya memahami peran ayah dan ibu dalam tumbuh kembang anak.', 'category' => 'pengasuhan_anak', 'order' => 35],
            ['question_text' => 'Saya siap belajar dan mencari informasi tentang pola asuh yang baik.', 'category' => 'pengasuhan_anak', 'order' => 36],
            ['question_text' => 'Saya sadar bahwa setiap anak memiliki karakter dan kebutuhan yang berbeda.', 'category' => 'pengasuhan_anak', 'order' => 37],
            ['question_text' => 'Saya siap berperan aktif dalam pendidikan anak sejak dini.', 'category' => 'pengasuhan_anak', 'order' => 38],
            ['question_text' => 'Saya siap mengutamakan kebutuhan anak dibanding keinginan pribadi.', 'category' => 'pengasuhan_anak', 'order' => 39],
            ['question_text' => 'Saya siap menjadi contoh dan teladan yang baik bagi anak.', 'category' => 'pengasuhan_anak', 'order' => 40],

            // Aspek Komunikasi & Konflik (41-50)
            ['question_text' => 'Saya dapat tetap tenang saat menghadapi perbedaan pendapat dengan pasangan.', 'category' => 'komunikasi', 'order' => 41],
            ['question_text' => 'Saya bisa mencari solusi bersama tanpa menyalahkan pasangan.', 'category' => 'komunikasi', 'order' => 42],
            ['question_text' => 'Saya mampu menunda emosi dan berbicara setelah suasana lebih tenang.', 'category' => 'komunikasi', 'order' => 43],
            ['question_text' => 'Saya dapat mendengarkan pendapat pasangan tanpa memotong pembicaraan.', 'category' => 'komunikasi', 'order' => 44],
            ['question_text' => 'Saya tidak mudah mengungkit kesalahan lama saat terjadi pertengkaran.', 'category' => 'komunikasi', 'order' => 45],
            ['question_text' => 'Saya berusaha memahami alasan di balik perilaku pasangan sebelum menilai.', 'category' => 'komunikasi', 'order' => 46],
            ['question_text' => 'Saya lebih memilih berdiskusi daripada diam atau menghindar saat ada masalah.', 'category' => 'komunikasi', 'order' => 47],
            ['question_text' => 'Saya tidak menggunakan kata-kata yang menyakiti saat marah.', 'category' => 'komunikasi', 'order' => 48],
            ['question_text' => 'Saya mampu mengalah demi menjaga hubungan tetap harmonis.', 'category' => 'komunikasi', 'order' => 49],
            ['question_text' => 'Saya dapat menilai konflik secara objektif tanpa didominasi oleh emosi.', 'category' => 'komunikasi', 'order' => 50],

            // Aspek Sosial & Lingkungan (51-60)
            ['question_text' => 'Saya mampu beradaptasi dengan keluarga pasangan setelah menikah.', 'category' => 'sosial', 'order' => 51],
            ['question_text' => 'Saya bisa menjaga hubungan baik dengan tetangga dan lingkungan sekitar.', 'category' => 'sosial', 'order' => 52],
            ['question_text' => 'Saya menghormati perbedaan kebiasaan antara keluarga saya dan keluarga pasangan.', 'category' => 'sosial', 'order' => 53],
            ['question_text' => 'Saya siap menerima masukan dari orang lain tanpa mudah tersinggung.', 'category' => 'sosial', 'order' => 54],
            ['question_text' => 'Saya mampu berkomunikasi sopan dan terbuka dengan keluarga besar.', 'category' => 'sosial', 'order' => 55],
            ['question_text' => 'Saya bisa menyesuaikan diri dalam acara sosial seperti kumpul keluarga atau lingkungan.', 'category' => 'sosial', 'order' => 56],
            ['question_text' => 'Saya bisa menjaga batas antara urusan pribadi dan sosial dengan bijak.', 'category' => 'sosial', 'order' => 57],
            ['question_text' => 'Saya bersedia ikut berpartisipasi dalam kegiatan sosial masyarakat.', 'category' => 'sosial', 'order' => 58],
            ['question_text' => 'Saya bisa menghargai pandangan dan nilai-nilai sosial pasangan.', 'category' => 'sosial', 'order' => 59],
            ['question_text' => 'Saya siap membangun hubungan harmonis antara keluarga saya dan keluarga pasangan.', 'category' => 'sosial', 'order' => 60],

            // Aspek Tanggung Jawab (61-70)
            ['question_text' => 'Saya siap menjalankan peran dan kewajiban saya dalam rumah tangga.', 'category' => 'tanggung_jawab', 'order' => 61],
            ['question_text' => 'Saya sadar bahwa pernikahan membutuhkan komitmen jangka panjang.', 'category' => 'tanggung_jawab', 'order' => 62],
            ['question_text' => 'Saya siap bekerja keras demi kesejahteraan keluarga.', 'category' => 'tanggung_jawab', 'order' => 63],
            ['question_text' => 'Saya mampu mengatur waktu antara pekerjaan, keluarga, dan istirahat.', 'category' => 'tanggung_jawab', 'order' => 64],
            ['question_text' => 'Saya siap menepati janji dan tanggung jawab yang saya buat.', 'category' => 'tanggung_jawab', 'order' => 65],
            ['question_text' => 'Saya menyadari bahwa keputusan saya akan berdampak pada keluarga.', 'category' => 'tanggung_jawab', 'order' => 66],
            ['question_text' => 'Saya mampu bertanggung jawab atas kesalahan saya tanpa menyalahkan orang lain.', 'category' => 'tanggung_jawab', 'order' => 67],
            ['question_text' => 'Saya siap menjaga komitmen pernikahan meskipun ada masalah berat.', 'category' => 'tanggung_jawab', 'order' => 68],
            ['question_text' => 'Saya berusaha menjadi panutan yang baik bagi pasangan dan anak-anak saya kelak.', 'category' => 'tanggung_jawab', 'order' => 69],
            ['question_text' => 'Saya memahami bahwa tanggung jawab rumah tangga adalah tugas bersama.', 'category' => 'tanggung_jawab', 'order' => 70],
        ];

        foreach ($questions as $question) {
            DB::table('questions')->insert([
                'question_text' => $question['question_text'],
                'category' => $question['category'],
                'order' => $question['order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('70 pertanyaan kuesioner berhasil dibuat!');
    }
}
