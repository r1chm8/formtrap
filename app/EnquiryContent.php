<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnquiryContent extends Model
{
    public $timestamps = false;

    protected $casts = [
        'value' => 'json'
    ];

    protected $fillable = [
        'value'
    ];

    public function getKeyAttribute()
    {
        return $this->field->label;
    }

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class, 'enquiry_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id')->withTrashed();
    }
}
