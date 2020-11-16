<div class="control -my-1">
    @foreach($field->options as $option)
        <div class="py-1">
            <input
                id="option_{{ $option->id }}"
                type="radio"
                name="{{ $field->name }}"
                class="checkbox"
                value="{{ $option->computedValue }}"
                {{ $field->required ? 'required' : null }}
            >
            <label for="option_{{ $option->id }}">
                {{ $option->label }}
            </label>
        </div>
    @endforeach
</div>
