
window.UI = window.UI || {};

/**************************
 * Fetching and Submitting
 **************************/

/**
 * Redirect to URL If the URL is string otherwise reload the page
 *
 * @param {string|null} url URL to redirect
 */
window.redirect = (url = null) => {
    document.body.classList.add(`redirectOverlay`, UI.css.bodyHideOverflow);
    typeof url === `string` ? location.href = url : location.reload();
}

window.Fetcher = class {
    #selfName = `Fetcher`;
    #response = null;
    #conf;

    /**
     * This is the base class that sends an array of fields of server action for processing.
     * The class can work out the server response after the validation of the fields.
     * If the server provides validation of the fields,
     * the server must return the answer in the JSON format:
     * * if exception - {"message": "Exception message", "exception": "Exception"}
     * * if error - {"message": "Error message", "errors": {"fieldName1":
     * ["error text 1", "error text 2"], "fieldName2": ["error text 1"]}}
     * * if success - {"message": "Success message", "successes": {"fieldName1":
     * ["ok text 1", "ok text 2"], "fieldName2": ["ok text 1"]}}
     *
     * @param {Object} conf Fetcher configuration
     * @param {string} conf.actionURL Fetcher action request URL
     * @param {[HTMLElement]} conf.collection Array of fields or fields wrappers elements.
     * After these elements will be shown notices of validation
     * @param {Array|null} [conf.allowedNotices = [`ok`, `error`, `wait`]] Messages that can be shown
     */
    constructor(conf) {
        this.#conf = Object.assign({allowedNotices: [`ok`, `error`, `wait`]}, conf);
        this.#conf.targets = [];
        this.#conf.submitButton = null;

        conf.collection.forEach(target => {
            if (!(target instanceof HTMLElement)) return;

            // Get target fields
            const fields = target.hasAttribute(`name`) && target.form
                ? [target]
                : [...target.querySelectorAll(`:scope [name]`)].filter(field => field.form);
            if (!fields || !Array.isArray(fields)) return;

            // Submit button if exists
            if (! this.#conf.submitButton) {
                this.#conf.submitButton = fields[0].form.querySelector(`:scope [type="submit"]`);
            }

            // Add after target notice container
            let notice = target.nextElementSibling;
            if (!notice || !notice.classList.contains(`ValidateNotice`)) {
                notice = document.createElement(`div`);
                notice.classList.add(`ValidateNotice`);
                target.after(notice);
            }

            // Set targets to the config
            this.#conf.targets.push({fields, target, notice});
        });
    }

    /**
     * Send request to actionURL with fields from
     * collection and get json data
     * @returns {Promise<any>}
     */
    async exec() {
        try {
            // Set form data object
            const formData = new FormData();
            this.#conf.targets.forEach(({fields}) => {
                fields.forEach(field => {
                    if (! formData.has(`_token`)) formData.set(`_token`, global.csrfToken);
                    const values = new FormData(field.form).getAll(field.name);
                    values.forEach(item => formData.append(field.name, item));
                });
            });

            // Send request
            const response = await fetch(this.#conf.actionURL, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                method: `POST`,
                body: formData,
            });

            // Notice if access denied
            if (UI.hasDenied(response)) return;

            // Notice if error
            else if (![200, 422].includes(response.status)) {
                UI.ErrNotice(response.status, false, () => this.notice().clean());
                return;
            }

            // return response data
            return await response.json();

        } catch (err) {
            UI.ErrNotice(err, false, () => this.notice().clean());
        }
    }

    /**
     * Handler of response. Override this method to
     * determine your logic processing server response
     * @returns {Promise<boolean>}
     */
    async validate() {
        this.notice().wait();
        const data = await this.exec();
        this.#response = data;

        // Response data exception
        if (data.exception) {
            this.notice().clean();
            console.error(`[${this.#selfName}]: server error:`, data);
            return false
        }

        // Response data errors
        if (data.errors) {
            this.notice().error(data.errors);
            return false;
        }

        // Response data susses
        this.notice().ok(data.successes);
        return true;
    }

    /**
     * Message management method
     * @return {Object} Methods
     */
    notice() {
        let buttonErrNotice = null;
        const conf = this.#conf;
        const css = [
            `notice-ok`,
            `notice-error`,
            `notice-wait`,
        ];

        return {
            /**
             * Show susses messages
             * @param {{name:[string]}} successes Object (Json) field names with susses message arrays
             */
            ok(successes) {
                this.clean();
                if (!conf.allowedNotices || !conf.allowedNotices.includes(`ok`)) return;
                conf.targets.forEach(({fields, notice,}) => {
                    fields.forEach(field => {
                        // The server returns the name of the field as "name" NOT "name[]"
                        const fieldName = field.name.replace(/\[\]$/, ``);
                        if (!successes[fieldName]) return;
                        notice.classList.add(css[0]);
                        successes[fieldName].forEach(msg => {
                            let item = document.createElement(`div`);
                            item.innerText = msg;
                            notice.append(item);
                        });
                    });
                });
            },

            /**
             * Show error messages
             * @param {{name:[string]}} errors Object (Json) field names with errors message arrays
             */
            error(errors) {
                this.clean();
                if (!conf.allowedNotices || !conf.allowedNotices.includes(`error`)) return;
                let countErrors = 0;
                let firstError;
                conf.targets.forEach(({fields, target, notice,}) => {
                    fields.forEach(field => {
                        // The server returns the name of the field as "name" NOT "name[]"
                        const fieldName = field.name.replace(/\[([^\[\]]+)\]/g, '.$1').replace(/^\./, '');
                        if (!errors[fieldName]) return;
                        if (!firstError) firstError = target;
                        notice.classList.add(css[1]);
                        errors[fieldName].forEach(msg => {
                            let item = document.createElement(`div`);
                            item.innerText = msg;
                            notice.append(item);
                            countErrors++;
                        });
                    });
                });

                // Alert error messages if targets no found
                if (!firstError) {
                    const msg = Object.values(errors).join(`<br>`);
                    if (msg) UI.Alert(msg);
                }

                // Show errors counter on submit button if exists
                if (conf.submitButton) {
                    buttonErrNotice = document.createElement(`span`);
                    buttonErrNotice.classList.add(`button-error-notice`);
                    buttonErrNotice.innerText = `errors: ${countErrors}`;
                    conf.submitButton.append(buttonErrNotice);
                }
            },

            /**
             * Show waiting messages
             * @param {string|null} message Waiting message
             */
            wait(message = `Wait please...`) {
                this.clean();
                if (!conf.allowedNotices || !conf.allowedNotices.includes(`wait`)) return;
                conf.targets.forEach(({notice}) => {
                    notice.classList.add(css[2]);
                    notice.innerHTML = message;
                });
            },

            /**
             * Clean all notices
             */
            clean() {
                conf.targets.forEach(({notice}) => {
                    notice.classList.remove(...css);
                    notice.innerHTML = ``;
                });

                // Remove errors counter on submit button if exists
                if (conf.submitButton) {
                    conf.submitButton
                        ?.querySelector(`:scope .button-error-notice`)
                        ?.remove();
                }
            }
        }
    }

    /**
     * Get response Json data after run validate method
     * @return {string|null}
     */
    get response() {
        return this.#response;
    }

    /**
     * Get configuration of Fetcher instance
     * @return {Object}
     */
    get conf() {
        return this.#conf;
    }
}

