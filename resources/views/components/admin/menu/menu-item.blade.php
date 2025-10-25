@props(['item'])

<!--- Menu Item --->
<li id="itemElement_{{ $item->id }}"
    data-item-id="{{ $item->id }}"
    data-parent-id="{{ $item->parent_id ?? '' }}"
    data-title="{{ $item->title }}"
    data-url="{{ $item->url ?? '' }}"
    data-target="{{ $item->target }}"
    data-order="{{ $item->order }}">

    <div class="menuItem">
        <span class="title ellipsis-overflow">{!! $item->title !!}</span>
        <div class="icons-group">
            <a class="fa-solid fa-info-circle"
               data-hint="this"></a>
            <span class="UI_Hint">
                id: {{ $item->id }}<br>
                parent id: {!! $item->parent_id ?? '&mdash;' !!}<br>
                url: {!! $item->url ?? '&mdash;' !!}<br>
                target: {{ $item->target }}<br>
                locale: {{ $item->locale }}<br>
                created: {{ consumerDateTimeFormat($item->created_at, 'admin') }}<br>
                updated: {{ consumerDateTimeFormat($item->updated_at, 'admin') }}<br>
            </span>

            <a href="{{ $item->url ?? '' }}" target="_blank"
               class="fa-solid fa-arrow-up-right-from-square @if(!$item->url) UI_disabled @endif"
               title="{{ __('base.View') }}"></a>

            <i class="separator"></i>

            @can('permits', ['menu', 'u'])
                <a class="update fa-solid fa-pen-to-square" title="{{ __('base.Edit') }}"></a>
            @endcan

            @can('permits', ['menu', 'd'])
                <a class="toggle fa-solid {{ $item->deleted_at ? 'fa-eye-slash red-text' : 'fa-eye' }}"
                   title="{{ __('base.Toggle_public') }}"></a>
                <a class="delete fa-solid fa-trash-can" title="{{ __('base.Delete') }}"></a>
            @endcan
        </div>
    </div>

    @if ($item->children)
        <ul>
            @foreach ($item->children as $child)
                <x-admin.menu.menu-item :item="$child" />
            @endforeach
        </ul>
    @endif
</li>
