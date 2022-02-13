<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
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
            'name' => $this['name'],
            'code' => $this['code'],
            'description' => $this['description'],
            'subject' => $this['subject'],
            'students' => $this->when(
                !empty($this['students']['data'] ?? []),
                new StudentsResource($this['students']['data'])
            ),
        ];
    }
}
