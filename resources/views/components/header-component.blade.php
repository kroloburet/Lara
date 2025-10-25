@props(['materialType', 'material' => null])

@php
    $layoutSettings = materialLayoutSettings($materialType, $material);
@endphp

@if($layoutSettings['header'])
    <!--- Header Component --->
    <header id="base-header" {{ $attributes->class(markIfBlocked($material)) }}>
        {!! $slot !!}
    </header>
@endif
