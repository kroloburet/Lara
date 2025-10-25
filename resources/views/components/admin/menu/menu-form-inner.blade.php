@props(['menu', 'locale' => app()->getLocale()])

@php
    $uniqId = uniqid('menuFormInner_');
@endphp

<!--- Menu Form Inner --->
<div id="{{ $uniqId }}">
    <input name="menu[item_id]" type="hidden" value="">

    <span class="form_field-label">{!! __('admin.menu.form_inner.title.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('admin.menu.form_inner.title.hint') !!}</span>
    <input name="menu[title]" type="text" class="UI_input"
           data-lim="this, 50"
           placeholder="{{ __('admin.menu.form_inner.title.placeholder') }}" required>

    <span class="form_field-label">{!! __('admin.menu.form_inner.url.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('admin.menu.form_inner.url.hint') !!}</span>
    <x-admin.menu.menu-item-url :$locale />

    <span class="form_field-label">{!! __('admin.menu.form_inner.parent_order.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('admin.menu.form_inner.parent_order.hint') !!}</span>
    <div class="UI_form-component UI_adaptive">
        <select name="menu[parent_id]"  class="UI_Select" data-select-placeholder="">
            <x-admin.menu.menu-item-parent-options :items="$menu"/>
        </select>

        <select name="menu[order_position]" class="UI_Select" data-select-placeholder=""></select>
    </div>
</div>

<script>
    {
        const component = document.querySelector(`#{{ $uniqId }}`);
        const itemIdField = component.querySelector(`[name="menu[item_id]"]`);
        const parentIdField = component.querySelector(`[name="menu[parent_id]"]`);
        const orderPositionField = component.querySelector(`[name="menu[order_position]"]`);

        /**
         * Loads order position options based on the selected parent ID.
         */
        const loadOrderPositionOptions = async () => {
            const formData = new FormData();
            formData.append(`menu[parent_id]`, parentIdField.value || ``);
            formData.append(`menu[item_id]`, itemIdField.value || ``);
            formData.append(`menu[locale]`, `{{ $locale }}`);

            component.classList.add(UI.css.disabled);

            try {
                const data = await fetchActionData(
                    `/xhr/admin/menu/order-position-options`,
                    formData
                );

                if (data && data.orderPositionOptions) {
                    orderPositionField.innerHTML = data.orderPositionOptions;
                }
            } catch (err) {
                console.error(`[MenuManager #loadOrderPositionOptions]:`, err);
            } finally {
                UI.Select();
                component.classList.remove(UI.css.disabled);
            }
        }

        parentIdField.addEventListener(`UI.selected`, async () => await loadOrderPositionOptions());
        loadOrderPositionOptions();
    }
</script>
