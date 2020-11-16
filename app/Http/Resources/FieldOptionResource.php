<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'label' => $this->label,
            'value' => $this->value
        ];
    }
}
