<?php

namespace App\Console\Commands;

use App\Models\Result;
use App\Models\Question;
use Illuminate\Console\Command;

class UpdateResultCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'results:update-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update result categories based on new percentage-based logic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating result categories...');

        $results = Result::with('questionnaire')->get();
        $maxScore = Question::active()->count() * 4;
        
        $updated = 0;
        $skipped = 0;

        foreach ($results as $result) {
            $totalScore = $result->total_score;
            $oldCategory = $result->category;
            
            // Calculate new category based on percentage
            $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            
            // Determine category based on new 4-category system
            $newCategory = 'tidak_siap';
            if ($percentage >= 72) {
                $newCategory = 'sangat_siap';
            } elseif ($percentage >= 50) {
                $newCategory = 'cukup_siap';
            } elseif ($percentage >= 25) {
                $newCategory = 'kurang_siap';
            }
            
            if ($oldCategory !== $newCategory) {
                $result->update(['category' => $newCategory]);
                $this->line("Updated Result ID {$result->id}: {$oldCategory} -> {$newCategory} (Score: {$totalScore}/{$maxScore}, Percentage: " . number_format($percentage, 2) . "%)");
                $updated++;
            } else {
                $skipped++;
            }
        }

        $this->info("Update completed! Updated: {$updated}, Skipped: {$skipped}");

        return Command::SUCCESS;
    }
}

