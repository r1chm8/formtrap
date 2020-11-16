<?php

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormVersion extends Model
{
    use SoftDeletes;

    protected $dates = [
        'published_at'
    ];

    public function rules()
    {
        $rules = [];

        foreach ($this->fields as $field) {
            $key = $field->name;

            $rules[$key][] = $field->required
                ? 'required'
                : 'nullable';

            if (! $field->typeRequiresOptions()) {
                $rules[$key][] = 'string';
                continue;
            }

            if ($field->acceptsMultipleValues()) {
                $rules[$key][] = 'array';
                $key .= '.*'; // Validate the array values
            }

            if ($field->hasOptions()) {
                $rules[$key][] = Rule::in(
                    $field->options->map(function ($option) {
                        return $option->computedValue;
                    })
                );
            }
        }

        return $rules;
    }

    public function saveEnquiry(array $data)
    {
        $enquiry = new Enquiry();
        $enquiry->form_id = $this->form_id;
        $enquiry->save();

        foreach ($this->fields as $field) {
            $content = new EnquiryContent();
            $content->field_id = $field->id;
            $content->value = $data[$field->name] ?? null;
            $enquiry->contents()->save($content);
        }

        return $enquiry;
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'form_version_id');
    }
}
