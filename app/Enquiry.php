<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $casts = [
        'read' => 'bool'
    ];

    protected $fillable = [
        'form_id'
    ];

    public function scopeFilter(Builder $query, Request $request)
    {
        if ($request->filled('formId')) {
            $query->where('form_id', $request->input('formId'));
        }
    }

    public function scopeForUser(Builder $query, User $user)
    {
        $query->whereHas('form', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function contents()
    {
        return $this->hasMany(EnquiryContent::class, 'enquiry_id');
    }
}
