@props(['social_networks' => ''])

<!--- Social Networks Component --->
<div id="socialNetworksComponent" {{ $attributes }}>
        <div class="UI_form-component controlComponent">
            <select class="UI_Select UI_inline-form" data-select-placeholder="">
                <option
                    data-content="<i class='fa-brands fa-linkedin'></i>"
                    data-domain="https://www.linkedin.com/"
                    value="linkedin">Linkedin</option>
                <option
                    data-content="<i class='fa-brands fa-instagram'></i>"
                    data-domain="https://www.instagram.com/"
                    value="instagram">Instagram</option>
                <option
                    data-content="<i class='fa-brands fa-facebook'></i>"
                    data-domain="https://www.facebook.com/"
                    value="facebook">Facebook</option>
                <option
                    data-content="<i class='fa-brands fa-tiktok'></i>"
                    data-domain="https://www.tiktok.com/"
                    value="tiktok">TikTok</option>
                <option
                    data-content="<i class='fa-brands fa-square-x-twitter'></i>"
                    data-domain="https://x.com/"
                    value="x">X</option>
                <option
                    data-content="<i class='fa-brands fa-youtube'></i>"
                    data-domain="https://www.youtube.com/"
                    value="youtube">Youtube</option>
                <option
                    data-content="<i class='fa-brands fa-whatsapp'></i>"
                    data-domain="https://www.whatsapp.com/"
                    value="whatsapp">Whatsapp</option>
                <option
                    data-content="<i class='fa-brands fa-snapchat'></i>"
                    data-domain="https://www.snapchat.com/"
                    value="snapchat">Snapchat</option>
                <option
                    data-content="<i class='fa-brands fa-soundcloud'></i>"
                    data-domain="https://soundcloud.com/"
                    value="soundcloud">Soundcloud</option>
                <option
                    data-content="<i class='fa-brands fa-github'></i>"
                    data-domain="https://github.com/"
                    value="github">GitHub</option>
                <option
                    data-content="<i class='fa-brands fa-discord'></i>"
                    data-domain="https://discord.com/"
                    value="discord">Discord</option>
                <option
                    data-content="<i class='fa-brands fa-reddit'></i>"
                    data-domain="https://www.reddit.com/"
                    value="reddit">Reddit</option>
            </select>
            <input type="url">
            <i class="UI_form-component-control fa-solid fa-plus addNetwork"></i>
        </div>
    <div class="networkItems"></div>
    <textarea type="hidden" name="social_networks">{!! !empty($social_networks) ? json_encode($social_networks) : null !!}</textarea>
</div>

