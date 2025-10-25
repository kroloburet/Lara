@props(['required' => true, 'confirmation' => true])

<!--- Password Component --->
<div id="passwordComponent" {{ $attributes }}>
    <label class="UI_form-component">
        <input type="password" name="password" class="passwordComponent_password"
            @if($confirmation)
               autocomplete="new-password"
            @endif
            @required($required)>

        <i class="fa-solid fa-eye-slash fa-eye UI_form-component-control passwordComponent_view-toggle"></i>

        @if($confirmation)
            <i class="fa-solid fa-arrows-rotate UI_form-component-control passwordComponent_generate"></i>
        @endif
    </label>

    @if($confirmation)
        <span class="form_field-label">{!! __('form.password.repeat_label') !!}</span>
        <label class="UI_form-component">
            <input type="password" name="password_confirmation" class="passwordComponent_repeat"
                   autocomplete="current-password" @required($required)>
            <i class="fa-solid fa-eye-slash fa-eye UI_form-component-control passwordComponent_repeat-control"></i>
        </label>
    @endif
</div>

@pushOnce('endPage')

    <!--
    ########### Password Component
    -->

    <script>
        window.passwordComponent = new class {
            #component = document.querySelector(`#passwordComponent`);
            #passwordField = this.#component.querySelector(`:scope .passwordComponent_password`);
            #passwordViewToggle = this.#component.querySelector(`:scope .passwordComponent_view-toggle`);

            @if($confirmation)
                #repeatFieldControl = this.#component.querySelector(`:scope .passwordComponent_repeat-control`);
                #repeatField = this.#component.querySelector(`:scope .passwordComponent_repeat`);
                #passwordPattern = /^(?=.*\p{Lu})(?=.*\p{Ll})(?=.*[\p{Z}|\p{S}|\p{P}]).{8,}$/u;
                #passwordGenerate = this.#component.querySelector(`:scope .passwordComponent_generate`);
            @endif

            constructor() {
                this.#component.addEventListener(`click`, event => {
                    if ([
                        this.#passwordViewToggle,
                        @if($confirmation) this.#repeatFieldControl @endif
                    ].includes(event.target)) {
                        const control = event.target;
                        const input = control.previousElementSibling;
                        control.classList.toggle(`fa-eye`);
                        input.type = input.type === `password` ? `text` : `password`;
                    }

                    @if($confirmation)
                        if ([this.#passwordGenerate].includes(event.target)) {
                            const password = this.#generatePassword();
                            this.#passwordField.value = password;
                            this.#repeatField.value = password;
                        }
                    @endif
                });

                @if($confirmation)
                    this.#component.addEventListener(`keyup`, event => {
                        if (event.target === this.#passwordField) {
                            this.#passwordAutoFormat();
                            this.#passwordValidate();
                        }

                        if (event.target === this.#repeatField) {
                            this.#repeatValidate();
                        }
                    });
                @endif
            }

            @if($confirmation)
                /**
                 * Remove forbidden chars
                 */
                #passwordAutoFormat() {
                    this.#passwordField.value = this.#passwordField.value.replace(/\s/g, ``);
                }

                /**
                 * Password field validation
                 * @returns {boolean}
                 */
                #passwordValidate() {
                    const isValid = this.#passwordPattern.test(this.#passwordField.value);
                    this.#passwordField.classList.toggle(UI.css.invalidForm, !isValid);
                    return isValid;
                }

                /**
                 * Repeat password field validation
                 * @returns {boolean}
                 */
                #repeatValidate() {
                    const isValid = this.#repeatField.value === this.#passwordField.value;
                    this.#repeatField.classList.toggle(UI.css.invalidForm, !isValid);
                    return isValid;
                }

                /**
                 * Password generator
                 *
                 * @param length
                 * @return {string}
                 */
                #generatePassword(length = 16) {
                    const uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    const lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
                    const numbers = '0123456789';
                    const symbols = '!@#$%^&*()_-+=';

                    const getRandom = (chars) => chars[Math.floor(Math.random() * chars.length)];

                    if (length < 4) throw new Error("Password length must be at least 4");

                    let password = [
                        getRandom(uppercaseLetters),
                        getRandom(lowercaseLetters),
                        getRandom(numbers),
                        getRandom(symbols)
                    ];

                    const allCharacters = uppercaseLetters + lowercaseLetters + numbers + symbols;

                    while (password.length < length) {
                        password.push(getRandom(allCharacters));
                    }

                    return password.sort(() => Math.random() - 0.5).join('');
                }

                /**
                 * Public validation of component
                 * @returns {boolean}
                 */
                validate() {
                    return this.#passwordValidate() && this.#repeatValidate();
                }
            @endif
        }
    </script>
@endPushOnce
