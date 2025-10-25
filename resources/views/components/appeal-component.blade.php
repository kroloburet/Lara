
<!--- Appeal Component --->
<div id="appealComponent" class="UI_Popup">
    <header class="popup-header">
        <div>
            <h3>{!! __('form.appeal.popup_title') !!}</h3>
            <p>{!! __('form.appeal.popup_desc') !!}</p>
        </div>
        <img src="/images/appeal_pop_bg.svg" alt="Appeal">
    </header>

    <x-form.appeal-form-component />

    <x-contacts-view-component />
</div>

@pushOnce('endPage')

    <!--
    ########### Appeal Component
    -->

    <script>
        {
            class Appeal {
                #pop = null;

                constructor() {
                    // listen to component trigger
                    document.addEventListener(`click`, event => {
                        const isTrigger = event.target.closest(`.appeal`);

                        if (isTrigger) {
                            this.#pop = UI.Popup(`appealComponent`);
                        }
                    });
                }
            }

            // Run component
            document.addEventListener(`DOMContentLoaded`, () => new Appeal());
        }
    </script>
@endPushOnce
