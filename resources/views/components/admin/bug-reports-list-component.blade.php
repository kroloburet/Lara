
<!--- Bug Reports List Component --->
<form id="bugReportsComponent">
    <h2>{!! __('admin.dashboard.Bug_reports') !!}</h2>

    <!--- Reports List Result --->
    <section id="bugReportsComponent_result" class="Paginator-list-view"></section>
    <button id="bugReportsComponent_more" type="button" class="UI_button UI_contour Paginator_more">
        {!! __('base.Load_more_results') !!}
    </button>
</form>

@pushonce('startPage')

    <!--
    ########### Bug Reports List Component
    -->

    <style>
        #bugReportsComponent {
            margin: var(--layout-gap) 0;
        }
    </style>
@endpushonce

@pushOnce('endPage')

    <!--
    ########### Bug Reports List Component
    -->

    <script>
        class BugReportsListController {
            #component = document.getElementById(`bugReportsComponent`);

            constructor() {
                new Paginator(
                    {
                        formData: new FormData(this.#component),
                        resultContainer: `#bugReportsComponent_result`,
                        moreButton: `#bugReportsComponent_more`,
                        actionURL: `{{ route('xhr.admin.paginate.bug-reports.list') }}`,
                    }
                ).fetchPaginatorResult();

                // Listen results items controls
                const resultContainer = document.getElementById(`bugReportsComponent_result`);
                resultContainer.addEventListener(`click`, async event => {
                    const isBtn = event.target;

                    // Toggle details
                    if (isBtn.classList.contains(`detailsMoreButton`)) {
                        this.#showDetails(isBtn);
                    }

                    // Delete report
                    if (isBtn.classList.contains(`delReport`)) {
                        await this.#delReport(isBtn);
                    }
                });
            }

            #showDetails(trigger) {
                const item = trigger.closest(`.Paginator_item`)

                if (! item) return;

                const detailsContainer = item.querySelector(`.detailsMoreContainer`);
                const detailsContainers = this.#component.querySelectorAll(`.detailsMoreContainer`);

                detailsContainers.forEach(container => {
                    if (container !== detailsContainer) {
                        container.style.display = `none`;
                    }
                });

                UI.Toggle(detailsContainer, `flex`);
            }

            async #delReport(trigger) {
                try {
                    trigger.classList.add(UI.css.process);

                    const data = await fetchActionData(
                        `{{ route('xhr.admin.delete.bug-report') }}`,
                        JSON.stringify({id: trigger.dataset.id})
                    );

                    if (data.ok) trigger.closest(`.Paginator_item`).remove();
                } catch (e) {
                    console.error(e);
                } finally {
                    trigger.classList.remove(UI.css.process);
                }
            }
        }

        document.addEventListener(`DOMContentLoaded`, () => {
            new BugReportsListController();
        });
    </script>
@endPushOnce
