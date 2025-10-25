@props(['phones' => []])

@php
    if (empty($phones)) return;
@endphp

<!--- Phones View Component --->
<div {{ $attributes->class(['phonesViewComponent']) }}>
    @foreach($phones as $phone)
        <span>
            <i class="fa-solid fa-phone"></i>&nbsp;
            {!! $phone['name'] !!}&nbsp;
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone['value'] ?? '') }}" target="_blank">{{ $phone['value'] }}</a>
        </span>
    @endforeach

    <style>
        .phonesViewComponent {
            display: flex;
            flex-direction: column;
            gap: .3em;
        }
    </style>
</div>
