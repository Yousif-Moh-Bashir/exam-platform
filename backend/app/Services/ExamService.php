<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * Start a new exam attempt.
     */
    public function startAttempt(
        Student $student,
        Exam $exam
    ): Attempt {
        return DB::transaction(function () use ($student, $exam) {

            $attempt = Attempt::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'started_at' => now(),
                'status' => 'in_progress',
                'score' => 0,
            ]);

            foreach ($exam->questions as $question) {
                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'option_id' => null,
                    'is_flagged' => false,
                ]);
            }

            return $attempt;
        });
    }

    /**
     * Submit an exam attempt and calculate the score.
     */
    public function submitAttempt(
        Attempt $attempt
    ): Attempt {
        return DB::transaction(function () use ($attempt) {

            /*
             * Prevent submitting the same attempt twice.
             */
            if ($attempt->isCompleted()) {
                return $attempt;
            }

            /*
             * Load answers and selected options.
             */
            $attempt->load('answers.option');

            /*
             * Count correct answers.
             */
            $correct = $attempt->answers
                ->filter(function ($answer) {
                    return $answer->option
                        && $answer->option->is_correct;
                })
                ->count();

            /*
             * Mark the attempt as completed.
             */
            $attempt->update([
                'score' => $correct,
                'status' => 'completed',
                'submitted_at' => now(),
            ]);

            return $attempt->fresh();
        });
    }

    /**
     * Calculate the final result of an attempt.
     */
    public function calculateResult(
        Attempt $attempt
    ): array {
        $attempt->load('answers.option');

        $total = $attempt->answers->count();

        $answered = $attempt->answers
            ->whereNotNull('option_id');

        $correct = $answered
            ->filter(function ($answer) {
                return $answer->option
                    && $answer->option->is_correct;
            })
            ->count();

        $wrong = $answered->count() - $correct;

        $unanswered = $total - $answered->count();

        $percentage = $total > 0
            ? round(($correct / $total) * 100)
            : 0;

        return [
            'attempt_id' => $attempt->id,
            'score' => $correct,
            'percentage' => $percentage,
            'total' => $total,
            'correct' => $correct,
            'wrong' => $wrong,
            'unanswered' => $unanswered,
        ];
    }
}
