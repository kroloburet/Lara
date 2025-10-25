@props(['menu'])

<!--- Menu Tree Inner --->
@forelse ($menu as $item)
    <x-admin.menu.menu-item :item="$item" />
@empty
    {!! __('base.no_results') !!}
@endforelse
