@component('mail::message')
# Enquiry Received

@foreach($enquiry->contents as $content)
<strong>{{  $content->field->label }}</strong><br>
@include('emails.partials.enquiry-value', [
    'value' => $content->value
])
{{-- Spacer --}}
@endforeach

@component('mail::button', [
    'url' => url("enquiries/{$enquiry->id}")
])
View in {{ config('app.name') }}
@endcomponent
@endcomponent
