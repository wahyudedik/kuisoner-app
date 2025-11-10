<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->integer('total_score'); // Total skor dari semua jawaban
            $table->decimal('percentage', 5, 2); // Persentase dari skor maksimal
            $table->enum('category', ['sangat_siap', 'cukup_siap', 'kurang_siap']);
            $table->json('category_scores')->nullable(); // Breakdown skor per kategori
            $table->text('suggestions')->nullable(); // Saran/solusi
            $table->timestamps();
            
            // One result per questionnaire
            $table->unique('questionnaire_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