/**
 * Sends a request and processes the response, is unique value
 * field in the name="col_name" data-model="model".
 *
 * @param {HTMLElement} component
 * @param {HTMLElement|null} elementBeforeNotice
 * @return {Promise<boolean>}
 */
window.isUniqueValueRequest = async (component, elementBeforeNotice = null) => {
    if (! component || ! (component instanceof HTMLElement)) return false;

    const field = component.hasAttribute(`name`)
        ? component
        : component.querySelector(`:scope [name]`);
    const model = field.dataset.model;

    if (! field || ! field.value || ! model) return false;

    const cleanErrors = (notice) => {
        field.classList.remove(UI.css.invalidForm);
        notice.classList.remove(`notice-error`);
        notice.innerHTML = null;
    }

    try {
        component.classList.add(UI.css.disabled);

        // Add after component validate notice container if not exists
        elementBeforeNotice = elementBeforeNotice ?? component;
        let notice = elementBeforeNotice.nextElementSibling;
        if (! notice || ! notice.classList.contains(`ValidateNotice`)) {
            notice = document.createElement(`div`);
            notice.classList.add(`ValidateNotice`);
            elementBeforeNotice.after(notice);
        }

        // Clean notice
        cleanErrors(notice);

        // Get response
        const body = {
            model,
            column: field.name,
            unique_value: field.value,
        }
        if (field.dataset.ignore) body.ignore = field.dataset.ignore;
        const response = await fetch(`/xhr/is-unique-value`,{
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-Token': global.csrfToken,
            },
            method: `POST`,
            body: JSON.stringify(body),
        });

        // Notice if access denied
        if (UI.hasDenied(response)) return false;

        // Notice if field error
        else if ([422].includes(response.status)) {
            const data = await response.json();
            field.classList.add(UI.css.invalidForm);
            notice.classList.add(`notice-error`);
            data.errors['unique_value']?.forEach(msg => {
                let item = document.createElement(`div`);
                item.innerText = msg;
                notice.append(item);
            });
            return false;
        }

        // Notice if error
        else if (! [200].includes(response.status)) {
            UI.ErrNotice(`${response.status} ${response.statusText}`);
            return false;
        }

        // Return true if value unique
        return true;
    } catch (err) {
        console.error(`[isUniqueValueRequest]: error on line: ${err.lineNumber}`, err);
        return false;
    } finally {
        component.classList.remove(UI.css.disabled);
    }
}

/**
 * Simple fetcher
 *
 * @param {string} actionURL Action request URL
 * @param {FormData|string|null} [formData = null] FormData object or JSON or null
 * @param {string} [method = POST] Request method POST|GET
 * @returns {Promise<any|string>} JSON or TEXT
 */
window.fetchActionData = async (
    actionURL,
    formData= null,
    method = 'POST'
) => {
    try {
        let data = null;

        // Get response
        method = method.toUpperCase();
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': global.csrfToken,
        }

        if (!(formData instanceof FormData)) {
            headers['Content-Type'] = 'application/json;charset=utf-8';
        }

        const response = await fetch(actionURL,{
            headers,
            method: method,
            body: method === 'POST' ? formData : null,
        });

        // Notice if access denied
        if (UI.hasDenied(response)) return;

        // Notice if form error
        else if ([422].includes(response.status)) {
            data = await response.json();
            UI.Alert(data.message);
        }

        // Notice if error
        else if (![200].includes(response.status)) {
            UI.ErrNotice(`${response.status} ${response.statusText}`);
            return;
        }

        // Return JSON or TEXT
        if (! response.bodyUsed) {
            const contentType = response.headers.get(`content-type`);
            if (contentType && contentType.indexOf(`application/json`) !== -1) {
                return await response.json();
            }
            return await response.text();
        } else {
            return data;
        }
    } catch (err) {
        console.error(`[fetchActionData]: error on line: ${err.lineNumber}`, err);
    }
}

/**
 * Submit and validate form data
 *
 * @event beforeFetchFormData
 * @event fetchFormDataOk
 *
 * @param {Array.<HTMLElement>} collection Array of fields or fields wrappers elements.
 * @param {string} actionURL Action request URL
 * @param {HTMLFormElement|string} form Submitting form or ID
 */
