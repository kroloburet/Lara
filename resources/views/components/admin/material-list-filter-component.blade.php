@props(['type'])

@php
    $isStatic = config("app.materials.types.{$type}.static");
@endphp

<!--- Material List Filter Component --->
<div class="UI_fieldset">
    <form id="materialListFilterComponent" class="@if($isStatic) UI_full-width @endif">
        <div class="UI_form-component UI_adaptive @if(! $isStatic) UI_inline-form @endif" style="margin-top: 0; margin-bottom: 0;">
            <select name="locale" class="UI_Select" data-select-placeholder="">
                @foreach(config('app.available_locales') as $name => $locale)
                    <option value="{{ $locale }}" @selected(app()->getLocale() === $locale)>{{ $name }}</option>
                @endforeach
            </select>

            @if(! $isStatic)
                <x-form.order-by-component>
                    <x-slot:additionalOptions>
                        <option value="deleted_at__asc">{{ __('form.order_by.soft_deletes') }}</option>
                    </x-slot:additionalOptions>
                </x-form.order-by-component>

                <x-form.search-query-component />
            @endif
        </div>

        <input name="type" type="hidden" value="{{ $type }}">
    </form>

    @if(! $isStatic)
        @can('permits', ['material', 'c'])
            <i id="materialListFilterComponent_create"
               class="UI_button fa-solid fa-plus"
               title="{{ __("base.Add") }}"></i>
        @endcan
    @endif
</div>

<!--- Result Container --->
<div id="resultContainer">
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
    <section id="materialListFilter_result" class="Paginator-list-view"></section>
    <button id="materialListFilter_more"
            type="button" class="UI_button UI_contour Paginator_more">
        {!! __('base.Load_more_results') !!}
    </button>
</div>

@pushOnce('endPage')

    <!--
    ########### Material List Filter Component
    -->

    <script>
        class MaterialFilterController {
            constructor() {
                // Fetch unfinished materials
                this.unfinishedContainer = document.getElementById(`unfinishedMaterialContainer`);
                this.unfinishedFilter = async () => {
                    const filter = new Filter(
                        {
                            form: `#unfinishedMaterialListFilterComponent`,
                            resultContainer: `#unfinishedMaterialListFilter_result`,
                            moreButton: `#unfinishedMaterialListFilter_more`,
                            actionURL: `{{ route('xhr.admin.paginate.unfinished.materials.list') }}`,
                        }
                    );
                    await filter.fetchFilterResult();
                    if (! filter.resultItems?.length) this.unfinishedContainer.remove();
                }
                this.unfinishedFilter();

                // Fetch filtered materials
                new Filter(
                    {
                        form: `#materialListFilterComponent`,
                        resultContainer: `#materialListFilter_result`,
                        moreButton: `#materialListFilter_more`,
                        actionURL: `{{ route('xhr.admin.paginate.materials.list') }}`,
                    }
                ).fetchFilterResult();

                // Listen Create button
                const createButton = document.getElementById(`materialListFilterComponent_create`);
                createButton?.addEventListener(`click`, () => {
                    const localeField = document.querySelector(`#materialListFilterComponent [name="locale"]`);
                    const content_locale = localeField?.value ?? `{{ app()->getLocale() }}`;
                    const url = `/admin/create/{{ $type }}/${content_locale}`
                    redirect(url);
                });

                // Listen results items controls
                const resultContainer = document.getElementById(`resultContainer`);
                resultContainer.addEventListener(`click`, async event => {
                    let isBtn = event.target;

                    // Toggle public
                    if (isBtn.classList.contains(`togglePublicMaterial`)) {
                        await this.#togglePublicMaterial(isBtn);
                    }

                    // Delete
                    if (isBtn.classList.contains(`delMaterial`)) {
                        await this.#delMaterial(isBtn);
                    }
                });
            }

            async #togglePublicMaterial(trigger) {
                try {
                    trigger.classList.add(UI.css.process);

                    const data = await fetchActionData(
                        `{{ route('xhr.admin.toggle.block.material') }}`,
                        JSON.stringify({
                            type: trigger.dataset.type,
                            alias: trigger.dataset.alias,
                        })
                    );

                    if (data.ok) {
                        if (trigger.classList.contains(`fa-eye`)) {
                            trigger.classList.remove(`fa-eye`);
                            trigger.classList.add(`fa-eye-slash`, `red-text`);
                        } else {
                            trigger.classList.remove(`fa-eye-slash`, `red-text`);
                            trigger.classList.add(`fa-eye`);
                        }

                        UI.OkNotice(data.message);
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    trigger.classList.remove(UI.css.process);
                }
            }

            async #delMaterial(trigger) {
                const execute = async () => {
                    try {
                        trigger.classList.add(UI.css.process);

                        const data = await fetchActionData(
                            `{{ route('xhr.admin.delete.material') }}`,
                            JSON.stringify({
                                type: trigger.dataset.type,
                                alias: trigger.dataset.alias,
                            })
                        );

                        if (data.ok) {
                            trigger.closest(`.Paginator_item`).remove();
                            UI.OkNotice(data.message);
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        trigger.classList.remove(UI.css.process);
                    }
                }

                UI.Confirm(
                    `{!! __("admin.material.del_confirm") !!}`,
                    execute
                );
            }
        }

        document.addEventListener(`DOMContentLoaded`, () => {
            new MaterialFilterController();
        });
    </script>
@endPushOnce
