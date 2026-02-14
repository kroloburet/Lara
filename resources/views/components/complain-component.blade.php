
<!--- Complain Component --->
<div id="complainComponent" class="UI_Popup">
    <header class="popup-header">
        <div>
            <h2>{!! __('component.complain.title') !!}</h2>
            <p>{!! __('component.complain.desc') !!}</p>
        </div>
        <img src="/images/complain_pop_bg.svg" alt="To complain">
    </header>

    <form>
        <x-form.email-or-phone-component />

        <span class="form_field-label">{!! __('component.complain.theme.label') !!}</span>
        <select class="UI_Select" data-select-placeholder="" name="complain[theme]" required>
            <option value="fake">
                {{ __('component.complain.theme.fake') }}
            </option>
            <option value="mistake">
                {{ __('component.complain.theme.mistake') }}
            </option>
            <option value="confidential">
                {{ __('component.complain.theme.confidential') }}
            </option>
            <option value="third_party">
                {{ __('component.complain.theme.third_party') }}
            </option>
            <option value="unacceptable_content">
                {{ __('component.complain.theme.unacceptable_content') }}
            </option>
        </select>

        <span class="form_field-label">{!! __('component.complain.message_label') !!}</span>
        <textarea name="complain[message]" class="UI_textarea" data-lim="this, 500" required></textarea>

        <div class="UI_fieldset UI_align-r" style="margin-bottom: 0;">
            <button type="submit" class="UI_button">
                {!! __('base.Send') !!}
            </button>
            <button type="button" class="UI_button UI_contour complainComponent_cancel">
                {!! __('base.Cancel') !!}
            </button>
        </div>
    </form>
</div>

@pushOnce('endPage')

    <!--
    ########### Complain Component
    -->

    <script>
        {
            class Complain {
                #component = document.getElementById(`complainComponent`);
                #form = this.#component.querySelector(`form`);
                #submitButton = this.#form.querySelector(`[type="submit"]`);
                #cancelButton = this.#form.querySelector(`.complainComponent_cancel`);
                #url = `{{ url()->current() }}`;
                #pop = null;

                constructor() {
                    // listen to component trigger
                    document.addEventListener(`click`, event => {
                        const isTrigger = event.target;
                        let url = isTrigger.dataset?.complain

                        if (typeof url === 'undefined') return;

                        if (url === '') url = window.location.href;

                        this.#url = url;
                        this.#pop = UI.Popup(`complainComponent`);
                    });

                    // Listen to component form submit
                    this.#form.addEventListener(`submit`, async event => await this.#send(event));

                    // Listen to cancel
                    this.#cancelButton.addEventListener(`click`, () => this.#clean());
                }

                /**
                 * Send complain form
                 *
                 * @param event
                 * @returns {Promise<void>}
                 */
                async #send(event) {
                    event.preventDefault();

                    try {
                        this.#form.classList.add(UI.css.disabled);
                        this.#submitButton.classList.add(UI.css.process);

                        const formData = new FormData(this.#form);

                        formData.set(`complain[url]`, this.#url);

                        const data = await fetchActionData(
                            `{{ route('xhr.complain.send') }}`,
                            formData
                        );

                        // Success
                        if (data && data.ok && data.message) {
                            UI.OkNotice(data.message);
                            this.#clean();
                            return;
                        }

                        // Fail alert
                        if (data && data.message) UI.Alert(data.message);
                    } catch (err) {
                        console.error(`[Complain #send]:`, err);
                    } finally {
                        this.#form.classList.remove(UI.css.disabled);
                        this.#submitButton.classList.remove(UI.css.process);
                    }
                }

                /**
                 * Clean form and hide the popup
                 */
                #clean() {
                    this.#pop?.hide();
                    this.#form.reset();
                }
            }

            // Run component
            document.addEventListener(`DOMContentLoaded`, () => new Complain());
        }
    </script>
@endPushOnce
