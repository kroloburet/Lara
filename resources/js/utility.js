export class Isolator {
    #storage = {};

    /**
     * This class-singleton provides an interface to delete
     * and storage DOM elements. Any elements can be deleted
     * from the DOM and stored and then restored
     *
     * @return {Isolator}
     */
    constructor() {
        // Return this instance
        if (typeof Isolator.instance === 'object') {
            return Isolator.instance;
        }
        // Return new instance
        Isolator.instance = this;
        return Isolator.instance;
    }

    /**
     * All children of each element of the
     * collection delete from the DOM and stored
     *
     * @param {string} key Storage key of collection
     * @param {[HTMLElement]} collection Array of elements for storage
     * @returns {Isolator}
     */
    push(key, collection) {
        if (!key || this.has(key) || !collection || !Array.isArray(collection)) return this;
        this.#storage[key] = [];
        collection.forEach(container => {
            this.#storage[key].push({
                container,
                content: [...container.childNodes],
            });
            container.innerHTML = ``;
        });
        return this;
    }

    /**
     * Restore a collection of elements in a DOM
     * using a key and delete it from storage
     *
     * @param {string} key Storage key of collection
     * @returns {Isolator}
     */
    pull(key) {
        if (key in this.#storage) {
            this.#storage[key]
                .forEach(item => item.container.append(...item.content));
            delete this.#storage[key];
        }
        return this;
    }

    /**
     * Is there a key in the storage
     *
     * @param {string} key Storage key of collection
     * @return {boolean}
     */
    has(key) {
        return key in this.#storage;
    }

    /**
     * How many collections in the storage
     *
     * @return {number}
     */
    get length() {
        return Object.keys(this.#storage).length;
    }
}

export class ObserveFormState {
    #form;
    #onInitState;
    #onInitStateChange;
    #initialValues = new Map();
    #boundHandleChange;
    #mutationObserver;
    #isResetting = false;

    /**
     * @param {HTMLFormElement} form The form to be observed
     * @param {function|null} onInitStateChange Callback when fields are changed from the initial state
     * @param {function|null} onInitState Callback when fields return to the initial state
     */
    constructor(
        form,
        onInitStateChange = null,
        onInitState = null
    ) {
        if (!(form instanceof HTMLFormElement)) {
            throw new Error(`[ObserveFormState]: The first argument must be a form element.`);
        }

        this.#form = form;
        this.#onInitState = onInitState;
        this.#onInitStateChange = onInitStateChange;
        this.#boundHandleChange = this.#handleChange.bind(this);

        this.#saveInitialValues();
        this.#attachEventListeners();
        this.#attachMutationObserver();

        // Immediately call the callback to set the initial state of the form
        this.#callback();
    }

    /**
     * Saves the initial values of all relevant form fields.
     * Includes text, number, email, password, textarea, select (single & multiple), radio, checkbox, hidden inputs,
     * and file inputs (single & multiple).
     */
    #saveInitialValues() {
        this.#initialValues.clear();
        const fields = this.#form.querySelectorAll(`input[name], textarea[name], select[name]`);