window.fetchFormData = (
    collection,
    actionURL,
    form = `fetchFormData`
) => {
    form = document.getElementById(form) ?? form;

    if (form.tagName !== `FORM`) {
        console.error(`[fetchFormData]: Invalid form element`);
        return;
    }

    const btn = form.querySelector(`:scope [type="submit"]`);

    // Listen the form submit
    form.addEventListener(`submit`, async event => {
        event.preventDefault();

        form.dispatchEvent(new CustomEvent(`beforeFetchFormData`));

        // Fetch data and get response
        const fetcher = new Fetcher({
            actionURL,
            collection,
            allowedNotices: ['error'],
        });

        // Start execute
        try {
            form.classList.add(UI.css.disabled);
            btn.classList.add(UI.css.process);

            // Validate and show errors if exist
            const isValid = await fetcher.validate();

            if (isValid) {
                const response = fetcher.response;
                let callback = response.redirect
                    ? () => redirect(response.redirect)
                    : null;

                // Response alerts handle or success notice
                if (response.alerts) {
                    for (let key in response.alerts) {
                        UI.Alert(response.alerts[key].message, callback);
                    }
                } else {
                    UI.OkNotice(response.message, callback);
                }

                form.dispatchEvent(new CustomEvent(`fetchFormDataOk`));
            }
        } catch (err) {
            console.error(`[fetchFormData]: error on line: ${err.lineNumber}`, err);
        } finally {
            form.classList.remove(UI.css.disabled);
            btn.classList.remove(UI.css.process);
        }
    });
}

/**
 * Send a request and get HTML,
 * insert html in targetElement.
 *
 * @param {HTMLElement} targetElement
 * @param {string} fetchURL
 * @param {FormData|JSON|null} fetchData
 * @param {function|null} resolve Callback on success
 * @param {function|null} reject Callback on failure
 */
window.importHTML = async ({
  targetElement,
  fetchURL,
  fetchData = null,
  resolve = null,
  reject = null
} = {}) => {
    // Checking incoming parameters
    if (!(targetElement instanceof HTMLElement) || !fetchURL) {
        console.error(`[ImportHTML]: Invalid target element or fetch URL`);
        return;
    }

    // Keep the initial state
    const originalHTML = targetElement.innerHTML;

    /**
     * Show the bootloader
     */
    function showLoader() {
        targetElement.innerHTML = ``;
        targetElement.append(createSpinner());
    }

    /**
     * Return the original state,
     * execute reject and log the error
     *
     * @param error
     * @return {Promise<boolean>}
     */
    async function handleError(error = null) {
        console.error(`[ImportHTML]:`, error ?? `Invalid response format`);
        targetElement.innerHTML = originalHTML;
        if (typeof reject === `function`) await reject();
        return false;
    }

    // Basic logic
    try {
        showLoader();

        const data = await fetchActionData(fetchURL, fetchData);

        if (! data?.html) {
            return await handleError();
        }

        targetElement.innerHTML = data.html;
        if (typeof resolve === `function`) await resolve();
        executeElementScripts(targetElement);

        return true;
    } catch (error) {
        return await handleError(error);
    }
}

/**
 * Dynamically loads Cropper.js scripts and styles
 * @param {function} callback - Function to run after scripts are loaded
 */
window.loadCropper = (callback) => {
    if (typeof Cropper !== `undefined`) {
        callback();
        return;
    }

    const cropperCss = document.createElement(`link`);
    cropperCss.rel = `stylesheet`;
    cropperCss.href = `https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css`;
    document.head.appendChild(cropperCss);

    const cropperJs = document.createElement(`script`);
    cropperJs.src = `https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js`;
    cropperJs.onload = callback;
    document.body.appendChild(cropperJs);
}

/**
 * Execute <script> scripts in an element.
 * When you need to execute all the scrips in the <script>
 * tags that are transmitted together with dynamically loaded content into the element.
 *
 * @param {HTMLElement} element HTMLElement with dynamically loaded content
 */
window.executeElementScripts = (element) => {
    if (! (element instanceof HTMLElement)) return;

    const handler = () => {
        element.querySelectorAll(`script`).forEach(script => {
            const newScript = document.createElement(`script`);

            if (script.src) {
                newScript.src = script.src;
                newScript.async = true;
                document.body.appendChild(newScript);
            } else {
                newScript.textContent = script.textContent;
                document.body.appendChild(newScript).remove();
            }
        });
    }

    handler();
}

/**
 * Get IP info from ipinfo.io API
 *
 * @param ip
 * @return {Promise<any|null>}
 * @constructor
 */
window.IPinfo = async (ip = ``) => {
    try {
        const url = `https://ipinfo.io/${ip}?token=52f92a91e1faeb`;
        const response = await fetch(url);
        return await response.json();
    } catch (err) {
        console.error(`[IPinfo]: error on line: ${err.lineNumber}`, err);
        return null;
    }
}

/**
 * Alert if session is over.
 * Check whether the subscriber session has ended in runtime.
 * It can also be used to synchronize logged-in subscriber
 * data between the server and the client
 *
 * @param {string} subscriber Subscriber (admin)
 */
window.pulse = (
    subscriber = ``,
) => {
    let key = null;
    const delay = 8000;

    // Query handler
    const handler = async () => {
        try {
            const response = await fetch(`/xhr/pulse`, {
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'X-CSRF-Token': global.csrfToken,
                },
                method: 'POST',
                body: JSON.stringify({
                    subscriber: subscriber,
                }),
            });

            // Catch Session errors
            if ([401, 403, 419].includes(response.status)) {
                clearInterval(interval);
                UI.hasDenied(response);
            }

            // const data = await response.json();
        } catch (err) {
            console.error(`[pulse]: error on line: ${err.lineNumber}`, err);
        }
    }

    // Is the subscriber currently on one of their account pages
    const isSubscriberArea = () => {
        return location.pathname.split(`/`)[2] === subscriber;
    }

    // Initialization conditions of the request handler
    if (
        ! [`admin`].includes(subscriber) ||
        ! isSubscriberArea()
    ) return;

    // send request every "delay" milliseconds
    const interval = setInterval(handler, delay);
}

/**************************
 * Paginating and Filtering
 **************************/

