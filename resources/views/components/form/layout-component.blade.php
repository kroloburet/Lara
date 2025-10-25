@props(['materialType', 'material' => null])

@php
    $layoutSettings = materialLayoutSettings($materialType, $material);
    $classes = $layoutSettings['classes'] ?? [];
    $currentBgUrl = materialBgImageUrl($material);
@endphp

    <!--- Layout Component --->
<div id="layoutComponent">
    <div class="layoutComponent_prev">
        <!-- Desktop preview -->
        <div class="preview-desktop">
            <div>
                <span class="form_field-label">{!! __('settings.layout.desktop.label') !!}</span>
                <i class="base_hint-icon" data-hint="this"></i>
                <span class="UI_Hint">{!! __('settings.layout.desktop.hint') !!}</span>
                <select class="UI_Select layoutComponent_desktop">
                    @foreach(config('app.settings.layout.desktop', []) as $className)
                        <option value="{{ $className }}" @selected(in_array($className, $classes))>
                            {{ __("settings.layout.desktop.{$className}") }}
                        </option>
                    @endforeach
                </select>
            </div>

            <header></header>
            <main>
                <section></section>
                <aside></aside>
            </main>
        </div>

        <!-- Adaptive preview -->
        <div class="preview-mobile">
            <div>
                <span class="form_field-label">{!! __('settings.layout.mobile.label') !!}</span>
                <i class="base_hint-icon" data-hint="this"></i>
                <span class="UI_Hint">{!! __('settings.layout.mobile.hint') !!}</span>
                <select class="UI_Select layoutComponent_mobile">
                    @foreach(config('app.settings.layout.mobile', []) as $className)
                        <option value="{{ $className }}" @selected(in_array($className, $classes))>
                            {{ __("settings.layout.mobile.{$className}") }}
                        </option>
                    @endforeach
                </select>
            </div>

            <header></header>
            <main>
                <section></section>
                <aside></aside>
            </main>
        </div>
    </div>

    <span class="form_field-label">{!! __('settings.layout.desktop.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('settings.layout.mobile.hint') !!}</span>
    <input type="range" class="UI_InputRange layoutComponent_layoutMaxWidthField" min="800" max="2000" step="1"
           data-css-variable="--layout-max-width"
           value="{{ $layoutSettings['layoutMaxWidth'] }}">

    <span class="form_field-label">{!! __('settings.layout.desktop.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('settings.layout.mobile.hint') !!}</span>
    <input type="range" class="UI_InputRange layoutComponent_asideWidthField" min="20" max="50" step="1"
           data-css-variable="--layout-aside-width"
           value="{{ $layoutSettings['asideWidth'] }}">

    <label class="UI_checkbox">
        <input type="checkbox" class="layoutComponent_header" @checked($layoutSettings['header'] ?? false)>
        <span class="UI_checkmark"></span>
        {!! __('settings.layout.header.label') !!}
        <i class="base_hint-icon" data-hint="this"></i>
        <span class="UI_Hint">{!! __('settings.layout.header.hint') !!}</span>
    </label>

    <input name="layout" type="hidden" value="{{ json_encode($layoutSettings) }}">
</div>

@pushOnce('startPage')

    <!--
    ########### Layout Component
    -->

    <style>
        #layoutComponent .layoutComponent_prev {
            --layout-prev-gap: .5em;

            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--layout-gap-s);
            margin-bottom: var(--UI_form-gap-bottom);
        }

        .layoutComponent_prev :where(header, section, aside) {
            background-color: var(--tertiary-bg-color);
            border-radius: var(--UI_base-border-radius);
        }

        .layoutComponent_prev > * {
            display: flex;
            flex-direction: column;
            gap: var(--layout-prev-gap);
        }

        .layoutComponent_prev header {
            aspect-ratio: var(--layout-header-aspect-ratio);
            background: url("{{ $currentBgUrl }}") no-repeat;
            background-color: var(--tertiary-bg-color);
            background-size: cover;
        }

        .layoutComponent_prev main {
            aspect-ratio: 1;
            display: flex;
            flex: 1;
            gap: var(--layout-prev-gap);
        }

        .layoutComponent_prev aside {
            flex: 0 0 var(--layout-aside-width);
        }

        .layoutComponent_prev section {
            flex: 1;
        }

        /* Desktop preview orientation */
        .preview-desktop.right-aside main { flex-direction: row; }
        .preview-desktop.left-aside main { flex-direction: row-reverse; }
        .preview-desktop.top-aside main { flex-direction: column-reverse; }
        .preview-desktop.bottom-aside main { flex-direction: column; }
        .preview-desktop.not-aside main aside { display: none; }

        /* Mobile preview orientation */
        .preview-mobile.right-aside-adaptive main { flex-direction: row; }
        .preview-mobile.left-aside-adaptive main { flex-direction: row-reverse; }
        .preview-mobile.top-aside-adaptive main { flex-direction: column-reverse; }
        .preview-mobile.bottom-aside-adaptive main { flex-direction: column; }
        .preview-mobile.not-aside-adaptive main aside { display: none; }

        @media (max-width: 500px) {
            #layoutComponent .layoutComponent_prev {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Layout Component
    -->

    <script>
        {
            const component = document.getElementById(`layoutComponent`);
            const desktopField = component.querySelector(`.layoutComponent_desktop`);
            const mobileField = component.querySelector(`.layoutComponent_mobile`);
            const layoutMaxWidthField = component.querySelector(`.layoutComponent_layoutMaxWidthField`);
            const asideWidthField = component.querySelector(`.layoutComponent_asideWidthField`);
            const headerField = component.querySelector(`.layoutComponent_header`);
            const dataField = component.querySelector(`[name="layout"]`);
            const prevDesktop = component.querySelector(`.preview-desktop`);
            const prevMobile = component.querySelector(`.preview-mobile`);

            // All possible classes
            const allDesktopClasses = @json(config('app.settings.layout.desktop'));
            const allMobileClasses = @json(config('app.settings.layout.mobile'));

            /**
             * Updates layout settings data and applies it to preview elements.
             * Collects form input values, updates hidden data field, and synchronizes
             * header visibility and CSS classes for desktop and mobile previews.
             */
            const setData = () => {
                const data = {
                    classes: [desktopField.value, mobileField.value],
                    layoutMaxWidth: layoutMaxWidthField.value,
                    asideWidth: asideWidthField.value,
                    header: headerField.checked
                };

                // Update hidden input with serialized JSON data
                dataField.value = JSON.stringify(data);

                // Toggle header visibility for desktop and mobile previews
                [prevDesktop, prevMobile].forEach(prev => {
                    prev.querySelector(`header`).style.display = data.header ? `block` : `none`;
                });

                // Update desktop preview classes
                allDesktopClasses.forEach(cls => prevDesktop.classList.remove(cls));
                if (data.classes[0]) prevDesktop.classList.add(data.classes[0]);

                // Update mobile preview classes
                allMobileClasses.forEach(cls => prevMobile.classList.remove(cls));
                if (data.classes[1]) prevMobile.classList.add(data.classes[1]);

                // Update aside column width
                [prevDesktop, prevMobile].forEach(prev => {
                    prev.querySelector(`aside`).style.flexBasis = `${asideWidthField.value}%`;
                });
            };

            /**
             * Dynamically updates a CSS custom property based on range input value.
             * Applies the input value with the specified unit to the document root.
             *
             * @param {HTMLInputElement} rangeField - The input range slider
             * @param {string} unit - The CSS unit to append (e.g., '%', 'px')
             */
            const updateWidth = (rangeField, unit) => {
                const cssVariable = rangeField.dataset.cssVariable;
                document.documentElement.style.setProperty(cssVariable, `${rangeField.value}${unit}`);
            };

            // Listeners of component fields
            desktopField.addEventListener(`change`, setData);
            mobileField.addEventListener(`change`, setData);
            layoutMaxWidthField.addEventListener(`change`, setData);
            asideWidthField.addEventListener(`change`, setData);
            layoutMaxWidthField.addEventListener(`input`, () => updateWidth(layoutMaxWidthField, `px`));
            asideWidthField.addEventListener(`input`, () => updateWidth(asideWidthField, `%`));
            headerField.addEventListener(`change`, setData);

            // Autorun
            setData();

            // Global call for a parent component
            component.layoutComponentUpdate = setData;
        }
    </script>
@endPushOnce
