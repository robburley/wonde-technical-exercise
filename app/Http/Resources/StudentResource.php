<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'surname' => $this['surname'],
            'forename' => $this['forename'],
            'middle_names' => $this['middle_names'],
            'gender' => $this['gender'],
        ];
    }
}
