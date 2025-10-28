@props(['key', 'model', 'verbal' => false])

@php
    if (empty($model) || empty($model->statistic)) return;

    $statisticService = statistic($model);
@endphp

<!--- Toggle Statistic Key Component --->
<span {{ $attributes->class(['toggleStatisticKeyComponent', 'verbal' => $verbal]) }}
      data-key="{{ $key }}"
      data-model-type="{{ $model->type }}"
      data-model-id="{{ $model->id }}">

    <a class="toggleStatisticKeyComponent_trigger">
        <span class="toggleStatisticKeyComponent_trigger-inner">
            @if($statisticService->hasKeyInGuestStatistic($key))
                {!! __("component.toggle_statistic_key.{$key}.remove") !!}
            @else
                {!! __("component.toggle_statistic_key.{$key}.add") !!}
            @endif
        </span>

        {{ $verbal ? __('base.verbal.like') : '' }}
    </a>

    @if($key === 'likes')
        <span class="toggleStatisticKeyComponent_count">
            {{ $model->statistic['likes'] }}
        </span>
    @endif
</span>