window.Paginator = class {
    /**
     * This is a basic class of pagination.
     * The class sends the request and receives the result of the
     * JSON {"html": "HTML Results"}, processes the script before
     * and after the request, inserts the result of the request
     * into the result container. Each item of the list of results
     * must have CSS class "Paginator_item" or pass your classname in
     * "resultContainerItemsClassName" config property. A notification
     * container of lack of result should have CSS class "Paginator_no-result".
     *
     * @param {Object} conf Paginator configuration
     * @param {FormData} conf.formData FormData object for fetchPaginatorResult
     * @param {HTMLElement|string} conf.resultContainer HTMLElement or selector of result container
     * @param {string} [conf.resultContainerItemsClassName = Paginator_item] CSS class of result items
     * @param {HTMLElement|string} conf.moreButton HTMLElement or selector of "more results" button
     * @param {string} conf.actionURL fetchPaginatorResult action request URL
     * @param {string} [conf.method = POST|GET] fetchPaginatorResult request method. "POST" by default
     */
    constructor(conf = {}) {
        this.conf = {
            formData: conf.formData,
            actionURL: conf.actionURL,
            method: conf.method?.toUpperCase() ?? `POST`,
        }
        this.resultContainer = conf.resultContainer instanceof HTMLElement
            ? conf.resultContainer
            : document.querySelector(conf.resultContainer);
        this.moreButton = conf.moreButton instanceof HTMLElement
            ? conf.moreButton
            : document.querySelector(conf.moreButton)
        this.resultItems = this.resultContainer.getElementsByClassName(
            conf.resultContainerItemsClassName ?? `Paginator_item`
        );
        this.isGetMethod = this.conf.method === `GET`;
        this.spinner = createSpinner();
        this.spinner.classList.add(`Paginator_spinner`);
        this.offset = this.getOffset();

        this.moreButton.addEventListener(`click`, () => this.fetchPaginatorResult());
    }

    getLimit() {
        return this.conf.formData.get(`limit`) ?? global.paginatorLimit;
    }

    getOffset() {
        return Array.from(this.resultItems).length ?? 0;
    }

    moreButtonDisable() {
        this.moreButton.hidden = true;
    }

    moreButtonEnable() {
        this.moreButton.hidden = false;
    }

    beforeFetchResult() {
        this.conf.formData.set(`offset`, String(this.offset));
        const lastResultItem = Array.from(this.resultItems).pop();
        ! lastResultItem
            ? this.resultContainer.prepend(this.spinner)
            : lastResultItem.after(this.spinner);
        this.moreButtonDisable();
    }

    afterFetchResult() {
        this.spinner.remove();
        this.resultContainer.querySelector(`:scope .Paginator_no-result`) ||
        this.resultItems.length % this.getLimit() !== 0
            ? this.moreButtonDisable()
            : this.moreButtonEnable();
    }

    insertResult(data) {
        if (!data.html || !this.spinner) return;
        this.spinner.insertAdjacentHTML(`afterend`, data.html);
        this.offset = this.getOffset();
        let temp = document.createElement(`div`)
        temp.innerHTML = data.html;
        executeElementScripts(temp);
        temp = null;
        return data;
    }

    async fetchPaginatorResult() {
        try {
            this.beforeFetchResult();

            let actionURL = this.conf.actionURL;
            if (this.isGetMethod) {
                const searchParams = new URLSearchParams(this.conf.formData).toString();

                actionURL = `${this.conf.actionURL}?${searchParams}`;
            }

            const data = await fetchActionData(
                actionURL,
                this.conf.formData,
                this.conf.method,
            );
            return this.insertResult(data);
        } catch (err) {
            console.error(`[Paginator.fetchPaginatorResult]: error on line: ${err.lineNumber}`, err);
        } finally {
            this.afterFetchResult();
        }
    }
}

