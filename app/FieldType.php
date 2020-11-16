<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    const TEXT = 1;
    const TEXTAREA = 2;
    const RADIO = 3;
    const CHECKBOXES = 4;
    const SELECT = 5;

    protected $fillable = [
        'name'
    ];

    public static function requiresOptions($type)
    {
        if ($type instanceof static) {
            $type = $type->id;
        }

        return in_array($type, [
            static::RADIO,
            static::CHECKBOXES,
            static::SELECT
        ]);
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'type_id');
    }
}
