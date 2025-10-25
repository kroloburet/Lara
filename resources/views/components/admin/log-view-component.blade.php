@props(['log' => []])

<!--- Log View Component --->
<div {{ $attributes->class(['logViewComponent']) }}>
    @forelse($log as $item)
        <div>
            @if(!empty($item['consumerName']))
                {{ $item['consumerName'] }}&nbsp;
            @endif
            {{ consumerDateTimeFormat($item['timestamp']) }}&nbsp;&rarr;&nbsp;
            {{ $item['event'] }}
        </div>
    @empty
        {!! __('component.log_view.empty') !!}
    @endforelse
</div>

@pushonce('startPage')

    <!--
    ########### Log View Component
    -->

    <style>
        .logViewComponent {
            display: flex;
            flex-direction: column;
            gap: var(--layout-gap-2xs);
        }
    </style>
@endpushonce