window.Filter = class extends Paginator {
    /**
     * This is a basic class of filter and pagination results.
     * This class extends the Paginator class.
     * In initialization, it fills the form by according to the location.search.
     * After the request of results, location.search are updated by according
     * to the values of the form.
     *
     * @param {Object} conf Filter configuration
     * @param {HTMLFormElement|string} conf.form HTMLFormElement or selector of filter form
     * @param {HTMLElement|string} conf.resultContainer HTMLElement or selector of result container
     * @param {HTMLElement|string} conf.moreButton HTMLElement or selector of "more results" button
     * @param {string} conf.actionURL fetchPaginatorResult action request URL
     * @param {string} [conf.method = POST|GET] fetchPaginatorResult request method. "POST" by default
     * @param {boolean} [conf.enableUrlSearchParams = true] Використовувати параметри URL
     */
    constructor(conf= {}) {
        super(conf);

        this.conf.enableUrlSearchParams = conf.enableUrlSearchParams ?? true;

        this.form = conf.form instanceof HTMLFormElement
            ? conf.form
            : document.querySelector(conf.form)
        this.submitButton = this.form.querySelector(`:scope [type="submit"]`);
        this.conf.formData = new FormData(this.form);

        if (this.conf.enableUrlSearchParams) this.setFormValuesFromSearchParams();

        this.fastFiltering();

        this.form.addEventListener(`submit`, async event => {
            event.preventDefault();
            await this.fetchFilterResult();
        });
    }

    setFormValuesFromSearchParams() {
        const searchParams = new URLSearchParams(location.search);
        searchParams.forEach((value, key) => {
            const field = this.form.querySelector(`:scope [name="${key}"]`);
            if (! field) return;

            if (field.multiple && field.tagName === `SELECT`) {
                value.split(`,`).forEach(optValue => {
                    field.querySelector(`:scope option[value="${optValue}"]`)
                        .setAttribute(`selected`, true);
                });
            } else {
                field.value = value;
            }
        });

        UI.Select().render();
    }

    setSearchParamsFromFormValues() {
        const searchParams = new URLSearchParams(this.conf.formData);
        searchParams.delete(`_token`);
        const newUrl =
            document.location.href.split(/[?#]/)[0] +
            (searchParams.size ? `?${searchParams.toString()}` : ``) +
            document.location.hash;
        window.history.replaceState(null, null, newUrl);
    }

    fastFiltering() {
        const fields = this.form.querySelectorAll(`:scope [name]`);
        fields.forEach(field => {
            if (field.tagName === `SELECT`) {
                field.addEventListener(`change`, async () => await this.fetchFilterResult());
            }

            const debouncedFetch = debounce(async () => await this.fetchFilterResult());
            field.addEventListener(`input`, debouncedFetch);
        });
    }

    clearResult() {
        this.resultContainer.innerHTML = null;
        this.offset = 0;
    }

    beforeFetchResult() {
        this.conf.formData = new FormData(this.form);
        super.beforeFetchResult();
        if (this.submitButton) this.submitButton.classList.add(UI.css.process);
        this.form.classList.add(UI.css.disabled);
    }

    afterFetchResult() {
        super.afterFetchResult();
        if (this.submitButton) this.submitButton.classList.remove(UI.css.process);
        this.form.classList.remove(UI.css.disabled);
    }

    async fetchPaginatorResult() {
        await super.fetchPaginatorResult();
        if (this.conf.enableUrlSearchParams) this.setSearchParamsFromFormValues();
    }

    async fetchFilterResult() {
        this.clearResult();
        await this.fetchPaginatorResult();
    }
}

/**************************
 * Cookie
 **************************/

/**
 * Returns cookies with the specified name,
 * or undefined if nothing was found
 *
 * @param name
 * @return {string|undefined}
 */
window.getCookie = (name) => {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}


/**
 * Set cookie
 * @param name
 * @param value
 * @param options
 */
window.setCookie = (name, value, options = {}) => {

    options = {
        path: '/',
        // при необходимости добавьте другие значения по умолчанию
        ...options
    };

    if (options.expires instanceof Date) {
        options.expires = options.expires.toUTCString();
    }

    let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

    for (let optionKey in options) {
        updatedCookie += "; " + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }

    document.cookie = updatedCookie;
}

window.deleteCookie = (name) => {
    setCookie(name, "", {'max-age': -1})
}

/**************************
 * Other
 **************************/

/**
 * Create and return system spinner element
 *
 * @returns {HTMLDivElement}
 */
window.createSpinner = () => {
    const spinner = document.createElement(`div`);
    spinner.classList.add(`load-spinner`);
    spinner.innerHTML = `<img src="/images/loader.gif" width="28" alt="...">`;
    return spinner;
}

/**
 * Generates a URL-friendly slug from any string.
 *
 * @param {string} text Any string
 * @returns {string} Slug string
 */
window.createSlug = (text) => {
    if (!text || typeof text !== `string`) return ``;

    const dictionary = {
        // Cyrillic
        'а': `a`, 'б': `b`, 'в': `v`, 'г': `h`, 'ґ': `g`, 'д': `d`, 'е': `e`, 'є': `ye`,
        'ж': `zh`, 'з': `z`, 'и': `y`, 'і': `i`, 'ї': `yi`, 'й': `y`, 'к': `k`, 'л': `l`,
        'м': `m`, 'н': `n`, 'о': `o`, 'п': `p`, 'р': `r`, 'с': `s`, 'т': `t`, 'у': `u`,
        'ф': `f`, 'х': `kh`, 'ц': `ts`, 'ч': `ch`, 'ш': `sh`, 'щ': `shch`, 'ь': ``,
        'ю': `yu`, 'я': `ya`, 'ё': `yo`, 'ы': `y`, 'э': `e`,
        // European specific characters
        'ä': `a`, 'ö': `o`, 'ü': `u`, 'ß': `ss`, 'ą': `a`, 'ć': `c`, 'ę': `e`, 'ł': `l`,
        'ń': `n`, 'ó': `o`, 'ś': `s`, 'ź': `z`, 'ż': `z`, 'ç': `c`, 'ñ': `n`
    };

    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/['’ʻ‘]/g, ``) // Remove various types of apostrophes
        .normalize(`NFD`) // Decompose combined characters
        .split(``)
        .map(char => dictionary[char] !== undefined ? dictionary[char] : char)
        .join(``)
        .replace(/[\u0300-\u036f]/g, ``) // Remove leftover diacritical marks
        .replace(/[^a-z0-9]+/g, `-`) // Replace non-alphanumeric chars with hyphens
        .replace(/^-+|-+$/g, ``); // Trim hyphens from both ends
}

/**
 * Convert absolute URL to relative URL based on current site origin.
 *
 * @param {string} url - Absolute or relative URL
 * @return {string} - Relative URL starting with '/'
 */
function toRelativeUrl(url) {
    if (!url) return ``;

    // Remove origin if present
    if (url.startsWith(window.location.origin)) {
        url = url.replace(window.location.origin, ``);
    }

    // Ensure leading slash
    if (!url.startsWith(`/`)) {
        url = `/` + url;
    }

    return url;
}

/**
 * This is a wrapper that defers calls to f until ms milliseconds
 * of inactivity have passed, and then calls f once with the last arguments.
 *
 * @param func
 * @param wait
 * @return {(function(...[*]): void)|*}
 */
window.debounce = (func, wait = 300) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
};

/**
 * Update settings of consumer
 *
 * @param {string} consumerType (admin|user)
 * @param {string} dotTargetKey Path to settings value in dot notation
 * @param {string|Object} value New settings value then will be stringify to JSON
 * @param {HTMLElement|null} component Will be blocked at the time of the request
 * @return {Promise<void>}
 */
window.setConsumerSettings = async (
    consumerType,
    dotTargetKey,
    value,
    component = null
) => {
    let newSettings = null;

    try {
        component?.classList.add(UI.css.disabled);

        const data = await fetchActionData(
            `/xhr/set-consumer-settings`,
            JSON.stringify({
                consumerType,
                dotTargetKey,
                value,
            })
        );

        if (data.ok && data.message) UI.OkNotice(data.message);
        if (data.ok && data.newSettings) newSettings = data.newSettings;
    } catch (e) {
        console.error(`[setConsumerSettings]: Consumer settings not set.\n`, e);
    } finally {
        component?.classList.remove(UI.css.disabled);
    }

    return newSettings;
}

/**
 * Get array of TinyMCE content editors instances.
 *
 * All instances of editors in the returned array has additional methods:
 * * getFilteredContent() - Return filtered editor (this) content.
 *
 * @param {Object} conf Editor config with "target" or "selector" property.
 * This config will be merged with default configuration.
 * @return {Promise<[]>} Array of editors instances
 */
window.Editors = async (conf = {}) => {
    const defConf = {
        target: null,
        selector: null,
        language: `en`,
        min_height: 200,
        width: `100%`,
        content_css: global.editor_content_css,
        content_style: `body {margin: 1rem}`,
        content_css_cors: true,
        browser_spellcheck: true,
        contextmenu: false,
        toolbar_mode: `sliding`,
        statusbar: false,
        menubar: false,
        indent: false,
        invalid_styles: {
            '*': 'color line-height font-size font-family background background-color margin margin-top margin-right margin-bottom margin-left padding padding-top padding-right padding-bottom padding-left page-break-after page-break-inside',
        },
        invalid_elements: `style, script, html, link, body, head, meta, title, menu, canvas, dialog, form, h1, img, iframe, audio, video, embed, object, svg, template`,
        block_formats: 'Paragraph=p; Heading =h3; Blockquote=blockquote',
        plugins: [`charmap`, `emoticons`, `lists`, `quickbars`/*, `paste`*/],
        toolbar: `undo redo | blocks bold italic underline bullist numlist removeformat | charmap emoticons`,
        quickbars_insert_toolbar: `blocks charmap emoticons`,
        quickbars_selection_toolbar: `blocks bold italic underline removeformat`,

        // Plugins settings
        paste_auto_cleanup_on_paste: true, // Automatic cleaning
        paste_remove_styles: true,         // Inline styles
        paste_remove_styles_if_webkit: true, // Webkit styles
        paste_remove_spans: true,           // <span> tags
        // paste_strip_class_attributes: "all", // CLASS attributes
        // paste_data_images: false,           // Insert of Base64 images
    };
    conf = Object.assign({}, defConf, conf);

    return new Promise((resolve, reject) => {
        const resolver = async () => {
            const editors = await tinymce.init(conf);

            /**
             * Get editor content without fucking shit..(
             * @return {string}
             */
            function getFilteredContent() {
                return this.getContent()
                    .replaceAll(
                        /<p><br( data-mce-bogus="1")?><\/p>/ig,
                        ``
                    )
                    .replaceAll(
                        /<script(.+)?>(.+)?<\/script>/ig,
                        ``
                    );
            }

            editors.forEach(editor => {
                editor.getFilteredContent = getFilteredContent;
                if (getCookie(`light-theme`) === `true`) {
                    editor.getDoc().children[0].classList.add(`light-theme`);
                }
            });

            return resolve(editors);
        }

        if (typeof tinymce === `undefined`) {
            const script = document.createElement(`script`);
            script.type = `text/javascript`;
            script.referrerpolicy = `origin`;
            script.crossorigin = `anonymous`;
            script.src = window.global.tinyMceSrc;

            script.addEventListener(`load`, async () => await resolver());

            script.addEventListener(`error`, () => {
                console.error(`[Editors]: Failed to load TinyMCE script.\n`);
                return reject([]);
            });

            document.body.appendChild(script);
        } else {
            return resolver();
        }
    });
}

////////////////////////////////////////////////
// UI Extended
////////////////////////////////////////////////

/**
 * Observes when a sticky element becomes sticky or unsticks.
 *
 * @param {string} elementId ID of the sticky element.
 * @param {number} stickyTopValue CSS 'top' value (in pixels) for the sticky element.
 * @param {function(): void} onStickyCallback Callback when the element becomes sticky.
 * @param {function(): void} onUnstickyCallback Callback when the element unsticks.
 */
UI.observeStickyElement = (elementId, stickyTopValue, onStickyCallback, onUnstickyCallback) => {
    const targetElement = document.getElementById(elementId);

    if (!targetElement) {
        console.warn(`[observeStickyElement]: Element with ID "${elementId}" not found.`);
        return;
    }

    const sentinel = document.createElement(`div`);
    sentinel.style.height = `${stickyTopValue}px`;
    sentinel.style.visibility = `hidden`;
    sentinel.style.pointerEvents = `none`;

    targetElement.parentNode.insertBefore(sentinel, targetElement);

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Sentinel is in viewport (element unsticky or not yet sticky)
                if (typeof onUnstickyCallback === `function`) {
                    onUnstickyCallback();
                }
            } else {
                // Sentinel left viewport (element became sticky)
                if (typeof onStickyCallback === `function`) {
                    onStickyCallback();
                }
            }
        });
    }, {
        root: null, // Viewport as root
        rootMargin: `-${stickyTopValue}px 0px 0px 0px` // Threshold
    });

    observer.observe(sentinel);
}

