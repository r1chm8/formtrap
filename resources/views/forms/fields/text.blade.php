<div class="control">
    <input
        id="{{ $field->name }}"
        type="text"
        name="{{ $field->name }}"
        class="input{{ $errors->has($field->name) ? ' error' : null }}"
        value="{{ is_scalar($value = old($field->name)) ? $value : null }}"
        {{ $field->required ? 'required' : null }}
    >
</div>
