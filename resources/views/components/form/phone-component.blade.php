@props(['phone' => null, 'required' => false])
@php([$countryCode, $number] = $phone ? explode(' ', $phone) : [null, null])

<!--- Phone Component --->
<div id="phoneComponent" {{ $attributes }}>
    <div class="UI_form-component">
        <select class="UI_Select UI_inline-form phoneComponent_country-code"
                data-select-placeholder=""
                data-search-placeholder="{{ __('base.Search_on_list') }}"
                data-with-search="true">
            @foreach(getCountriesCollect() as $country)
                <option data-find-of="{{ $country['name'] }} {{ $country['native'] }} {{ $country['iso2'] }}"
                    value="{{ $country['phoneCode'] }}" @selected($countryCode === $country['phoneCode'])>
                    {{ $country['flag'] }} {{ $country['phoneCode'] }}</option>
            @endforeach
        </select>
        <input type="tel" class="phoneComponent_tel" placeholder="{{ __('form.phone.placeholder') }}"
               autocomplete="tel" value="{{ $number }}"
               @required($required)>
    </div>
    <textarea type="hidden" name="phone" class="phoneComponent_data" @required($required)>{{ $phone }}</textarea>
</div>

@pushOnce('endPage')

    <!--
    ########### Phone Component
    -->

    <script>
        window.phoneComponent = new class {
            #component = document.querySelector(`#phoneComponent`);
            #countryCodeField = this.#component.querySelector(`:scope .phoneComponent_country-code`);
            #telField = this.#component.querySelector(`:scope .phoneComponent_tel`);
            #dataField = this.#component.querySelector(`:scope .phoneComponent_data`);

            constructor() {
                // Search user country in options list
                this.#countryCodeField.addEventListener(`UI.beforeDropdownShow`, async () => {
                    const data = await IPinfo();
                    const country = data?.country;
                    if (! country) return;
                    this.#countryCodeField.UI.Builder.search(country);
                    this.#countryCodeField.UI.searchInput.value = country;
                    this.#countryCodeField.UI.searchInput.focus();
                });

                // Country chosen
                this.#countryCodeField.addEventListener(`change`, () => {
                    this.#telAutoFormat();
                    this.#dataField.value = this.#getData();
                    this.#telField.focus();
                });

                // Input phone number
                this.#telField.addEventListener(`keyup`, () => {
                    this.#telAutoFormat();
                    this.#dataField.value = this.#getData();
                });
            }

            /**
             * Format phone field value, rewrite
             */
            #telAutoFormat() {
                this.#telField.value = this.#telField.value
                    .replace(/\D/g, ``)
                    .slice(0, 11);
            }

            /**
             * Return json string with fields data
             * or empty string if tel not valid
             * @returns {string}
             */
            #getData() {
                if (this.validate() && this.#telField.value) {
                    return `${this.#countryCodeField.value} ${this.#telField.value}`;
                }
                return ``;
            }

            /**
             * Public validate of component
             * @returns {boolean}
             */
            validate() {
                const isOptional = !this.#telField.hasAttribute(`required`);

                // Empty field is Ok if not required
                if (isOptional && this.#telField.value.trim() === ``) {
                    this.#telField.classList.remove(UI.css.invalidForm);
                    return true;
                }

                // Original validation logic for non-empty or required field
                const isValid = /^\d{3,11}$/.test(this.#telField.value);
                this.#telField.classList.toggle(UI.css.invalidForm, !isValid);
                return isValid;
            }
        }
    </script>
@endPushOnce
