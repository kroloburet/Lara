@props(['materials'])

<!--- Material Selector List --->
@forelse($materials as $material)
    <div class="Paginator_item">
        <div class="Paginator_item-prev"
             style="background-image:url('{!! materialBgImageUrl($material) !!}')"></div>

        <div class="Paginator_item-attr">
            {{ $material->content()->title }}
        </div>

        <div class="Paginator_item-attr" title="Parent category">
            {!!
                '<i class="fa-solid fa-folder-tree"></i>&nbsp;' .
                ( $material->category?->content()->title ?? '&mdash;' )
            !!}
        </div>

        <div class="Paginator_item-control">
            <a class="fa-solid fa-info-circle"
               data-hint="this"></a>
            <span class="UI_Hint">
                type: {{ $material->type }}<br>
                id: {{ $material->id }}<br>
                alias: {{ $material->alias }}<br>
                category: {!! $material->category?->content()->title ?? '&mdash;' !!}<br>
                created: {{ consumerDateTimeFormat($material->created_at, 'admin') }}<br>
                updated: {{ consumerDateTimeFormat($material->updated_at, 'admin') }}<br>
            </span>

            <a class="fa-solid fa-share-nodes"
               data-share="{{ routeToMaterial($material, true) }}"
               title="{{ __("base.Share") }}"></a>

            <a href="{{ routeToMaterial($material) }}" target="_blank"
               class="fa-solid fa-arrow-up-right-from-square"
               title="{{ __("base.View") }}"></a>

            <span class="Paginator_item-control-separator"></span>

            <a class="Paginator_item-control-btn"
               data-props="{{ json_encode([
                'id' => $material->id,
                'alias' => $material->alias,
                'title' => $material->content()->title,
                'locale' => $material->content()->locale,
                'type' => $material->type,
                'relativeUrl' => routeToMaterial($material),
            ]) }}">Select</a>
        </div>

        <div class="paginatorItemControlToggle"></div>
    </div>
@empty
    <div class="Paginator_no-result">
        {!! __('base.no_results') !!}
    </div>
@endforelse
