<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model
{
    public $timestamps = false;

    public function getComputedValueAttribute()
    {
        return $this->value ?: $this->label;
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
