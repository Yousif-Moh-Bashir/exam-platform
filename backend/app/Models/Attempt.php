<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'started_at',
        'submitted_at',
        'score',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function hasExpired(): bool
    {
        if (! $this->started_at) {
            return false;
        }

        $exam = $this->relationLoaded('exam')
            ? $this->exam
            : $this->load('exam')->exam;

        return now()->greaterThanOrEqualTo(
            $this->started_at->copy()->addMinutes(
                $exam->duration_minutes
            )
        );
    }
}
