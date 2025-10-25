@props(['items', 'level' => 0, 'updatableItemId' => null])

<!--- Menu Item Parent Options --->
@if($level === 0)
    <option value="">{{ __('admin.menu.form_inner.parent_order.No_parent') }}</option>
@endif
@foreach ($items as $item)
    @continue($item->id == $updatableItemId)
    <option value="{{ $item->id }}">{{ str_repeat('-- ', $level) }}{{ $item->title }}</option>
    @if ($item->children)
        <x-admin.menu.menu-item-parent-options
            :items="$item->children"
            :level="$level + 1"
            :$updatableItemId />
    @endif
@endforeach