@pushOnce('startPage')

    <!--
    ########### Social Networks Component
    -->

    <style>
        #socialNetworksComponent > .UI_form-component {
            margin-bottom: 0;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        #socialNetworksComponent > .UI_form-component .UI_Select-control {
            border-bottom-left-radius: 0;
        }

        #socialNetworksComponent > .UI_form-component .UI_form-component-control {
            border-bottom-right-radius: 0;
        }

        #socialNetworksComponent .networkItems {
            margin-bottom: var(--UI_form-gap-bottom);
            border: var(--UI_form-border-width) solid var(--UI_form-border-color);
            border-top: none;
            border-radius: 0 0 var(--UI_form-border-radius) var(--UI_form-border-radius);
            max-height: 10em;
            overflow: auto;
        }

        #socialNetworksComponent .networkItems .UI_form-component,
        #socialNetworksComponent .networkItems .UI_form-component * {
            margin: 0;
            border: none;
            border-radius: 0;
        }

        #socialNetworksComponent .networkItems .UI_form-component:last-of-type .UI_form-component-control {
            border-bottom-right-radius: var(--UI_form-border-radius);
        }

        #socialNetworksComponent .networkItems .content {
            padding: var(--UI_form-field-paddingY) var(--UI_form-field-paddingX);
            font-size: var(--UI_form-font-size);
            line-height: var(--UI_form-line-height);
            color: var(--UI_form-font-color);
            flex-grow: 1;
        }

        #socialNetworksComponent .networkItems .content :first-child {
            margin-right: 1.2em;
        }

        #socialNetworksComponent .networkItems .noAdded {
            padding: var(--UI_form-field-paddingY) var(--UI_form-field-paddingX);
            color: var(--UI_form-placeholder-color);
            text-align: center;
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### socialNetworksComponent
    -->

    <script>
        const socialNetworksComponent = new class {
            #component = document.querySelector(`#socialNetworksComponent`);
            #controlComponent = this.#component.querySelector(`:scope .controlComponent`);
            #networkItems = this.#component.querySelector(`:scope .networkItems`);
            #selectComponent = UI.Select({selector: `#socialNetworksComponent select`});
            #networksField = this.#selectComponent.get[0];
            #urlField = this.#controlComponent.querySelector(`:scope input`);
            #addBtn = this.#controlComponent.querySelector(`:scope .addNetwork`);
            #dataField = this.#component.querySelector(`:scope textarea`);
            #data = this.#dataField.value ? JSON.parse(this.#dataField.value) : {};

            constructor() {
                this.#networksField.addEventListener(`change`, () => this.#setPlaceholder());
                this.#addBtn.addEventListener(`click`, () => this.#addNetwork());
                this.#build();
            }

            #build() {
                this.#networkItems.innerHTML = null;

                if (! this.#dataField.value) {
                    const noAdded = document.createElement(`div`);
                    noAdded.classList.add(`noAdded`);
                    noAdded.innerHTML = `{{ __('form.social_networks.no_added') }}`;
                    this.#networkItems.append(noAdded);
                    this.#setPlaceholder();
                    return;
                }

                for (let key in this.#data) {
                    const data = this.#data[key];
                    const option = this.#networksField.querySelector(`option[value="${key}"]`);
                    const icon = option.dataset.content;
                    const item = document.createElement(`div`);
                    const content = document.createElement(`div`);
                    const removeBtn = document.createElement(`i`);

                    option.disabled = true;
                    item.classList.add(UI.css.formComponent);
                    content.classList.add(`content`, `ellipsis-overflow`);
                    content.innerHTML = `${icon}${data.url}`;
                    removeBtn.classList.add(UI.css.formComponentControl, `fa-solid`, `fa-xmark`);
                    removeBtn.onclick = () => this.#removeNetwork(key);

                    item.append(content, removeBtn);
                    this.#networkItems.prepend(item);
                }

                const options = [...this.#networksField.options].filter(i => !i.disabled);
                this.#networksField.selectedIndex = options[0]?.index ?? -1;
                this.#selectComponent.render();
                this.#setPlaceholder();
                this.#controlComponent.classList.toggle(UI.css.disabled, !options.length);
            }

            #setPlaceholder() {
                const domain = this.#networksField.selectedOptions[0]?.dataset.domain;
                this.#urlField.placeholder = domain
                    ? `${domain}...`
                    : `{{ __('form.social_networks.No_networks_to_add') }}`;
                this.#urlField.value = ``;
            }

            #addNetwork() {
                if (! this.validate()) return;

                const network = this.#networksField.value;
                const option = this.#networksField.querySelector(`:scope option[value="${network}"]`);
                this.#data = Object.assign(this.#data, {
                    [network]: {
                        network: network,
                        icon: option.dataset.content,
                        url: this.#urlField.value,
                    },
                });
                this.#dataField.value = JSON.stringify(this.#data);
                this.#build();
            }

            #removeNetwork(key) {
                const option = this.#networksField.querySelector(`option[value="${key}"]`);
                option.disabled = false;
                delete this.#data[key];
                this.#dataField.value = Object.keys(this.#data).length
                    ? JSON.stringify(this.#data)
                    : ``;
                this.#build();
            }

            validate() {
                const network = this.#networksField.value;
                const reg = new RegExp(`^https?:\/\/(www\.)?${network}.+$`);
                const isValid = reg.test(this.#urlField.value);

                this.#urlField.classList.toggle(UI.css.invalidForm, !isValid);
                return isValid;
            }
        }
    </script>
@endPushOnce
