
<!--- Moderators List Filter Component --->
<div class="UI_fieldset">
    <form id="moderatorsListFilterComponent" class="UI_form-component UI_inline-form UI_adaptive">
        <x-form.order-by-component>
            <x-slot:additionalOptions>
                <option value="deleted_at__asc">{{ __('form.order_by.blocked') }}</option>
            </x-slot:additionalOptions>
        </x-form.order-by-component>
        <x-form.search-query-component />
    </form>

    @can('permits', ['moderator', 'c'])
        <a href="{{ route('admin.create.moderator') }}"
           class="UI_button fa-solid fa-plus"
           title="{{ __("admin.moderator.add.page_title") }}"></a>
    @endcan
</div>

<!--- Moderators List Result --->
<section id="moderatorsListFilter_result" class="Paginator-list-view"></section>
<button id="moderatorsListFilter_more" type="button" class="UI_button UI_contour Paginator_more">
    {!! __('base.Load_more_results') !!}
</button>

@pushOnce('endPage')

    <!--
    ########### Moderators List Filter Component
    -->

    <script>
        class ModeratorsFilterController {
            constructor() {
                new Filter(
                    {
                        form: `#moderatorsListFilterComponent`,
                        resultContainer: `#moderatorsListFilter_result`,
                        moreButton: `#moderatorsListFilter_more`,
                        actionURL: `{{ route('xhr.admin.paginate.moderators.list') }}`,
                    }
                ).fetchFilterResult();

                // Listen results items controls
                const resultContainer = document.getElementById(`moderatorsListFilter_result`);
                resultContainer.addEventListener(`click`, async event => {
                    const isBtn = event.target;

                    // Toggle block
                    if (isBtn.classList.contains(`toggleBlockModerator`)) {
                        await this.#toggleBlockModerator(isBtn);
                    }

                    // Delete
                    if (isBtn.classList.contains(`delModerator`)) {
                        await this.#delModerator(isBtn);
                    }
                });
            }

            async #toggleBlockModerator(trigger) {
                try {
                    trigger.classList.add(UI.css.process);

                    const data = await fetchActionData(
                        `{{ route('xhr.admin.toggle.block.moderator') }}`,
                        JSON.stringify({
                            _token: global.csrfToken,
                            id: trigger.dataset.id,
                        })
                    );

                    if (data.ok) {
                        if (trigger.classList.contains(`fa-unlock`)) {
                            trigger.classList.remove(`fa-unlock`);
                            trigger.classList.add(`fa-lock`, `red-text`);
                        } else {
                            trigger.classList.remove(`fa-lock`, `red-text`);
                            trigger.classList.add(`fa-unlock`);
                        }

                        UI.OkNotice(data.message);
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    trigger.classList.remove(UI.css.process);
                }
            }

            async #delModerator(trigger) {
                const execute = async () => {
                    try {
                        trigger.classList.add(UI.css.process);

                        const data = await fetchActionData(
                            `{{ route('xhr.admin.delete.moderator') }}`,
                            JSON.stringify({
                                _token: global.csrfToken,
                                id: trigger.dataset.id,
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
                    `{!! __('admin.moderator.list.del_confirm') !!}`,
                    execute
                );
            }
        }

        document.addEventListener(`DOMContentLoaded`, () => {
            new ModeratorsFilterController();
        });
    </script>
@endPushOnce
