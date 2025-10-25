@props(['emails' => []])

@php
    if (empty($emails)) return;
@endphp

<!--- Emails View Component --->
<div {{ $attributes->class(['emailsViewComponent']) }}>
    @foreach($emails as $email)
        <span>
            <i class="fa-solid fa-envelope"></i>&nbsp;
            {!! $email['name'] !!}&nbsp;
            <a href="mailto:{{ $email['value'] }}" target="_blank">{{ $email['value'] }}</a>
        </span>
    @endforeach

    <style>
        .emailsViewComponent {
            display: flex;
            flex-direction: column;
            gap: .3em;
        }
    </style>
</div>
