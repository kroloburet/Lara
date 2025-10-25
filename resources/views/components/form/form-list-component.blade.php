@props([
    'name' => '',
    'items' => [],
    'iconClass' => 'fa-solid fa-list',
    'valueRegex' => '/.+/',
    'valueType' => 'text',
    'namePlaceholder' => '',
    'valuePlaceholder' => '',
    'noAddedText' => '',
])

<!--- Form List Component --->
<div id="{{ $name }}" {{ $attributes->class(['formListComponent']) }}>
    <div class="UI_form-component controlComponent">
        <input class="formListComponent_name"
               placeholder="{{ $namePlaceholder }}"
               type="text">
        <input class="formListComponent_value"
               placeholder="{{ $valuePlaceholder }}"
               type="{{ $valueType }}"
               autocomplete="{{ $valueType }}">
        <i class="UI_form-component-control fa-solid fa-plus addItem"></i>
    </div>
    <div class="formListComponent_items">
        <div class="noAdded">{{ $noAddedText }}</div>
    </div>
    <textarea type="hidden" name="{{ $name }}">{!! !empty($items) ? json_encode($items) : null !!}</textarea>
</div>


<!--
########### Form List Component Script for {{ $name }}
-->

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new FormListComponent({
            componentId: '{{ $name }}',
            iconClass: '{{ $iconClass }}',
            valueRegex: {{ $valueRegex }}
        });
    });
</script>
