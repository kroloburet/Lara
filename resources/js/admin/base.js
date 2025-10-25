import '../../css/admin/base.css';
import Flmngr from 'flmngr';


/**
 * A reusable component for managing a list of items
 * with name and value fields, stored as JSON.
 *
 *  - see components.form.form-list-component.blade.php
 */
window.FormListComponent = class {
    #component;
    #controlComponent;
    #itemsContainer;
    #noAddedContainer;
    #nameField;
    #valueField;
    #addBtn;
    #dataField;
    #data;
    #iconClass;
    #valueRegex;

    /**
     * Initializes the component with provided configuration.
     *  - see components.form.form-list-component.blade.php
     *
     * @param {Object} config - Configuration object.
     * @param {string} config.componentId - The ID of the component (e.g., 'emailsComponent').
     * @param {string} config.iconClass - The CSS class for the item icon (e.g., 'fa-solid fa-envelope').
     * @param {RegExp} config.valueRegex - Regular expression to validate the value field.
     */
    constructor({ componentId, iconClass, valueRegex }) {
        this.#component = document.querySelector(`#${componentId}.formListComponent`);
        this.#controlComponent = this.#component.querySelector(`:scope .controlComponent`);
        this.#itemsContainer = this.#component.querySelector(`:scope .formListComponent_items`);
        this.#noAddedContainer = this.#itemsContainer.querySelector(`:scope .noAdded`);
        this.#nameField = this.#controlComponent.querySelector(`:scope .formListComponent_name`);
        this.#valueField = this.#controlComponent.querySelector(`:scope .formListComponent_value`);
        this.#addBtn = this.#controlComponent.querySelector(`:scope .addItem`);
        this.#dataField = this.#component.querySelector(`:scope textarea`);
        this.#iconClass = iconClass;
        this.#valueRegex = valueRegex;

        // Initialize this.#data as an array
        this.#data = this.#dataField.value ? JSON.parse(this.#dataField.value || `[]`) : [];

        this.#addBtn.addEventListener(`click`, () => this.#addItem());
        this.#build();
    }

    /**
     * Builds the list of items or displays the 'no added' message from the DOM.
     */
    #build() {
        this.#itemsContainer.innerHTML = ``;

        if (!this.#data.length) {
            this.#itemsContainer.append(this.#noAddedContainer);
            return;
        }

        this.#data.forEach((data, index) => {
            const item = document.createElement(`div`);
            const content = document.createElement(`div`);
            const icon = document.createElement(`i`);
            const name = document.createElement(`span`);
            const value = document.createElement(`span`);
            const removeBtn = document.createElement(`i`);

            item.classList.add(UI.css.formComponent);
            content.classList.add(`ellipsis-overflow`, `content`);
            icon.classList.add(...this.#iconClass.split(` `));
            name.textContent = data.name;
            value.innerHTML = `&nbsp;&rarr;&nbsp;${data.value}`;
            removeBtn.classList.add(UI.css.formComponentControl, `fa-solid`, `fa-xmark`);
            removeBtn.onclick = () => this.#removeItem(index);

            content.append(icon, name, value);
            item.append(content, removeBtn);
            this.#itemsContainer.append(item);
        });
    }

    /**
     * Adds a new item to the list and updates the JSON data.
     */
    #addItem() {
        if (!this.validate()) return;

        this.#data.unshift({
            name: this.#nameField.value,
            value: this.#valueField.value,
        });

        this.#dataField.value = JSON.stringify(this.#data);
        this.#nameField.value = ``;
        this.#valueField.value = ``;
        this.#build();
    }

    /**
     * Removes an item from the list by index and updates the JSON data.
     *
     * @param {number} index - The index of the item to remove.
     */
    #removeItem(index) {
        if (index >= 0 && index < this.#data.length) {
            this.#data.splice(index, 1);
        }

        this.#dataField.value = this.#data.length ? JSON.stringify(this.#data) : ``;
        this.#build();
    }

    /**
     * Validates the name and value fields.
     *
     * @returns {boolean} True if both fields are valid, false otherwise.
     */
    validate() {
        const name = this.#nameField.value;
        const value = this.#valueField.value;
        const isValidName = name.trim().length > 0;
        const isValidValue = this.#valueRegex.test(value);

        this.#nameField.classList.toggle(UI.css.invalidForm, !isValidName);
        this.#valueField.classList.toggle(UI.css.invalidForm, !isValidValue);

        return isValidName && isValidValue;
    }
}

/**
 * Update settings of Application
 *
 * @param {string} dotTargetKey Path to settings value in dot notation
 * @param {string|Object} value New settings value then will be stringify to JSON
 * @param {HTMLElement|null} component Will be blocked at the time of the request
 * @return {Promise<void>}
 */
