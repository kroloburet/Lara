@props(['material'])

<!--- Static Material List Item --->
@if(! empty($material))
    <div class="Paginator_item">
        <div class="Paginator_item-prev"
             style="background-image:url('{!! materialBgImageUrl($material) !!}')"></div>

        <div class="Paginator_item-attr">
            {{ $material->content()->title }}
        </div>

        <div class="Paginator_item-control">
            <a class="fa-solid fa-info-circle"
               data-hint="this"></a>
            <span class="UI_Hint">
            type: {{ $material->type }}<br>
            id: {{ $material->id }}<br>
        </span>

            <a class="fa-solid fa-share-nodes"
               data-share="{{ routeToMaterial($material, true) }}"
               title="{{ __("base.Share") }}"></a>

            <a href="{{ routeToMaterial($material) }}" target="_blank"
               class="fa-solid fa-arrow-up-right-from-square"
               title="{{ __("base.View") }}"></a>

            <span class="Paginator_item-control-separator"></span>

            @can('permits', ['material', 'u'])
                <a href="{{ route('admin.update.static-material',
                    [
                        'type' => $material->type,
                        'content_locale' => $material->content()->locale]
                ) }}"
                   class="fa-solid fa-pen-to-square"
                   title="{{ __("base.Edit") }}"></a>
            @endcan
        </div>

        <div class="paginatorItemControlToggle"></div>
    </div>
@else
    <div class="Paginator_no-result">
        {!! __('base.no_results') !!}
    </div>
@endif
