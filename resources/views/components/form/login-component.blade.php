@props(['login' => '', 'required' => true])

<!--- Login Component --->
<label id="loginComponent" {{ $attributes }}>
    <input class="UI_input" type="text" name="login" value="{!! $login !!}" @required($required)>
</label>

@pushOnce('endPage')

    <!--
    ########### Login Component
    -->

    <script>
        window.loginComponent = new class {
            #component = document.querySelector(`#loginComponent`);
            #loginField = this.#component.querySelector(`:scope input[name="login"]`);

            constructor() {
                this.#loginField.addEventListener(`keyup`, () => this.#loginAutoFormat());
            }

            /**
             * Remove forbidden chars
             */
            #loginAutoFormat() {
                this.#loginField.value = this.#loginField.value.replace(/\s/g, ``);
            }
        }
    </script>
@endPushOnce
