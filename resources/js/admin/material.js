window.Material = class {
    #conf = {};
    #contentEditor = null;
    #contentValues = null;
    #form = document.getElementById(`fetchFormData`);
    #localeField = this.#form.querySelector(`:scope [name="locale"]`);
    #aliasField = this.#form.querySelector(`:scope [name="alias"]`);
    #contentField = this.#form.querySelector(`:scope [name="content"]`);

    /**
     *
     * @param {Object} conf Class configuration
     * @param {string} conf.action Material action ('create' or 'update')
     * @param {string} conf.type Material type
     * @param {string} conf.userLocale User interface locale
     * @param {string} conf.actionURL The storing action request URL
     * @param {string} conf.confirmText Confirm message if content is changed
     * @param {[]} conf.collection Array of elements with fields or fields for storing
     * @param {[]} conf.listenCollection Array of fields for listen on content change event
     */
    constructor(conf) {
        this.#conf = conf;
        this.#init();
    }

    async #init() {
        try {
            this.#formDisable(true);
            this.#contentEditor = await this.#initContentEditor();
            await this.#insertContentData();
            fetchFormData(this.#conf.collection, this.#conf.actionURL);

            this.#localeField.addEventListener(`change`, async () =>
                await this.#insertContentData());

            this.#localeField.addEventListener(`UI.beforeDropdownShow`, () =>
                this.#isContentChanged() ? this.#confirm() : null);

            this.#form.addEventListener(`beforeFetchFormData`, () => {
                this.#formDisable(true);
                this.#contentField.value = this.#getEditorContent()
            });

            this.#form.addEventListener(`fetchFormDataOk`, () => {
                this.#aliasField?.setAttribute(`readonly`, true);
                this.#keepContentValues();
                this.#formDisable(false);
            });

            window.addEventListener(`beforeunload`, event => {
                if (this.#isContentChanged()) {
                    event.preventDefault();
                    event.returnValue = ``;
                }
            });
        } catch (e) {
            console.error(`[Material.initContentEditor]: Failed to init class.\n`, e);
        } finally {
            this.#formDisable(false);
        }
    }

    /**
     * Load and configure content editor with Flmngr integration
     * - Forces all inserted file URLs to be relative
     * - Removes width/height fields in image dialog
     *
     * @return {Promise<{}>}
     */
    async #initContentEditor(){
        const conf = {
            // Start External Plugins
            external_plugins: {
                'file-manager': `/js/file-manager/plugin.min.js?nocache=${Date.now()}`,
            },
            Flmngr: {
                apiKey: window.global.flmngrApiKey
            },
            // End External Plugins

            target: this.#contentField,
            language: this.#conf.editorLocale,
            min_height: 500,
            invalid_elements: `style, script`,
            image_title: true,
            image_description: false,
            image_dimensions: false,
            convert_urls: false,

            link_default_target: `_blank`,
            link_context_toolbar: true,
            link_title: false,
            link_target_list: false,
            link_rel_list: [
                { title: `nofollow`, value: `nofollow`},
                { title: `ugc`, value: `ugc`},
                { title: `nofollow ugc`, value: `nofollow ugc`},
                { title: `sponsored`, value: `sponsored`},
                { title: `none`, value: ``},
            ],

            style_formats: [
                { title: `Headings`, items: [
                        { title: `Heading 2`, block: `h2` },
                        { title: `Heading 3`, block: `h3` },
                        { title: `Heading 4`, block: `h4` },
                        { title: `Heading 5`, block: `h5` },
                    ] },
                { title: `Inline`, items: [
                        { title: `Bold`, format: `bold` },
                        { title: `Italic`, format: `italic` },
                        { title: `Underline`, format: `underline` },
                        { title: `Strikethrough`, format: `strikethrough` },
                        { title: `Superscript`, format: `superscript` },
                        { title: `Subscript`, format: `subscript` },
                        { title: `Code`, format: `code` }
                    ]},
                { title: `Blocks`, items: [
                        { title: `Paragraph`, format: `p` },
                        { title: `Blockquote`, format: `blockquote` },
                        { title: `Div`, format: `div` },
                        { title: `Pre`, format: `pre` }
                    ]},
                { title: `Align`, items: [
                        { title: `Left`, format: `alignleft` },
                        { title: `Center`, format: `aligncenter` },
                        { title: `Right`, format: `alignright` },
                        { title: `Justify`, format: `alignjustify` }
                    ]},
            ],

            block_formats: `Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5`,
            plugins: [`anchor`, `autolink`, `charmap`, `code`, `emoticons`, `fullscreen`, `image`, `link`, `lists`, `media`, `quickbars`, `searchreplace`, `table`, `file-manager`],
            toolbar: `undo redo | styles bold italic bullist numlist align | link image media table | emoticons charmap hr | code fullscreen`,
            quickbars_insert_toolbar: `styles link image media quicktable bullist numlist charmap emoticons`,
            quickbars_selection_toolbar: `styles link bold italic h2 h3 h4 blockquote removeformat | align`,
            quickbars_image_toolbar: `alignleft aligncenter alignright`,
        };

        const editors = await Editors(conf);
        return editors[0];
    }

    /**
     * Disable or enable material form
     *
     * @param {boolean} stage True - disable or false - enable
     */
    #formDisable(stage) {
        this.#form.classList.toggle(UI.css.disabled, stage);
    }

    /**
     * Keep values from listenCollection
     */
    #keepContentValues() {
        this.#contentValues = this.#getContentValues();
    }

    /**
     * Get editor content without fucking shit..(
     *
     * @return {string}
     */
    #getEditorContent() {
        return this.#contentEditor.getFilteredContent();
    }

    /**
     * Get current values from listenCollection
     * for track changes
     *
     * @return {string}
     */
    #getContentValues() {
        return this.#conf.listenCollection.map(field =>
            field.name === `content` ? this.#getEditorContent() : field.value
        ).join(``);
    }

    /**
     * Value from listenCollection is changed
     *
     * @return {boolean}
     */
    #isContentChanged(){
        return this.#contentValues !== this.#getContentValues();
    }

    /**
     * Show confirm if content changed
     *
     * @return {boolean}
     */
    #confirm() {
        return UI.Confirm(
            this.#conf.confirmText,
            async () => {
                this.#form.querySelector(`:scope button[type="submit"]`).click();
            }
        );
    }

    /**
     * Get data of the chosen language
     * and insert to listenCollection values
     *
     * @return {Promise<*|string>}
     */
    async #insertContentData() {
        if (this.#conf.action === `create`) {
            this.#keepContentValues();
            return
        }

        try {
            this.#formDisable(true);

            const formData = {
                locale: this.#localeField.value,
                type: this.#conf.type,
            }

            // Static materials may not have alias field
            if (this.#aliasField) formData.alias = this.#aliasField.value;

            const data = await fetchActionData(
                `/xhr/admin/get/material/content`,
                JSON.stringify(formData)
            );

            if (data.fields) {
                for (let name in data.fields) {
                    const formElement = this.#form.querySelector(`:scope [name="${name}"]`);
                    if (formElement) {
                        formElement.value = data.fields[name];
                        if (name === `content`) this.#contentEditor.setContent(data.fields[name] ?? ``);
                    }
                }
            } else {
                this.#conf.listenCollection.forEach(field => {
                    field.value = null
                    if (field.name === `content`) this.#contentEditor.setContent(``);
                });
            }

            this.#keepContentValues();
        } catch (e) {
            console.error(`[Material.insertContentData]: Failed to insert data.\n`);
        } finally {
            this.#formDisable(false);
        }
    }
}