/**
 * Mark menu item if it includes current location link
 *
 * @param {HTMLElement|string} ul Element or selector
 */
UI.markMenuItem = (ul = `.markMenuItem`) => {
    if (! ul) return;

    const collection = ul instanceof HTMLElement
        ? [...ul]
        : [...document.querySelectorAll(ul.toString())];

    if(! collection.length) return;

    const worker = ul => {
        const pageLocation = [
            `${location.protocol}//${location.host}${location.pathname}`,
            `${location.host}${location.pathname}`,
            `${location.pathname}${location.search}`,
            location.pathname,
            location.href,
        ];
        ul.querySelectorAll(`:scope a`).forEach(a => {
            if (pageLocation.includes(a.href)) a.classList.add(`current`);
        });
    }

    collection.forEach(worker);
}

/**
 * Rollup elements toggle
 */
UI.rollup = () => {
    const rollupShowedClassName = `rollupShowed`;
    const rollupTogglerIconClassName = `rollupTogglerIcon`;
    const components = document.querySelectorAll(`.rollupWrapper`);

    if (components.length < 1) return;

    components.forEach(component => {
        const section = component.querySelector(`:scope .rollupSection`);
        const toggler = component.querySelector(`:scope .rollupToggler`);
        const isShowed = () => section.classList.contains(rollupShowedClassName);

        if (toggler.classList.contains(rollupTogglerIconClassName)) {
            toggler.classList.replace(
                rollupTogglerIconClassName,
                isShowed() ? `UI_angle-up` : `UI_angle-down`
            );
        }

        toggler.addEventListener(`click`, () => {
            section.classList.toggle(rollupShowedClassName);
            toggler.classList.replace(
                ...(isShowed()
                    ? [`UI_angle-down`, `UI_angle-up`]
                    : [`UI_angle-up`, `UI_angle-down`])
            );
        });
    });
}

