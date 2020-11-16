<div class="control">
    <textarea
        id="{{ $field->name }}"
        name="{{ $field->name }}"
        class="textarea{{ $errors->has($field->name) ? ' error' : null }}"
        {{ $field->required ? 'required' : null }}
    >{{ is_scalar($value = old($field->name)) ? $value : null }}</textarea>
</div>
