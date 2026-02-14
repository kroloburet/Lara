
<!--- Share Component --->
<div id="shareComponent" class="UI_Popup">
    <header class="popup-header">
        <h2>{!! __('component.share.title') !!}</h2>
        <img src="/images/social_share_pop_bg.svg" alt="Share this page">
    </header>

    <label class="UI_form-component">
        <input class="UI_input UI_no-scrollbar shareComponent_url" readonly>
        <i class="UI_form-component-control fa-solid fa-copy shareComponent_copy-url-btn"
           title="{!! __('component.share.Copy_link') !!}"></i>
    </label>

    <!-- AddToAny BEGIN -->
    <div id="a2a_box" class="a2a_kit a2a_kit_size_32 a2a_default_style">
        <a class="a2a_button_linkedin"></a>
        <a class="a2a_button_facebook"></a>
        <a class="a2a_button_twitter"></a>
        <a class="a2a_button_telegram"></a>
        <a class="a2a_button_reddit"></a>
        <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
    </div>
    <!-- AddToAny END -->
</div>

@pushOnce('endPage')

    <!--
    ########### Share Component
    -->

    <script src="https://static.addtoany.com/menu/page.js" defer></script>
    <script>
        {
            class Share {
                #component = document.querySelector(`#shareComponent`);
                #currentUrl = this.#component.querySelector(`:scope .shareComponent_url`);
                #copyUrlBtn = this.#component.querySelector(`:scope .shareComponent_copy-url-btn`);
                #a2a = this.#component.querySelector(`:scope #a2a_box`);

                constructor() {
                    const a2a_config = window.a2a_config || {};
                    a2a_config.locale = `{{ app()->getLocale() }}`;

                    document.addEventListener(`click`, event => {
                        const isTrigger = event.target;
                        let url = isTrigger.dataset?.share

                        if (typeof url === 'undefined') return;

                        if (url === '') url = window.location.href;

                        this.#currentUrl.value = url;
                        this.#a2a.dataset.a2aUrl = url;
                        UI.Popup(`shareComponent`);
                    });

                    this.#currentUrl.addEventListener(`click`, () => {
                        this.#currentUrl.select();
                    });

                    this.#copyUrlBtn.addEventListener(`click`, () => {
                        window.navigator.clipboard.writeText(this.#currentUrl.value);
                        this.#copyUrlBtn.classList.add(`copied`);
                        setTimeout(() => this.#copyUrlBtn.classList.remove(`copied`), 2000);
                    });
                }
            }

            // Run component
            document.addEventListener(`DOMContentLoaded`, () => new Share());
        }
    </script>
@endPushOnce
