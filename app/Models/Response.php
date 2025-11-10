<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'question_id',
        'answer',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    /**
     * Get the questionnaire that owns this response
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the question for this response
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get answer label
     */
    public function getAnswerLabelAttribute(): string
    {
        return Question::getAnswerOptions()[$this->answer] ?? '';
    }
}
