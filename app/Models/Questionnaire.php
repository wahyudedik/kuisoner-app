<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Questionnaire extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this questionnaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all responses for this questionnaire
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Get the result for this questionnaire
     */
    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }

    /**
     * Check if questionnaire is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark questionnaire as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Get total score from responses
     */
    public function getTotalScore(): int
    {
        return $this->responses()->sum('score');
    }

    /**
     * Get score by category
     */
    public function getScoreByCategory(string $category): int
    {
        return $this->responses()
            ->whereHas('question', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->sum('score');
    }

    /**
     * Check if all questions are answered
     */
    public function isAllQuestionsAnswered(): bool
    {
        $totalQuestions = Question::active()->count();
        $answeredQuestions = $this->responses()->count();
        
        return $totalQuestions === $answeredQuestions;
    }
}