/**
 * Listen tabs click event and add tab data-tab as hash.
 * Get url hash, find and open tab.
 */
UI.openTabByUrlHash = () => {

    // Set hash
    document.addEventListener(`click`, event => {
        const tab = event.target.dataset.tab;
        if (tab) document.location.hash = tab;
    });

    // Open tab from hash
    const hash = document.location.hash.substring(1);
    if (hash) {
        const dt = document.querySelector(`:scope dt[data-tab="${hash}"]`);
        if (dt) {
            const dl = dt.closest(`dl`);
            const dtList = dl?.querySelectorAll(`:scope dt`);
            const tabsBuilder = dl?.UI.Builder;
            if (dtList && tabsBuilder) tabsBuilder.show([...dtList].indexOf(dt));
        }
    }
}

/**
 * Toggle more section in the elements with "panel" CSS class
 */
UI.panelToggle = () => {
    document.addEventListener(`click`, event => {

        // Toggle panel item more section
        const isToggleButton = event.target.classList.contains(`panel-item-more`);
        if (isToggleButton) {
            const toggleButton = event.target;
            const panelItem = toggleButton.closest(`.panel-item`);
            const moreContainer = panelItem.querySelector(`:scope .panel-item-more-container`);
            moreContainer.classList.toggle(`showed`);
        }
    });
}

/**
 * Toggle control panel of paginator item
 */
UI.paginatorItemControlToggle = () => {
    document.addEventListener(`click`, event => {
        const isTrigger = event.target;

        if (isTrigger.closest(`.paginatorItemControlToggle`)) {
            const parent = isTrigger.parentElement;
            const control = parent?.querySelector(`:scope .Paginator_item-control`);
            if (! control) return;

            control.classList.toggle(`show`);
        }
    });
}

/**
 * Handle bugReport triggers
 */
UI.bugReport = () => {
    document.addEventListener(`click`, async event => {
        const trigger = event.target.closest(`.bugReport`);

        if (! trigger) return;

        try {
            trigger.classList.add(UI.css.process);

            await fetchActionData(
                `/xhr/bug-report`,
                JSON.stringify({
                    code: trigger.dataset.code,
                    text: trigger.dataset.text
                })
            )

            trigger.classList.replace(UI.css.process, UI.css.ok);
            setTimeout(() => trigger.classList.remove(UI.css.ok), 3000);
        } catch (err) {
            console.error(`[UI.bugReport]: error on line: ${err.lineNumber}`, err);
        } finally {
            trigger.classList.remove(UI.css.process);
        }
    });
}

/**
 * Toggle Statistic Key Component
 */
UI.toggleStatisticKey = () => {
    document.addEventListener(`click`, async event => {
        const isTrigger = event.target.closest(`.toggleStatisticKeyComponent_trigger`);
        if (isTrigger) {
            event.stopImmediatePropagation();
            await handleToggleClick(isTrigger);
        }
    });

    async function handleToggleClick(trigger) {
        trigger.classList.add(UI.css.process);

        try {
            const component = trigger.closest(`.toggleStatisticKeyComponent`);
            const counter = component.querySelector(`.toggleStatisticKeyComponent_count`);

            const data = await fetchActionData(
                `/xhr/toggle-statistic-key`,
                JSON.stringify({
                    key: component.dataset.key,
                    model_type: component.dataset.modelType,
                    model_id: component.dataset.modelId,
                }
            ));

            if (data.status && data.trigger) {
                const triggerInner = trigger.querySelector(`.toggleStatisticKeyComponent_trigger-inner`);
                handleStatusChange(data, counter, triggerInner);
            }
        } catch (err) {
            console.error(`[Goods Toggle Component]: error on line: ${err.lineNumber}`, err);
        } finally {
            trigger.classList.remove(UI.css.process);
        }
    }

    function handleStatusChange(data, counter, triggerInner) {
        if (data.status === `added` && counter) {
            counter.innerText = parseInt(counter.innerText, 10) + 1;
        }

        if (data.status === `removed` && counter) {
            counter.innerText = parseInt(counter.innerText, 10) - 1;
        }

        triggerInner.innerHTML = data.trigger;
    }
}

/**
 * This works with "activity-indicator-component" components.
 * Collects data from components and sends requests every "delay" ms.
 * Supports multiple consumer types with unique "type::id" keys.
 *
 * @param {number} delay
 */
UI.updateActivityIndicators = (delay = 60000) => {
    const handler = async () => {
        try {
            // Get all components
            const components = document.querySelectorAll(`.consumerActivityIndicatorComponent`);
            if (components.length === 0) return;

            // Collect unique type::id combinations
            const consumersData = {};
            components.forEach(component => {
                const type = component.dataset.consumerType;
                const id = component.dataset.consumerId;

                if (type && id) {
                    consumersData[`${type}::${id}`] = {type, id: parseInt(id)};
                }
            });

            const idsAndTypes = Object.entries(consumersData).map(([key, data]) => ({
                type: data.type,
                id: data.id,
            }));

            if (idsAndTypes.length === 0) return;

            // Send request with ids and types
            const response = await fetch(`/xhr/get-consumers-activity`, {
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'X-CSRF-Token': global.csrfToken,
                },
                method: 'POST',
                body: JSON.stringify({
                    consumers: idsAndTypes
                }),
            });

            if (!response.ok) return;

            const data = await response.json();
            if (!data || typeof data !== 'object') return;

            // Update components
            components.forEach(component => {
                const type = component.dataset.consumerType;
                const id = component.dataset.consumerId;
                const key = `${type}::${id}`;

                if (!data[key]) return;

                const consumerData = data[key];
                const lastActivityElement = component.querySelector(`:scope .last-active-at`);

                // Update online status
                component.classList.toggle('online', consumerData.online);

                // Update last activity
                if (lastActivityElement) {
                    lastActivityElement.innerHTML = consumerData.lastActivity;
                }
            });
        } catch (err) {
            console.error(`[updateActivityIndicators]:`, err);
        }
    }

    // Interval handler
    return setInterval(handler, delay);
}

