<div class="control">
    @php($isMultiple = $field->isMultiSelect())
    <div class="select w-full{{ $isMultiple ? ' multiple' : null }}{{ $errors->has($field->name) ? ' error' : null }}">
        <select
            id="{{ $field->name }}"
            name="{{ $field->name . ($isMultiple ? '[]' : null) }}"
            {{ $isMultiple ? 'multiple' : null }}
            {{ $field->required ? 'required' : null }}
        >
            @if(! $isMultiple)
                <option value="">Please select</option>
            @endif
            @foreach($field->options as $option)
                <option value="{{ $option->computedValue }}"{{
                    ($isMultiple && is_array(old($field->name)) && in_array($option->computedValue, old($field->name)))
                    || (! $isMultiple && old($field->name) == $option->computedValue)
                        ? ' selected'
                        : null
                }}>{{ $option->label }}</option>
            @endforeach
        </select>
    </div>
</div>
