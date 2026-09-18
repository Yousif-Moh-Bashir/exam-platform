<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttemptResource;
use App\Http\Resources\ResultResource;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Student;
use App\Services\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    /**
     * Start a new exam attempt.
     */
    public function store(
        Request $request,
        ExamService $examService
    ): JsonResponse {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
            ],

            'exam_id' => [
                'required',
                'exists:exams,id',
            ],
        ]);

        $student = Student::findOrFail(
            $validated['student_id']
        );

        $exam = Exam::with([
            'questions',
        ])->findOrFail(
            $validated['exam_id']
        );

        $attempt = $examService->startAttempt(
            $student,
            $exam
        );

        return response()->json([
            'message' => 'Attempt started successfully.',
            'data' => new AttemptResource($attempt),
        ], 201);
    }

    /**
     * Show an existing attempt.
     */
    public function show(
        Attempt $attempt
    ): AttemptResource {
        $attempt->load([
            'exam',
            'answers.question',
            'answers.option',
        ]);

        return new AttemptResource($attempt);
    }

    /**
     * Save an answer for a question.
     */
    public function answer(
        Request $request,
        Attempt $attempt,
        ExamService $examService
    ): JsonResponse {
        /*
         * Prevent modifying a completed attempt.
         */
        if ($attempt->isCompleted()) {
            return response()->json([
                'message' => 'This attempt has already been submitted.',
            ], 422);
        }

        /*
         * Prevent answering after the exam time has expired.
         */
        if ($attempt->hasExpired()) {
            $examService->submitAttempt($attempt);

            return response()->json([
                'message' => 'Exam time has expired.',
            ], 422);
        }

        $validated = $request->validate([
            'question_id' => [
                'required',
                'exists:questions,id',
            ],

            'option_id' => [
                'required',
                'exists:options,id',
            ],
        ]);

        /*
         * Make sure this question belongs
         * to the current attempt.
         */
        $answer = $attempt->answers()
            ->where(
                'question_id',
                $validated['question_id']
            )
            ->firstOrFail();

        /*
         * Make sure the selected option
         * belongs to the selected question.
         */
        $optionBelongsToQuestion = Option::query()
            ->where(
                'id',
                $validated['option_id']
            )
            ->where(
                'question_id',
                $validated['question_id']
            )
            ->exists();

        if (! $optionBelongsToQuestion) {
            return response()->json([
                'message' => 'The selected option does not belong to this question.',
            ], 422);
        }

        /*
         * Save the selected option.
         */
        $answer->update([
            'option_id' => $validated['option_id'],
        ]);

        return response()->json([
            'message' => 'Answer saved successfully.',
            'data' => [
                'question_id' => $answer->question_id,
                'option_id' => $answer->option_id,
            ],
        ]);
    }

    /**
     * Flag or unflag a question.
     */
    public function flag(
        Request $request,
        Attempt $attempt,
        ExamService $examService
    ): JsonResponse {
        /*
         * Prevent modifying a completed attempt.
         */
        if ($attempt->isCompleted()) {
            return response()->json([
                'message' => 'This attempt has already been submitted.',
            ], 422);
        }

        /*
         * Prevent flagging after the exam time has expired.
         */
        if ($attempt->hasExpired()) {
            $examService->submitAttempt($attempt);

            return response()->json([
                'message' => 'Exam time has expired.',
            ], 422);
        }

        $validated = $request->validate([
            'question_id' => [
                'required',
                'exists:questions,id',
            ],

            'flagged' => [
                'required',
                'boolean',
            ],
        ]);

        /*
         * Make sure this question belongs
         * to the current attempt.
         */
        $answer = $attempt->answers()
            ->where(
                'question_id',
                $validated['question_id']
            )
            ->firstOrFail();

        /*
         * Update flag status.
         */
        $answer->update([
            'is_flagged' => $validated['flagged'],
        ]);

        return response()->json([
            'message' => 'Question flag updated.',
            'data' => [
                'question_id' => $answer->question_id,
                'is_flagged' => $answer->is_flagged,
            ],
        ]);
    }

    /**
     * Submit the exam attempt.
     */
    public function submit(
        Attempt $attempt,
        ExamService $examService
    ): JsonResponse {
        /*
         * Prevent submitting the same attempt twice.
         */
        if ($attempt->isCompleted()) {
            return response()->json([
                'message' => 'This attempt has already been submitted.',
            ], 422);
        }

        $attempt = $examService->submitAttempt(
            $attempt
        );

        return response()->json([
            'message' => 'Exam submitted successfully.',
            'data' => [
                'attempt_id' => $attempt->id,
                'score' => $attempt->score,
                'status' => $attempt->status,
                'submitted_at' => $attempt->submitted_at,
            ],
        ]);
    }

    /**
     * Show the final exam result.
     */
    public function result(
        Attempt $attempt,
        ExamService $examService
    ): JsonResponse {
        /*
         * Result is only available after submission.
         */
        if (! $attempt->isCompleted()) {
            return response()->json([
                'message' => 'The exam has not been submitted yet.',
            ], 422);
        }

        $result = $examService->calculateResult(
            $attempt
        );

        return response()->json([
            'data' => new ResultResource($result),
        ]);
    }
}
