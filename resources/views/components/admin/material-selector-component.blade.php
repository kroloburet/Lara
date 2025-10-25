@props(['locale' => app()->getLocale()])

<!--- Material Selector Component --->
<!--- Filter --->
<h3>Оберіть матеріал</h3>

<form id="materialSelectorComponent" class="UI_form-component UI_adaptive">
    <select name="type" class="UI_Select UI_inline-form" data-select-placeholder="">
        @foreach(config('app.materials.types', []) as $type => $item)
            <option value="{{ $type }}">
                {{ __("material.{$type}.he.upper") }}
            </option>
        @endforeach
    </select>
    <input name="locale" type="hidden" value="{{ $locale }}">
    <x-form.search-query-component />
</form>

<!--- Result Container --->
<div id="materialSelectorComponent_results">
    <!--- Unfinished Material List Result --->
    <div id="unfinishedMaterialContainer" class="warning-section">
        <form id="unfinishedMaterialListFilterComponent">
            <input type="hidden" name="type" value="{{ $type }}">
        </form>

        <span class="form_field-label">{!! __("admin.material.unfinished_label") !!}</span>
        <i class="base_hint-icon" data-hint="this"></i>
        <span class="UI_Hint">{!! __("admin.material.unfinished_hint") !!}</span>
        <section id="unfinishedMaterialListFilter_result" class="Paginator-list-view"></section>
        <button id="unfinishedMaterialListFilter_more"
                type="button" class="UI_button UI_contour Paginator_more">
            {!! __('base.Load_more_results') !!}
        </button>
    </div>

    <!--- Material List Result --->
    <section id="materialSelector_result" class="Paginator-list-view"></section>
    <button id="materialSelector_more"
            type="button" class="UI_button UI_contour Paginator_more">
        {!! __('base.Load_more_results') !!}
    </button>
</div>

<!--
########### Material Selector Component
-->

<script>
    {
        new class MaterialSelector {
            constructor() {
                UI.Select({selector: `#materialSelectorComponent [name=type]`});

                // Fetch unfinished materials
                this.unfinishedContainer = document.getElementById(`unfinishedMaterialContainer`);
                this.unfinishedFilter = async () => {
                    const filter = new Filter(
                        {
                            form: `#unfinishedMaterialListFilterComponent`,
                            resultContainer: `#unfinishedMaterialListFilter_result`,
                            moreButton: `#unfinishedMaterialListFilter_more`,
                            actionURL: `{{ route('xhr.admin.paginate.unfinished.materials.list') }}`,
                            enableUrlSearchParams: false,
                        }
                    );
                    await filter.fetchFilterResult();
                    this.unfinishedContainer.style.display = !filter.resultItems?.length ? `none` : `block`;
                }
                this.unfinishedFilter();

                // Fetch filtered materials
                new Filter(
                    {
                        form: `#materialSelectorComponent`,
                        resultContainer: `#materialSelector_result`,
                        moreButton: `#materialSelector_more`,
                        actionURL: `/xhr/admin/material-selector/list`,
                        enableUrlSearchParams: false,
                    }
                ).fetchFilterResult();

                // Listen results items controls
                const materialSelectorComponent_results = document.getElementById(`materialSelectorComponent_results`);
                materialSelectorComponent_results.addEventListener(`click`, async event => {
                    const trigger = event.target?.closest(`[data-props]`);
                    if (!trigger) return;

                    // Get material data
                    const props = trigger.getAttribute(`data-props`)?.trim();

                    // Dispatch event with material data
                    const selectedEvent = new CustomEvent(`MaterialSelector.selected`, {detail: JSON.parse(props)});
                    document.dispatchEvent(selectedEvent);
                });

                // Listen component select Type
                const componentTypeField = document.querySelector(`#materialSelectorComponent [name=type]`);
                componentTypeField.addEventListener(`change`, async event => {
                    event.preventDefault();

                    // Update unfinished materials filter
                    const unfinishedForm = document.getElementById(`unfinishedMaterialListFilterComponent`);
                    const unfinishedTypeField = unfinishedForm.querySelector(`#unfinishedMaterialListFilterComponent [name="type"]`);
                    unfinishedTypeField.value = componentTypeField.value;
                    await this.unfinishedFilter();
                });
            }
        }
    }
</script>
