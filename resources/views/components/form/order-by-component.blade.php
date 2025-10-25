@props(['additionalOptions' => null])

<!--- Order By Component --->
<select id="orderByComponent"  {{ $attributes->class(['UI_Select']) }}
    name="order_by" data-select-placeholder="">
    <option value="id__desc">{{ __('form.order_by.newest') }}</option>
    <option value="id__asc">{{ __('form.order_by.oldest') }}</option>
    {{ $additionalOptions }}
</select>
