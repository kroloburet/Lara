@props(['consumer'])

@php
    $onlineClass = $consumer->isActive() ? 'online' : null;
    $lastActivityAt = consumerDateTimeFormat($consumer->lastActivity(), null, true) ?? __('base.Was_not_activity');
@endphp

<!--- Consumer Activity Indicator Component --->
<span class="consumerActivityIndicatorComponent {{ $onlineClass }}"
      data-consumer-type="{{ $consumer->type }}"
      data-consumer-id="{{ $consumer->id }}">
    <i class="indicator" data-hint="this">online</i>
    <span class="UI_Hint">
        {!! __('base.Last_activity') !!}
        <span class="last-active-at">{!! $lastActivityAt !!}</span>
    </span>
</span>
