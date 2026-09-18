<?php

namespace App\Http\Resources;

use App\Http\Resources\AnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'exam_id' => $this->exam_id,

            'status' => $this->status,

            'started_at' => $this->started_at,
            'submitted_at' => $this->submitted_at,

            'score' => $this->when(
                $this->isCompleted(),
                $this->score
            ),

            'answers' => AnswerResource::collection(
                $this->whenLoaded('answers')
            ),
        ];
    }
}
