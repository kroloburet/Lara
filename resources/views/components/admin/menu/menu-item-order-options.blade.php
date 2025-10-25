@props([
    'parentItems',
    'updatableItemId' => null,
    'currentItem' => null,
    'lastItemId' => null,
])

<!--- Menu Item Order Options --->
<option value="first" @if($parentItems->isEmpty() || ($currentItem && $currentItem->order == 1)) selected @endif>
    {{ __('admin.menu.form_inner.parent_order.First') }}
</option>
@foreach($parentItems as $item)
    @continue($item->id == $updatableItemId)
    <option
        value="{{ $item->id }}"
        @if(($currentItem && $item->order < $currentItem->order && $parentItems->where('order', '<', $currentItem->order)->max('order') == $item->order) || (!$currentItem && $item->id == $lastItemId))
            selected
        @endif
    >
        {{ __('admin.menu.form_inner.parent_order.After') }} "{{ $item->title }}"
    </option>
@endforeach
