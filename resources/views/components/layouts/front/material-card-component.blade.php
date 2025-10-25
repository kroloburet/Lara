@props(['item'])

<!--- Material Card Component --->
<div class="materialCard Paginator_item {{ markIfBlocked($item) }}">
    <a href="{{ routeToMaterial($item) }}"
       class="materialCard_hero"
       style="background-image:url('{!! materialBgImageUrl($item->layout['header'] ? $item : null) !!}')"></a>
    <div class="materialCard_desc">

        <x-content-tools-component :model="$item" class="UI_align-r" />

        <h3>
            <a href="{{ routeToMaterial($item) }}">{{ $item->content()->title }}</a>
        </h3>

        {{ stripTagsAndLimit($item->content()->description, 200) }}
    </div>
    <div class="materialCard_footer">
        <a href="{{ routeToMaterial($item) }}" class="UI_arrow-right">{!! __('base.Read') !!}</a>
    </div>
</div>
