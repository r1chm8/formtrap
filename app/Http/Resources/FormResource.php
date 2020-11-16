<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'endpoint' => route('forms.show', $this->hashed_id),
            'redirect_url' => $this->redirect_url,
            'notification_email' => $this->notification_email,
            'version' => new FormVersionResource($this->version),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
