@php
    $uniqId = uniqid('setLayoutComponent_');
    $materialTypes = config('app.materials.types', []);
    $defaultSettings = collect(appSettings('layout.default'))
        ->only( // Only not static materials
            array_keys(
                collect($materialTypes)->where('static', false)->all()
            )
        )->toArray();
@endphp

<!--- Set Layout Component --->
<h2>{!! __('settings.layout.title') !!}</h2>
<p>{!! __('settings.layout.desc') !!}</p>

<div class="dashboard-panel column">
    <div id="{{ $uniqId }}" class="dashboard-panel-item">
        <span class="form_field-label">{!! __('settings.layout.material_type.label') !!}</span>
        <i class="base_hint-icon" data-hint="this"></i>
        <span class="UI_Hint">{!! __('settings.layout.material_type.hint') !!}</span>

        <select class="UI_Select setLayoutComponent_materialType" data-select-placeholder="">
            @foreach(array_keys($defaultSettings) as $materialType)
                <option value="{{ $materialType }}">
                    {{ __("material.{$materialType}.they.upper") }}
                </option>
            @endforeach
        </select>

        <x-form.layout-component :material-type="array_key_first($defaultSettings)" />

        <div class="UI_fieldset UI_align-r">
            <button class="UI_button setLayoutComponent_apply">{{ __('base.Apply') }}</button>
        </div>
    </div>
</div>

@pushOnce('endPage')
    <script>
        {
            const component = document.getElementById(`{{ $uniqId }}`);
            const materialSelect = component.querySelector(`.setLayoutComponent_materialType`);
            const layoutComponent = component.querySelector(`#layoutComponent`);
            const applyButton = component.querySelector(`.setLayoutComponent_apply`);
            const dataField = layoutComponent.querySelector(`[name="layout"]`);
            let defaultSettings = @json($defaultSettings);
            let layoutState = dataField.value;

            /**
             * Recursively normalize object or array (sort object keys)
             * @param {any} obj - Target object or array to normalize
             * @return {any} - Normalized object or array
             */
            function normalize(obj) {
                if (Array.isArray(obj)) {
                    return obj.map(normalize);
                } else if (obj && typeof obj === `object` && !(obj instanceof Date)) {
                    return Object.keys(obj).sort().reduce((acc, key) => {
                        acc[key] = normalize(obj[key]);
                        return acc;
                    }, {});
                }
                return obj;
            }

            /**
             * Compare two JSON strings regardless of key order
             * @return {boolean} - Returns true if layout state differs
             */
            const layoutStateChanged = () => {
                try {
                    const oldVal = normalize(JSON.parse(layoutState));
                    const newVal = normalize(JSON.parse(dataField.value));
                    return JSON.stringify(oldVal) !== JSON.stringify(newVal);
                } catch (e) {
                    console.error(`JSON parse error in layoutStateChanged:`, e);
                    return true;
                }
            };

            /**
             * Update stored layout state
             */
            const updateLayoutState = () => layoutState = dataField.value;

            /**
             * Reset form field to previous layout state
             */
            const resetLayoutState = () => dataField.value = layoutState;

            /**
             * Update layout component form fields by selected material type
             * @param {string} type - Selected material type key
             */
            const updateLayoutComponent = (type) => {
                const settings = defaultSettings[type];

                const desktopField = layoutComponent.querySelector(`.layoutComponent_desktop select`);
                const mobileField = layoutComponent.querySelector(`.layoutComponent_mobile select`);
                const layoutMaxWidthField = component.querySelector(`.layoutComponent_layoutMaxWidthField`);
                const asideWidthField = component.querySelector(`.layoutComponent_asideWidthField`);
                const headerField = layoutComponent.querySelector(`.layoutComponent_header`);

                if (desktopField) desktopField.value = settings.classes?.[0] ?? ``;
                if (mobileField) mobileField.value = settings.classes?.[1] ?? ``;
                if (layoutMaxWidthField) layoutMaxWidthField.value = settings.layoutMaxWidth ?? 1300;
                if (asideWidthField) asideWidthField.value = settings.asideWidth ?? 30;
                if (headerField) headerField.checked = settings.header ?? true;

                if (typeof layoutComponent.layoutComponentUpdate === `function`) {
                    layoutComponent.layoutComponentUpdate();
                }

                updateLayoutState();
            }

            /**
             * Save layout settings via XHR request
             */
            const saveSettings = async () => {
                try {
                    const value = JSON.parse(dataField.value) ?? {};
                    const newSettings = await setAppSettings(`layout.default.${materialSelect.value}`, value, component);
                    defaultSettings = newSettings?.layout?.default ?? defaultSettings;
                    updateLayoutState();
                } catch (e) {
                    console.error(`Failed to save layout settings:`, e);
                }
            }

            /**
             * Listen when material type dropdown is opened
             * Ask user to save unsaved changes if layout has been modified
             */
            materialSelect.addEventListener(`click`, () => {
                if (layoutStateChanged()) {
                    UI.Confirm(
                        `{!! __('settings.layout.save_changes_confirm') !!}`,
                        async () => await saveSettings(),
                        () => resetLayoutState()
                    );
                }
            });

            /**
             * Listen when material type is changed
             */
            materialSelect.addEventListener(`change`, e => {
                updateLayoutComponent(e.target.value);
                UI.Select({selector: `#layoutComponent select`});
                UI.InputRange({selector: `#layoutComponent .UI_InputRange`});
            });

            /**
             * Listen Apply button click to save settings
             */
            applyButton.addEventListener(`click`, async () => {
                await saveSettings();
            });

            /**
             * Autorun: Initialize component with current material type
             */
            updateLayoutComponent(materialSelect.value);
        }
    </script>
@endPushOnce
