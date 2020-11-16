<?php

namespace App\Http\Resources;

use App\FieldType;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => new FieldTypeResource($this->type),
            'name' => $this->name,
            'label' => $this->label,
            'options' => $this->when(
                $this->typeRequiresOptions(), function () {
                    return FieldOptionResource::collection($this->options);
                }
            ),
            'multiple' => $this->when(
                $this->isOfType(FieldType::SELECT), function () {
                    return $this->meta['multiple'] ?? false;
                }
            ),
            'required' => $this->required,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
