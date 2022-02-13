<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this['id'],
            'initials' => $this['initials'],
            'title' => $this['title'],
            'surname' => $this['surname'],
            'forename' => $this['forename'],
            'classes' => $this->when(
                !empty($this['classes'] ?? []),
                new ClassesResource($this['classes'])
            ),
        ];
    }
}