/**
 * Dropdown Menu Component
 */
UI.dropdownMenu = () => {
    const components = document.querySelectorAll(`.dropdownMenu`);
    const dropdowns = document.querySelectorAll(`.dropdownMenu .dropdown`)
    const hideAll = (untilDropdown = null) => {
        dropdowns.forEach(dropdown => {
            if (untilDropdown && dropdown === untilDropdown) return;
            dropdown.style.display = `none`;
        });
    }

    components.forEach(component => {
        const trigger = component.querySelector(`:scope > .trigger`)
        const dropdown = component.querySelector(`:scope .dropdown`);

        trigger.addEventListener(`click`, () => {
            hideAll(dropdown);
            UI.Toggle(dropdown, 'flex');
        });
    });

    document.addEventListener(`click`, event => {
        const isNotComponent = ! event.target.closest(`.dropdownMenu`);
        if (isNotComponent) hideAll();
    });
}

/**
 * Asynchronously loads the Google Maps API script into the document.
 * Ensures the script is loaded only once and provides a Promise-based interface
 * for managing its loading and availability.
 *
 * @param {string[]} libraries - An array of additional Google Maps API libraries to load (e.g., ['places', 'geometry']).
 * @param {string} lang - Interface language
 * @returns {Promise<typeof google>} A Promise that resolves with the global `google` object once the API is fully loaded.
 * Rejects if there's an error during script loading.
 */
UI.loadGoogleMapsAPI = async (libraries = [], lang = `en`) => {
    // Check if a Promise for loading Google Maps API already exists.
    if (UI.loadGoogleMapsAPI.googleMapsPromise) {
        return UI.loadGoogleMapsAPI.googleMapsPromise;
    }

    // If the Promise doesn't exist yet, create a new one.
    UI.loadGoogleMapsAPI.googleMapsPromise = new Promise((resolve, reject) => {
        // Check if Google API is already globally loaded.
        if (typeof google !== `undefined` && typeof google.maps !== `undefined`) {
            resolve(google);
            return;
        }

        // Construct the URL for the Google Maps API script.
        let scriptSrc = `${global.googleMapSrc}&language=${lang}`;

        if (libraries.length > 0) {
            scriptSrc += `&libraries=${libraries.join(`,`)}`;
        }

        // Generate a unique name for the global callback function.
        const uniqueCallbackName = `_googleMapsApiCallback_${Date.now()}`;
        scriptSrc += `&callback=${uniqueCallbackName}`;

        // Define the global callback function.
        window[uniqueCallbackName] = () => {
            // API loaded, resolve the Promise with the global Google object
            resolve(google);
            // Clean up the temporary global function after use.
            delete window[uniqueCallbackName];
        }

        // Create and configure the script element.
        const script = document.createElement(`script`)
        script.src = scriptSrc;
        script.async = true;
        script.defer = true;
        script.referrerPolicy = `origin`;

        // Handle script loading errors.
        script.onerror = (error) => {
            console.error(`Failed to load Google Maps API script:`, error);
            reject(new Error(`Failed to load Google Maps API. Check your network or API key.`));
            // In case of an error, reset the Promise to allow a retry on subsequent calls.
            // This is useful if, for example, there was a temporary network glitch.
            UI.loadGoogleMapsAPI.googleMapsPromise = null;
        }

        // Append the script to the <head> of the document.
        document.head.appendChild(script);
    })

    // Return the Promise. The calling function will `await` this Promise.
    return UI.loadGoogleMapsAPI.googleMapsPromise;
}

/**
 * Open Map on Popup and vew location
 */
UI.viewLocation = () => {
    // Open pop and insert into location data
    document.addEventListener(`click`, async event => {
        const isTrigger = event.target.closest(`.viewLocation`);
        const locationData = JSON.parse(isTrigger?.dataset.location ?? null) ?? null;

        if (! isTrigger || ! locationData) return false;

        event.preventDefault();

        // Load Google Maps API
        const google = await UI.loadGoogleMapsAPI();

        // Create popup if not exists and init then
        let pop = document.getElementById(`viewLocation`);
        if (! pop) {
            pop = document.createElement(`div`);
            pop.classList.add(`UI_Popup`, `popup-full`);
            pop.id = `viewLocation`;
            document.body.append(pop);
            UI.Popup();
        }

        UI.Popup(pop.id);

        // Init Map in Popup and add the marker
        const map = new google.maps.Map(
            pop,
            {
                zoom: 12,
                center: {lat: locationData.lat, lng: locationData.lng},
                mapTypeControl: false,
                streetViewControl: false,
                zoomControl: true,
                scrollwheel: false,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.BOTTOM_RIGHT,
                },
            }
        );
        new google.maps.Marker({
            map: map,
            position: {lat: locationData.lat, lng: locationData.lng},
        });
    });
}

/**************************
 * Listeners
 **************************/

document.addEventListener('DOMContentLoaded', () => {

    /**
     * Run Methods
     */
    UI.Menu({markLink: true});
    UI.rollup();
    UI.bugReport();
    UI.openTabByUrlHash();
    UI.panelToggle();
    UI.paginatorItemControlToggle();
    UI.markMenuItem();
    UI.toggleStatisticKey();
    UI.updateActivityIndicators();
    UI.dropdownMenu();
    UI.viewLocation();
});
