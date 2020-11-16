@if(is_array($value))
{{ implode($value, ', ') }}
@else
{{ $value }}
@endif
