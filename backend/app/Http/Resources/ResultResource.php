<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'attempt_id' => $this['attempt_id'],
            'score' => $this['score'],
            'percentage' => $this['percentage'],
            'total' => $this['total'],
            'correct' => $this['correct'],
            'wrong' => $this['wrong'],
            'unanswered' => $this['unanswered'],
        ];
    }
}
