@props(['social_networks' => []])

@php
    if (empty($social_networks)) return;
@endphp

<!--- Social Networks Component --->
<div {{ $attributes->class(['socialNetworksComponent', 'icons-group', 'UI_adaptive']) }}>
    @foreach($social_networks as $network)
        <a href="{{ $network['url'] }}" target="_blank">
            {!! $network['icon'] !!}
        </a>
    @endforeach
</div>
