<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'duration_minutes' => $this->duration_minutes,

            'questions_count' => $this->when(
                isset($this->questions_count),
                $this->questions_count
            ),

            'questions' => QuestionResource::collection(
                $this->whenLoaded('questions')
            ),
        ];
    }
}
