<?php

namespace App;

use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model implements Sortable
{
    use SoftDeletes, SortableTrait;

    protected $casts = [
        'meta' => 'json',
        'required' => 'bool'
    ];

    protected $sortable = [
        'order_column_name' => 'order'
    ];

    public function isOfType($type)
    {
        if ($type instanceof FieldType) {
            return $type = $type->id;
        }

        return $this->type_id == $type;
    }

    public function typeRequiresOptions()
    {
        return FieldType::requiresOptions($this->type_id);
    }

    public function hasOptions()
    {
        return $this->options->isNotEmpty();
    }

    public function setMeta(array $data)
    {
        $meta = null;

        if ($this->isOfType(FieldType::SELECT)) {
            $meta = ['multiple' => $data['multiple'] ?? false];
        }

        $this->meta = $meta;
    }

    public function addOptions(array $options)
    {
        $records = [];

        foreach ($options as $option) {
            $records[] = [
                'field_id' => $this->id,
                'label' => $option['label'],
                'value' => $option['value'] ?? null
            ];
        }

        $this->options()->insert($records);
    }

    public function acceptsMultipleValues()
    {
        return (
            $this->isOfType(FieldType::CHECKBOXES)
            || $this->isMultiSelect()
        );
    }

    public function isMultiSelect()
    {
        return (
            $this->isOfType(FieldType::SELECT)
            && $this->meta['multiple'] ?? false
        );
    }

    public function formVersion()
    {
        return $this->belongsTo(FormVersion::class, 'form_version_id');
    }

    public function type()
    {
        return $this->belongsTo(FieldType::class, 'type_id');
    }

    public function options()
    {
        return $this->hasMany(FieldOption::class, 'field_id');
    }
}