        fields.forEach(field => {
            const name = field.name;
            let value;

            if (field.type === `checkbox`) {
                value = field.checked;
            } else if (field.type === `radio`) {
                if (field.checked) {
                    value = field.value;
                } else if (!this.#initialValues.has(name)) {
                    value = undefined; // No radio button with this name is initially checked
                } else {
                    return; // Skip if a value for this radio group has already been saved
                }
            } else if (field.tagName === `SELECT` && field.multiple) {
                // For multiple select, store an array of selected option values
                value = Array.from(field.options)
                    .filter(option => option.selected)
                    .map(option => option.value)
                    .sort(); // Sort to ensure consistent comparison later
            } else if (field.type === `file` && field.multiple) {
                // For multiple file input, store an array of file names and sizes for comparison
                // Note: File objects themselves cannot be reliably compared across state changes
                value = Array.from(field.files).map(file => ({ name: file.name, size: file.size })).sort((a, b) => a.name.localeCompare(b.name));
            }
            else {
                value = field.value;
            }
            this.#initialValues.set(name, value);
        });
    }

    /**
     * Attaches standard event listeners to the form for changes.
     * Uses event delegation for better performance.
     */
    #attachEventListeners() {
        this.#form.addEventListener(`input`, this.#boundHandleChange);
        this.#form.addEventListener(`change`, this.#boundHandleChange);
    }

    /**
     * Attaches a MutationObserver to hidden input fields and textareas to detect programmatic changes.
     */
    #attachMutationObserver() {
        // Select all hidden inputs AND textareas that have a name attribute
        const hiddenFields = this.#form.querySelectorAll(`input[type="hidden"][name], textarea[name]`);

        if (hiddenFields.length > 0) {
            this.#mutationObserver = new MutationObserver(mutations => {
                if (this.#isResetting) {
                    return; // Ignore the changes if in the reset process
                }

                let relevantChange = false;
                for (const mutation of mutations) {
                    const target = mutation.target;

                    if (target.matches(`input[type="hidden"][name]`)) {
                        // For hidden inputs, check attribute changes ('value', 'checked')
                        if (mutation.type === `attributes` && (mutation.attributeName === `value` || mutation.attributeName === `checked`)) {
                            relevantChange = true;
                            break;
                        }
                    } else if (target.matches(`textarea[name]`)) {
                        // For textareas, check character data changes (textContent)
                        if (mutation.type === `characterData` || (mutation.type === `childList` && target.contains(mutation.addedNodes[0]) && mutation.addedNodes[0].nodeType === Node.TEXT_NODE)) {
                            relevantChange = true;
                            break;
                        }
                        // Also check for 'value' attribute change, though less common for textarea
                        if (mutation.type === `attributes` && mutation.attributeName === `value`) {
                            relevantChange = true;
                            break;
                        }
                    }
                }
                if (relevantChange) {
                    this.#handleChange();
                }
            });

            // Configuration for the MutationObserver:
            // - attributes: true to watch for 'value'/'checked' on inputs
            // - characterData: true to watch for text content changes in textareas
            // - childList: true to watch for text node additions/removals in textareas
            // - subtree: true to ensure we catch text node changes within the textarea
            const config = {
                attributes: true,
                attributeFilter: [`value`, `checked`],
                characterData: true, // For changes to text nodes (e.g., in textarea)
                childList: true,     // For adding/removing text nodes (e.g., in textarea)
                subtree: true        // Necessary to observe text nodes inside textarea
            };

            // Observe each relevant hidden field
            hiddenFields.forEach(field => {
                this.#mutationObserver.observe(field, config);
            });
        }
    }

    /**
     * Detaches all event listeners and the MutationObserver from the form.
     * Essential for preventing memory leaks.
     */
    #detachEventListeners() {
        this.#form.removeEventListener(`input`, this.#boundHandleChange);
        this.#form.removeEventListener(`change`, this.#boundHandleChange);
        if (this.#mutationObserver) {
            this.#mutationObserver.disconnect();
        }
    }

    /**
     * Handles any form state change by triggering the main callback logic.
     */
    async #handleChange() {
        await this.#callback();
        this.#form.dispatchEvent(new CustomEvent(`ObserveFormState.changed`));
    }

    /**
     * Checks the form fields for changes against their initial values.
     * Triggers appropriate callbacks (#onInitStateChange or #onInitState).
     */
    async #callback() {
        let isChanged = false;
        const currentFormValues = new Map();

        // Collect current values of all relevant fields
        this.#form.querySelectorAll(`input[name], textarea[name], select[name]`).forEach(field => {
            const name = field.name;
            let currentValue;

            if (field.type === `checkbox`) {
                currentValue = field.checked;
            } else if (field.type === `radio`) {
                if (field.checked) {
                    currentValue = field.value;
                } else if (!currentFormValues.has(name)) {
                    currentValue = undefined;
                } else {
                    return;
                }
            } else if (field.tagName === `SELECT` && field.multiple) {
                // For multiple select, get an array of current selected option values
                currentValue = Array.from(field.options)
                    .filter(option => option.selected)
                    .map(option => option.value)
                    .sort(); // Sort for consistent comparison
            } else if (field.type === `file` && field.multiple) {
                // For multiple file input, get an array of current file names and sizes
                currentValue = Array.from(field.files).map(file => ({ name: file.name, size: file.size })).sort((a, b) => a.name.localeCompare(b.name));
            }
            else {
                currentValue = field.value;
            }
            currentFormValues.set(name, currentValue);
        });

        // Compare current values with initial values
        for (const [name, initialValue] of this.#initialValues.entries()) {
            const currentValue = currentFormValues.get(name);

            // Special handling for radio buttons
            const radioFields = Array.from(this.#form.querySelectorAll(`input[name="${name}"][type="radio"]`));
            if (radioFields.length > 0) {
                const initialCheckedRadioValue = initialValue;
                const currentCheckedRadio = radioFields.find(radio => radio.checked);
                const currentCheckedRadioValue = currentCheckedRadio ? currentCheckedRadio.value : undefined;

                if (initialCheckedRadioValue !== currentCheckedRadioValue) {
                    isChanged = true;
                    break;
                }
            } else if (Array.isArray(initialValue) && Array.isArray(currentValue)) {
                // Compare arrays for multiple select and multiple file fields
                if (initialValue.length !== currentValue.length) {
                    isChanged = true;
                    break;
                }
                if (initialValue.some((val, index) => {
                    if (typeof val === 'object' && val !== null && typeof currentValue[index] === 'object' && currentValue[index] !== null) {
                        // For file objects (name and size comparison)
                        return val.name !== currentValue[index].name || val.size !== currentValue[index].size;
                    }
                    // For primitive values (like select options)
                    return val !== currentValue[index];
                })) {
                    isChanged = true;
                    break;
                }
            } else if (initialValue !== currentValue) {
                isChanged = true;
                break;
            }
        }

        if (isChanged) {
            if (typeof this.#onInitStateChange === `function`) {
                await this.#onInitStateChange();
            }
        } else {
            if (typeof this.#onInitState === `function`) {
                await this.#onInitState();
            }
        }
    }

    /**
     * Resets the form fields to their initially saved values.
     * Uses the native form reset method first, then applies specific initial values.
     * Note: Programmatically resetting `input type="file"` is not possible due to security restrictions.
     * After `form.reset()`, file inputs will be cleared regardless of their initial state.
     */
    async resetToInitialState() {
        this.#isResetting = true;
        this.#form.reset(); // Native form reset

        // After native reset, apply saved initial values for all relevant fields
        this.#initialValues.forEach((initialValue, name) => {
            const fields = this.#form.querySelectorAll(`[name="${name}"]`);

            fields.forEach(field => {
                if (field.type === `checkbox`) {
                    field.checked = initialValue;
                } else if (field.type === `radio`) {
                    field.checked = (field.value === initialValue);
                } else if (field.tagName === `SELECT` && field.multiple) {
                    // For multiple select, set selected options based on the initial array
                    Array.from(field.options).forEach(option => {
                        option.selected = initialValue.includes(option.value);
                    });
                } else if (field.type === `file`) {
                    // NOTE: Setting files programmatically is not allowed due to security.
                    // The native reset already clears the file input. We cannot restore
                    // previously selected files this way.
                    // If you need to "restore" files, you'd typically manage them
                    // outside the form's state (e.g., re-upload, display previews).
                }
                else {
                    field.value = initialValue;
                }
            });
        });

        // After programmatically resetting values, re-evaluate the form state
        await this.#callback();

        // Dispatch reset Event
        this.#form.dispatchEvent(new CustomEvent(`ObserveFormState.reset`));
    }

    /**
     * Public method to manually re-save the initial values of all form fields.
     * Useful if the form's structure or initial values change dynamically after initialization.
     */
    reSaveInitialValues() {
        this.#saveInitialValues();
        this.#callback(); // Update state after re-saving
    }

    /**
     * Cleans up all event listeners and the MutationObserver.
     * Call this when the form observer is no longer needed to prevent memory leaks.
     */
    destroy() {
        this.#detachEventListeners();
        this.#form = null;
        this.#onInitState = null;
        this.#onInitStateChange = null;
        this.#initialValues.clear();
        this.#boundHandleChange = null;
        this.#mutationObserver = null;
    }
}
