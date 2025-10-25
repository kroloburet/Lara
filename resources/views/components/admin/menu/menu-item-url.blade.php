@props(['url' => '', 'locale' => '', 'required' => false])

@php
    $uniqId = uniqid('urlComponent_');
@endphp

<!--- Url Component --->
<div id="{{ $uniqId }}" {{ $attributes->class(['UI_form-component']) }}>
    <input name="menu[url]" class="UI_input" type="text"
           placeholder="{{ __('admin.menu.form_inner.url.placeholder') }}" value="{!! $url !!}" @required($required)>

    <i class="fa-solid fa-photo-film UI_form-component-control"
       data-file-selector="#{{ $uniqId }} [name='menu[url]']"></i>

    <i class="fa-solid fa-file-lines UI_form-component-control"
       data-material-selector="{{ json_encode(['locale' => $locale, 'selector' => "#{$uniqId} [name='menu[url]']"]) }}"></i>

    <select name="menu[target]" class="UI_Select UI_inline-form" data-select-placeholder="">
        <option value="_self">{{ __('admin.menu.form_inner.url.self') }}</option>
        <option value="_blank">{{ __('admin.menu.form_inner.url.blank') }}</option>
    </select>
</div>
