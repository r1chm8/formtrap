@component('mail::message')
# Welcome aboard!

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas
mi nisl, blandit ac malesuada at, semper non arcu. Morbi iaculis
sagittis risus, et facilisis nunc consequat ut.

@component('mail::button', ['url' => ''])
Activate account
@endcomponent

<br>Thanks,<br>
{{ config('app.name') }}
@endcomponent
