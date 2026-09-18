<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExamController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $exams = Exam::query()
            ->withCount('questions')
            ->get();

        return ExamResource::collection($exams);
    }

    public function show(Exam $exam): ExamResource
    {
        $exam->load([
            'questions.options'
        ]);

        return new ExamResource($exam);
    }
}
