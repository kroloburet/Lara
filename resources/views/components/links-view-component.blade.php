@props(['links' => []])

@php
    if (empty($links)) return;
@endphp

<!--- Links View Component --->
<div {{ $attributes->class(['linksViewComponent']) }}>
    @foreach($links as $link)
        <span>
            <i class="fa-solid fa-link"></i>&nbsp;
            <a href="{{ $link['value'] }}" target="_blank">{!! $link['name'] !!}</a>
        </span>
    @endforeach

    <style>
        .linksViewComponent {
            display: flex;
            flex-direction: column;
            gap: .3em;
        }
    </style>
</div>
