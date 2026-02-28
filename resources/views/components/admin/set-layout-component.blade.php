@php
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
    <div id="setLayoutComponent" class="dashboard-panel-item">
        <span class="form_field-label">{!! __('settings.layout.material_type.label') !!}</span>
        <i class="base_hint-icon" data-hint="this"></i>
        <span class="UI_Hint">{!! __('settings.layout.material_type.hint') !!}</span>

        <select id="setLayoutComponent_materialType" class="UI_Select" data-select-placeholder="">
            @foreach(array_keys($defaultSettings) as $materialType)
                <option value="{{ $materialType }}">
                    {{ __("material.{$materialType}.they.upper") }}
                </option>
            @endforeach
        </select>

        <x-form.layout-component :material-type="array_key_first($defaultSettings)" />

        <span class="form_field-label">{!! __('settings.layout.save_opt.label') !!}</span>
        <i class="base_hint-icon" data-hint="this"></i>
        <span class="UI_Hint">{!! __('settings.layout.save_opt.hint') !!}</span>
        <div class="UI_fieldset">
            <select id="setLayoutComponent_saveOptSelect" class="UI_Select" data-select-placeholder="">
                <option value="only_new">{!! __('settings.layout.save_opt.only_new') !!}</option>
                <option value="existent_and_new">{!! __('settings.layout.save_opt.existent_and_new') !!}</option>
            </select>
            <button class="UI_button setLayoutComponent_apply">{{ __('base.Apply') }}</button>
        </div>
    </div>
</div>

@pushOnce('endPage')
    <script>
        {
            const component = document.getElementById(`setLayoutComponent`);
            const layoutComponent = component.querySelector(`#layoutComponent`);
            const materialTypeSelect = document.getElementById(`setLayoutComponent_materialType`);
            const saveOptSelect = document.getElementById(`setLayoutComponent_saveOptSelect`);
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

                const desktopField = layoutComponent.querySelector(`#layoutComponent_desktop`);
                const mobileField = layoutComponent.querySelector(`#layoutComponent_mobile`);
                const layoutMaxWidthField = layoutComponent.querySelector(`#layoutComponent_layoutMaxWidthField`);
                const asideWidthField = layoutComponent.querySelector(`#layoutComponent_asideWidthField`);
                const headerField = layoutComponent.querySelector(`.layoutComponent_header`);

                if (desktopField) desktopField.value = settings.classes?.[0] ?? ``;
                if (mobileField) mobileField.value = settings.classes?.[1] ?? ``;
                if (layoutMaxWidthField) layoutMaxWidthField.value = settings.layoutMaxWidth ?? 1300;
                if (asideWidthField) asideWidthField.value = settings.asideWidth ?? 30;
                if (headerField) headerField.checked = settings.header ?? true;

                UI.Select({selector: `#layoutComponent select`});
                UI.InputRange({selector: `#layoutComponent [type="range"]`});

                if (typeof layoutComponent.layoutComponentUpdate === `function`) {
                    layoutComponent.layoutComponentUpdate();
                }

                updateLayoutState();
            }

            /**
             * Save layout settings via XHR request
             */
            const saveSettings = async () => {
                const opt = saveOptSelect.value;
                const save = async () => {
                    try {
                        component.classList.add(UI.css.disabled);

                        let newSettings = null;
                        const data = await fetchActionData(
                            `{{ route('xhr.admin.set.app.layout') }}`,
                            JSON.stringify({
                                material_type: materialTypeSelect.value,
                                settings: JSON.parse(dataField.value) ?? {},
                                opt,
                            })
                        )

                        if (data.ok && data.message) UI.OkNotice(data.message);
                        if (data.ok && data.newSettings) newSettings = data.newSettings;

                        defaultSettings = newSettings?.layout?.default ?? defaultSettings;
                        updateLayoutState();
                    } catch (e) {
                        console.error(`Failed to save layout settings:`, e);
                    } finally {
                        component.classList.remove(UI.css.disabled);
                    }
                }

                try {
                    if (opt === `existent_and_new`) {
                        UI.Confirm(
                            `{!! __('settings.layout.save_existent_materials_confirm') !!}`,
                            async () => await save()
                        );
                    } else {
                        await save()
                    }
                } catch (e) {
                    console.error(`Failed to save layout settings:`, e);
                }
            }

            /**
             * Listen when material type dropdown is opened
             * Ask user to save unsaved changes if layout has been modified
             */
            materialTypeSelect.addEventListener(`click`, () => {
                if (layoutStateChanged()) {
                    UI.Confirm(
                        `{!! __('settings.layout.save_changes_confirm') !!}`,
                        async () => await saveSettings(),
                        () => {
                            resetLayoutState();
                            updateLayoutComponent(materialTypeSelect.value)
                        }
                    );
                }
            });

            /**
             * Listen when material type is changed
             */
            materialTypeSelect.addEventListener(`change`, e => {
                updateLayoutComponent(e.target.value);
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
            updateLayoutComponent(materialTypeSelect.value);
        }
    </script>
@endPushOnce
