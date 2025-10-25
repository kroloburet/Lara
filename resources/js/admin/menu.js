window.MenuManager = class {
    #interface = document.querySelector(`.base-main`);
    #tree = document.getElementById(`menuTree`);
    #localeField = document.getElementById(`menuLocaleField`);
    #createForm = document.getElementById(`menuCreateItemForm`);
    #updateForm = document.getElementById(`menuUpdateItemForm`);
    #updateFormPop = null;
    #locale = null;
    #deleteConfirmMsg = null;

    constructor(config) {
        this.#locale = config.langVersion;
        this.#deleteConfirmMsg = config.deleteConfirmMsg;
        this.#initListeners();
    }

    /**
     * Initializes event listeners for the menu interface.
     */
    async #initListeners() {
        await this.#setLocale();

        this.#tree.addEventListener(`click`, async event => {
            const target = event.target;

            if (target.closest(`.update`)) {
                this.#updateFormPop = await this.#initUpdateForm(target);
            }

            if (target.closest(`.toggle`)) {
                await this.#toggleItem(target);
            }

            if (target.closest(`.delete`)) {
                await this.#deleteItem(target);
            }
        });

        this.#localeField.addEventListener(`change`, async event => await this.#setLocale(event));
        this.#createForm?.addEventListener(`submit`, async event => await this.#createItem(event));
        this.#updateForm?.addEventListener(`submit`, async event => await this.#updateItem(event));
        this.#updateForm?.addEventListener(`reset`, () => this.#updateFormPop.hide());
    }

    /**
     * Sets the current locale and refreshes the interface.
     */
    async #setLocale() {
        this.#locale = this.#localeField.UI.value;

        try {
            this.#interface.classList.add(UI.css.disabled);

            const formData = new FormData();
            formData.append(`menu[locale]`, this.#locale);

            const data = await fetchActionData(
                `/xhr/admin/menu/refresh`,
                formData
            );

            this.#render(data);
        } catch (err) {
            console.error(`[MenuManager #setLocale]: error on line: ${err.lineNumber}`, err);
        } finally {
            this.#interface.classList.remove(UI.css.disabled);
        }
    }

    /**
     * Initializes the update form for a selected menu item.
     * @param {HTMLElement} trigger - The element that triggered the action.
     * @returns The popup instance for the update form.
     */
    async #initUpdateForm(trigger) {
        if (! this.#updateForm) return;

        const itemElement = trigger.closest(`li`);
        const itemIdField = this.#updateForm.querySelector(`[name="menu[item_id]"]`);
        const itemTitleField = this.#updateForm.querySelector(`[name="menu[title]"]`);
        const itemUrlField = this.#updateForm.querySelector(`[name="menu[url]"]`);
        const itemTarget = this.#updateForm.querySelector(`[name="menu[target]"]`);
        const itemPrentIdField = this.#updateForm.querySelector(`[name="menu[parent_id]"]`);

        itemIdField.value = itemElement.dataset.itemId;
        itemTitleField.value = itemElement.dataset.title;
        itemUrlField.value = itemElement.dataset.url;
        itemTarget.value = itemElement.dataset.target;

        await this.#loadParentIdOptions(
            itemPrentIdField,
            itemElement.dataset.itemId,
            itemElement.dataset.parentId
        );

        return UI.Popup(`menuUpdateItemForm`);
    }

    /**
     * Loads parent ID options for the update form, excluding the specified item.
     * @param {HTMLSelectElement} itemPrentIdField - The select field for parent ID.
     * @param {string} updatableItemId - The ID of the item being updated.
     * @param {string} parentItemId - The current parent ID of the item.
     */
    async #loadParentIdOptions(itemPrentIdField, updatableItemId, parentItemId) {
        this.#interface.classList.add(UI.css.disabled);

        try {
            const formData = new FormData();
            formData.append(`menu[locale]`, this.#locale);
            formData.append(`menu[item_id]`, updatableItemId);

            const data = await fetchActionData(
                `/xhr/admin/menu/parent-id-options`,
                formData
            );

            if (data && data.parentIdOptions) {
                itemPrentIdField.innerHTML = data.parentIdOptions;
            }
        } catch (err) {
            console.error(`[MenuManager #loadParentIdOptions]:`, err);
        } finally {
            itemPrentIdField.value = parentItemId;
            itemPrentIdField.dispatchEvent(new CustomEvent(`UI.selected`));
            this.#interface.classList.remove(UI.css.disabled);
        }
    }

    /**
     * Handles the creation of a new menu item.
     * @param {Event} event - The form submission event.
     */
    async #createItem(event) {
        event.preventDefault();

        if (! this.#createForm) return;

        const submitButton = this.#createForm.querySelector(`[type="submit"]`)
        const formData = new FormData(this.#createForm);

        try {
            this.#interface.classList.add(UI.css.disabled);
            submitButton.classList.add(UI.css.process);
            formData.append(`menu[locale]`, this.#locale);

            const data = await fetchActionData(
                `/xhr/admin/menu/create`,
                formData
            );

            this.#render(data);
        } catch (err) {
            console.error(`[MenuManager #createItem]: error on line: ${err.lineNumber}`, err);
        } finally {
            this.#interface.classList.remove(UI.css.disabled);
            submitButton.classList.remove(UI.css.process);
        }
    }

    /**
     * Handles the update of an existing menu item.
     * @param {Event} event - The form submission event.
     */
    async #updateItem(event) {
        event.preventDefault();

        if (! this.#updateForm) return;

        const submitButton = this.#updateForm.querySelector(`[type="submit"]`)
        const formData = new FormData(this.#updateForm);

        try {
            this.#interface.classList.add(UI.css.disabled);
            submitButton.classList.add(UI.css.process);
            formData.append(`menu[locale]`, this.#locale);

            const data = await fetchActionData(
                `/xhr/admin/menu/update`,
                formData
            );

            this.#render(data);
            this.#updateFormPop.hide();
        } catch (err) {
            console.error(`[MenuManager #updateItem]: error on line: ${err.lineNumber}`, err);
        } finally {
            this.#interface.classList.remove(UI.css.disabled);
            submitButton.classList.remove(UI.css.process);
        }
    }

    /**
     * Handles the deletion of a menu item.
     * @param {HTMLElement} trigger - The element that triggered the action.
     */
    async #deleteItem(trigger) {
        UI.Confirm(this.#deleteConfirmMsg, async () => {
            try {
                const itemElement = trigger.closest(`li`);
                const formData = new FormData();
                formData.append(`menu[locale]`, this.#locale);
                formData.append(`menu[item_id]`, itemElement.dataset.itemId);

                this.#interface.classList.add(UI.css.disabled);
                trigger.classList.add(UI.css.process);

                const data = await fetchActionData(
                    `/xhr/admin/menu/delete`,
                    formData
                );

                this.#render(data);
            } catch (err) {
                console.error(`[MenuManager #updateItem]: error on line: ${err.lineNumber}`, err);
            } finally {
                this.#interface.classList.remove(UI.css.disabled);
                trigger.classList.remove(UI.css.process);
            }
        });
    }

    /**
     * Toggles the visibility of a menu item.
     * @param {HTMLElement} trigger - The element that triggered the action.
     */
    async #toggleItem(trigger) {
        try {
            const itemElement = trigger.closest(`li`);
            const formData = new FormData();
            formData.append(`menu[locale]`, this.#locale);
            formData.append(`menu[item_id]`, itemElement.dataset.itemId);

            this.#interface.classList.add(UI.css.disabled);
            trigger.classList.add(UI.css.process);

            const data = await fetchActionData(
                `/xhr/admin/menu/toggle`,
                formData
            );

            this.#render(data);
        } catch (err) {
            console.error(`[MenuManager #toggleItem]: error on line: ${err.lineNumber}`, err);
        } finally {
            this.#interface.classList.remove(UI.css.disabled);
            trigger.classList.remove(UI.css.process);
        }
    }

    /**
     * Renders the updated menu fragments in the DOM.
     * @param {Object} response - The server response containing HTML fragments.
     */
    #render(response) {
        if (response) {
            if (response.menuTreeInner) {
                this.#tree.innerHTML = response.menuTreeInner;
            }

            if (response.menuCreateFormInner && this.#createForm) {
                const innerContainer = this.#createForm.querySelector(`.menuFormInnerContainer`);
                innerContainer.innerHTML = response.menuCreateFormInner;
                executeElementScripts(innerContainer);
            }

            if (response.menuUpdateFormInner && this.#updateForm) {
                const innerContainer = this.#updateForm.querySelector(`.menuFormInnerContainer`);
                innerContainer.innerHTML = response.menuUpdateFormInner;
                executeElementScripts(innerContainer);
            }

            UI.Select();
        }
    }
}
