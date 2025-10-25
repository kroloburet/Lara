@props([
    'model',
    'verbal' => false,
    'created_at' => true,
    'views' => true,
    'likes' => true,
    'share' => true,
    'complain' => true,
    'appeal' => true,
])

@php
    $resourcesConf = array_merge(
        config('app.consumers.types'),
        config('app.materials.types'),
    );
    $resourceUrlSegment = $resourcesConf[$model->type]['urlSegment'] ?? '';
    $resourceUrl = url("{$resourceUrlSegment}/{$model->alias}");
@endphp

<!--- Content Tools Component --->
<div {{ $attributes->class(['contentToolsComponent']) }}>
    @if($created_at && !empty($model->created_at))
        <time>{{ consumerDateFormat($model->created_at) }}</time>
    @endif

    @if($views && !empty($model->statistic))
        <span class="views">
            <i class="fa-solid fa-eye"></i>
            {{ $model->statistic['views'] }}
        </span>
    @endif

    @if($likes)
        <x-toggle-statistic-key-component key="likes" :$model :$verbal />
    @endif

    @if($share)
        <a data-share="{{ $resourceUrl }}">
            <i class="fa-solid fa-share-nodes"></i>
            {{ $verbal ? __('base.verbal.share') : '' }}
        </a>
    @endif

    @if($complain)
        <a data-complain="{{ $resourceUrl }}">
            <i class="fa-solid fa-bolt-lightning"></i>
            {{ $verbal ? __('base.verbal.complain') : '' }}
        </a>
    @endif

    @if($appeal)
        <a class="appeal">
            <i class="fa-solid fa-bullhorn"></i>
            {{ $verbal ? __('base.verbal.appeal') : '' }}
        </a>
    @endif
</div>