window.setAppSettings = async (
    dotTargetKey,
    value,
    component = null
) => {
    let newSettings = null;

    try {
        component?.classList.add(UI.css.disabled);

        const data = await fetchActionData(
            `/xhr/admin/set/app/setting`,
            JSON.stringify({
                dotTargetKey,
                value,
            })
        );

        if (data.ok && data.message) UI.OkNotice(data.message);
        if (data.ok && data.newSettings) newSettings = data.newSettings;
    } catch (e) {
        console.error(`[setAppSettings]: App settings not set.\n`, e);
    } finally {
        component?.classList.remove(UI.css.disabled);
    }

    return newSettings;
}

/**
 * Initialize File Manager trigger logic.
 * - Opens Flmngr file manager when element with `data-file-selector` is clicked.
 * - If `data-file-selector` contains a selector (e.g. `input #inputElement`),
 *   then user must choose one file and the selected file's relative URL
 *   is inserted into the specified input element.
 * - If `data-file-selector` is empty, file manager opens in default mode.
 */
const fileSelectorListener = () => {
    document.addEventListener(`click`, event => {
        // Find clicked element with [data-file-selector]
        const trigger = event.target?.closest(`[data-file-selector]`);
        if (!trigger) return;

        // Get selector from attribute (if provided)
        const selector = trigger.getAttribute(`data-file-selector`)?.trim();
        const isForInput = selector && selector !== ``;

        // Show loading state on trigger
        trigger.classList.add(UI.css.process, UI.css.disabled);

        // Load Flmngr library
        Flmngr.load({
            apiKey: window.global.flmngrApiKey,
            urlFileManager: `/flmngr`,
            urlFiles: `/uploads/files`,
        }, {
            onFlmngrLoaded: () => {
                // Remove loading state
                trigger.classList.remove(UI.css.process, UI.css.disabled);

                if (isForInput) {
                    // Open in single file select mode
                    Flmngr.open({
                        isMultiple: false,
                        onFinish: (files) => {
                            if (files && files.length > 0) {
                                const input = document.querySelector(selector);
                                if (input) {
                                    input.value = toRelativeUrl(files[0].relativeUrl || files[0].url);
                                }
                            }
                        }
                    });
                } else {
                    // Open in default mode (multiple selection allowed)
                    Flmngr.open({
                        isMultiple: null,
                    });
                }
            }
        });
    });
}

/**
 * Initialize MaterialSelector trigger logic.
 * - Opens Popup with filtered materials when element with `data-file-selector` is clicked.
 * - The attribute should contain json {"locale": "uk", "selector": "#inputElement"}.
 * - The Popup will be loaded materials with "locale".
 * - Then user must choose one material relative URL
 *   is inserted into the specified "selector" input element.
 */
const materialSelectorListener = () => {
    let popElement = document.getElementById(`materialSelector`);
    let pop = null;
    let data = {};

    // Init Component logic
    document.addEventListener(`click`, async event => {
        // Find clicked element with [data-material-selector]
        const trigger = event.target?.closest(`[data-material-selector]`);
        if (!trigger) return;

        // Get data from attribute (if provided)
        data = JSON.parse(trigger.getAttribute(`data-material-selector`)?.trim());
        if (!data) return;

        // Create Popup element if ot exists
        if (!popElement) {
            popElement = document.createElement(`div`);
            popElement.id = `materialSelector`;
            popElement.classList.add(`UI_Popup`, `popup-full`, `popup-l`);
            document.body.append(popElement);
            UI.Popup();
        }

        // Show Popup
        pop = UI.Popup(`materialSelector`);

        // Load materials list into Popup
        try {
            const targetElement = document.createElement(`div`);
            const fetchURL = `/xhr/admin/material-selector/load-component`;

            pop.insert(targetElement);

            await importHTML({
                targetElement,
                fetchURL,
                fetchData: JSON.stringify({locale: data.locale}),
                reject: () => pop.hide()
            });
        } catch (err) {
            console.error(`[chooseMaterialsListener]: \n`, err);
        }
    });

    // Handle of select material event
    document.addEventListener(`MaterialSelector.selected`, event => {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        // Find element by selector
        const inputElement = document.querySelector(data.selector);

        // Element validation
        if (! inputElement || inputElement.tagName !== `INPUT`) return;

        // Set element value
        inputElement.value = event.detail?.relativeUrl;

        // Close Popup
        if (pop) pop.hide();
    });
}

// Run listeners
document.addEventListener(`DOMContentLoaded`, () => {
    fileSelectorListener();
    materialSelectorListener();
});
