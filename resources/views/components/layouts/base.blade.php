<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? __('base.default_meta_desc') }}">
    <meta name="robots" content="{{ $robots ?? 'none' }}">
    <link href="{{ Vite::asset('node_modules/kroloburet_ui/UI.css') }}" rel="stylesheet">
    <link href="{{ Vite::asset('resources/css/base.css') }}" rel="stylesheet">
    <title>{{ $title ?? __('base.default_meta_title') }}</title>

    <!--
    ###########################
    # SEO, Markup Data, Meta, JSON-LD...
    ###########################
    -->

    <x-seo-meta-component :resource="$seo ?? null" />

    <!--- START Meta Images & Manifest Section --->
    @php($share_image = $previewImage ?? url('meta/share.png'))
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{ url('meta/apple-touch-icon-57x57.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="{{ url('meta/apple-touch-icon-60x60.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ url('meta/apple-touch-icon-72x72.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="{{ url('meta/apple-touch-icon-76x76.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ url('meta/apple-touch-icon-114x114.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ url('meta/apple-touch-icon-120x120.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('meta/apple-touch-icon-144x144.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('meta/apple-touch-icon-152x152.png') }}" />
    <link rel="icon" type="image/png" href="{{ url('meta/favicon-16x16.png') }}" sizes="16x16" />
    <link rel="icon" type="image/png" href="{{ url('meta/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ url('meta/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/png" href="{{ url('meta/favicon-128x128.png') }}" sizes="128x128" />
    <link rel="icon" type="image/png" href="{{ url('meta/favicon-196x196.png') }}" sizes="196x196" />
    <meta name="application-name" content="&nbsp;"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="{{ url('meta/mstile-144x144.png') }}" />
    <meta name="msapplication-square70x70logo" content="{{ url('meta/mstile-70x70.png') }}" />
    <meta name="msapplication-square150x150logo" content="{{ url('meta/mstile-150x150.png') }}" />
    <meta name="msapplication-wide310x150logo" content="{{ url('meta/mstile-310x150.png') }}" />
    <meta name="msapplication-square310x310logo" content="{{ url('meta/mstile-310x310.png') }}" />
    <link rel="manifest" href="{{ url('/meta/manifest.json') }}">
    <!--- END Meta Images & Manifest Section --->

    <!--- Twitter --->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="{{ env('APP_NAME') }}">
    <meta name="twitter:title" content="{{ $title ?? __('base.default_meta_title') }}">
    <meta name="twitter:description" content="{{ $description ?? __('base.default_meta_desc') }}">
    <meta name="twitter:image:src" content="{{ $share_image }}">
    <meta name="twitter:domain" content="{{ env('APP_URL') }}">
    <!--- Facebook --->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title ?? __('base.default_meta_title') }}">
    <meta property="og:description" content="{{ $description ?? __('base.default_meta_desc') }}">
    <meta property="og:image" content="{{ $share_image }}">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:site_name" content="{{ env('APP_NAME') }}">
    <!--- Other --->
    <link rel="image_src" href="{{ $share_image }}">
    <!--- Contact --->
    <script type="application/ld+json">
    @verbatim
        {
            "@context":"http://schema.org",
            "@type":"Organization",
            "name":"{{ env('APP_NAME') }}",
            "url":"{{ env('APP_URL') }}",
            "logo":"{{ url('meta/share.png') }}"
        }
    @endverbatim
    </script>

    <style>
        /*--- Page Preload ---*/
        #pagePreloaderBox {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999999;
            width: 100vw;
            height: 100vh;
            background-color: var(--UI_base-body-bg-color);
            background-image: url('/images/sky.svg');
            animation: slidein 10s;
            animation-fill-mode: forwards;
            animation-iteration-count: infinite;
            animation-direction: alternate;
        }

        #pagePreloaderBox::before {
            content: "";
            margin: auto;
            box-sizing: border-box;
            width: 100px;
            height: 100px;
            background: url('/images/loader.gif') center no-repeat;
        }

        @keyframes slidein {
            from {
                background-position: bottom left;
            }
            to {
                background-position: top right;
            }
        }
    </style>

@if(! Route::is('admin*'))
    @if(! empty($layout))
        <!--
        ########### Apply the layout settings of the current material
        -->

        <style>
            :root {
                --layout-max-width: {{ $layout->layout['layoutMaxWidth'] }}px;
                --layout-aside-width: {{ $layout->layout['asideWidth'] }}%;
                --layout-header-bg-image: url({{ materialBgImageUrl($layout) }});
            }
        </style>
    @endif

    <!-- Consent Mode Default -->
    <script src="/js/cookieconsent/cookieconsent-config.js" type="module"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}

        if (localStorage.getItem('consentMode') === null) {
            gtag('consent', 'default', {
                'ad_storage': 'denied',
                'ad_user_data': 'denied',
                'ad_personalization': 'denied',
                'analytics_storage': 'denied',
                'personalization_storage': 'granted',
                'functionality_storage': 'granted',
                'security_storage': 'granted',
            });
        } else {
            gtag('consent', 'default', JSON.parse(localStorage.getItem('consentMode')));
        }
    </script>
    <!-- End Consent Mode Default -->

    <!--- Google Tag Manager --->
{{--    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':--}}
{{--                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],--}}
{{--            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=--}}
{{--            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);--}}
{{--        })(window,document,'script','dataLayer','GTM-K7L8DGTX');</script>--}}
    <!--- End Google Tag Manager --->
@endif

    <!--- Page Preload --->
    <script>
        window.onload = () => document.getElementById('pagePreloaderBox').style.display = 'none';
    </script>

    <!--
    ###########################
    # startPage Stack
    ###########################
    -->

    @stack('startPage')

    @if(isset($css) && !empty($css))

    <!--
    ###########################
    # Material content CSS
    ###########################
    -->

    {{ $css }}
    @endif

    @if(now()->month >= 12 || now()->month <= 2)
        <x-snow-component/>
    @endif
</head>

<body id="startPage">
<!--- Page Preload --->
<div id="pagePreloaderBox"></div>

<!--- Google Tag Manager (noscript) --->
{{--<noscript>--}}
{{--    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K7L8DGTX" height="0" width="0" style="display:none;visibility:hidden"></iframe>--}}
{{--</noscript>--}}
<!--- End Google Tag Manager (noscript) --->

<!--
###########################
# Overall Scripts
###########################
-->

<script src="{{ Vite::asset('node_modules/kroloburet_ui/UI.js') }}"></script>
<script src="{{ Vite::asset('resources/js/base.js') }}"></script>

<script>
    /**
     * Global JS Vars
     */
    window.global = {
        csrfToken: `{{ csrf_token() }}`,
        tinyMceSrc: `{{ env('TINY_MCE_SCRIPTS') }}`,
        flmngrApiKey: `{{ env('FLMNGR_API_KEY') }}`,
        googleMapSrc: `{{ env('GOOGLE_MAP_SCRIPTS') }}`,
        paginatorLimit: {{ config('app.settings.paginatorLimit') }},
        editor_content_css: [
            `{{ Vite::asset('node_modules/kroloburet_ui/UI.css') }}?` + new Date().getTime(),
            `{{ Vite::asset('resources/css/base.css') }}?` + new Date().getTime(),
        ],
    }

    /**
     * Error notice extends UI
     *
     * @param {string|null} err Error message (text or HTML)
     * @param {boolean} throwable Whether to throw an exception
     * @param {function|null} callback Callback function after notification
     */
    UI.ErrNotice = (err = `It is not known yet`, throwable = false, callback = null) => {
        UI.Alert(
            `{!! __('errors.default', ['error' => '${err}']) !!}`,
            callback
        );

        if (throwable) throw err;
    }

    /**
     * Access denied notice extends UI
     *
     * @param {string|null} message Access message (text or HTML)
     * @param {boolean} throwable Whether to throw an exception
     * @param {function|null} callback Callback function after notification
     */
    UI.accessDenied = (message = ``, throwable = false, callback = null) => {
        UI.Alert(
            `${message ?? `{{ __('errors.xhr.401') }}`}`,
            callback ?? (() => redirect())
        );

        if (throwable) throw message ?? `Access Denied!`;
    }

    /**
     * Too many attempts notice extends UI
     *
     * @param {boolean} throwable Whether to throw an exception
     * @param {function|null} callback Callback function after notification
     */
    UI.tooManyAttempts = (throwable = false, callback = null) => {
        UI.Notice({
            className: `UI_notice-warning`,
            message: `{!! __('errors.xhr.429') !!}`,
            delay: 7000,
            callback: callback,
        });

        if (throwable) throw err;
    }

    /**
     * Success notice extends UI
     *
     * @param {string|null} message Success message (text or HTML)
     * @param {function|null} callback Callback function after notification
     * @return {HTMLElement} Notice container
     */
    UI.OkNotice = (message = ``, callback = null) => {
        return UI.Notice({
            className: `UI_notice-success`,
            message: message,
            delay: 2000,
            callback: callback,
        }).get;
    }

    /**
     * Confirm notice extends UI
     *
     * @param {string|null} message Confirm message (text or HTML)
     * @param {function|null} resolve Callback function if ok
     * @param {function|null} reject Callback function if cancel
     * @param {Node|string[]|null} insert Insert nodes to confirm container
     * @param {boolean} secret If true, the user has to enter email
     * @returns {boolean}
     */
    UI.Confirm = (message = ``, resolve = null, reject = null, insert = null, secret = false) => {
        let result = false;
        const messageContainer = document.createElement(`div`);
        const buttonsContainer = document.createElement(`div`);
        const cancel = document.createElement(`button`);
        const ok = document.createElement(`button`);
        const inserts = [];
        const notice = UI.Notice({
            className: `UI_Confirm`,
        });

        messageContainer.classList.add(`UI_Confirm-message-container`);
        buttonsContainer.classList.add(`UI_Confirm-buttons-container`, `UI_fieldset`);
        cancel.classList.add(`UI_Confirm-button-cancel`, `UI_button`, `UI_contour`);
        ok.classList.add(`UI_Confirm-button-ok`, `UI_button`);
        cancel.innerText = `{!! __('base.No') !!}`
        ok.innerText = `{!! __('base.Yes') !!}`
        messageContainer.innerHTML = message;
        buttonsContainer.append(ok, cancel);

        if (insert) messageContainer.append(insert);
        inserts.push(messageContainer);

        if (secret) {
            const secret = `{{ auth()->user()?->email }}`;
            const secretContainer = document.createElement(`div`);
            const secretLabel = document.createElement(`span`);
            const secretField = document.createElement(`input`);
            secretContainer.classList.add(`UI_Confirm-secret-container`);
            secretLabel.classList.add(`form_field-label`);
            secretField.classList.add(`UI_input`);
            secretLabel.innerHTML = `{!! __('base.secret_label_text') !!}`;
            secretField.setAttribute(`type`, `text`);
            secretField.setAttribute(`placeholder`, secret);
            secretContainer.append(secretLabel, secretField);
            inserts.push(secretContainer);
            ok.style.display = `none`;

            secretField.addEventListener(`input`, () => {
                ok.style.display = secret && secretField.value === secret ? `block` : `none`;
            });
        }

        inserts.push(buttonsContainer);
        notice.insert(...inserts);

        ok.addEventListener(`click`, async () => {
            notice.remove();
            if (typeof resolve === `function`) await resolve();
            result = true;
        });

        cancel.addEventListener(`click`, async () => {
            notice.remove();
            if (typeof reject === `function`) await reject();
            result = false;
        });

        return result;
    }

    /**
     * Alert notice extends UI
     *
     * @param {string|null} message Alert message (text or HTML)
     * @param {function|null} callback Callback function if ok
     * @return {Object} [notice, messageContainer, buttonsContainer]
     */
    UI.Alert = (message = ``, callback = null) => {
        const messageContainer = document.createElement(`div`);
        const buttonsContainer = document.createElement(`div`);
        const ok = document.createElement(`button`);
        const notice = UI.Notice({
            className: `UI_Alert`,
        }).insert(messageContainer, buttonsContainer);

        messageContainer.classList.add(`UI_Alert-message-container`);
        buttonsContainer.classList.add(`UI_Alert-buttons-container`, `UI_fieldset`);
        ok.classList.add(`UI_Alert-button-ok`, `UI_button`);
        ok.innerText = `{!! __('base.Ok') !!}`
        messageContainer.innerHTML = message;
        buttonsContainer.append(ok);

        ok.addEventListener(`click`, async () => {
            notice.remove();
            if (typeof callback === `function`) await callback();
        });

        return {notice, messageContainer, buttonsContainer};
    }

    /**
     * Processing of the access denial status
     *
     * @param {Response} response - The raw Response object from a fetch call.
     * @return {boolean}
     */
    UI.hasDenied = (response) => {
        switch (response.status) {
            case 401: UI.Alert(`{!! __('errors.xhr.401') !!}`, (() => redirect())); break;
            case 402: UI.Alert(`{!! __('errors.xhr.402') !!}`); break;
            case 403: UI.Alert(`{!! __('errors.xhr.403') !!}`); break;
            case 404: UI.Alert(`{!! __('errors.xhr.404') !!}`); break;
            case 406: UI.Alert(`{!! __('errors.xhr.406') !!}`); break;
            case 412: UI.Alert(`{!! __('errors.xhr.412') !!}`); break;
            case 419: UI.Alert(`{!! __('errors.xhr.419') !!}`, (() => redirect())); break;
            case 429: UI.tooManyAttempts(); break;
            default: return false;
        }

        return true;
    }
</script>

<!--
###########################
# Start Body Slot
###########################
-->

{{ $slot }}

<!--
###########################
# End Body Slot
###########################
-->

<!--
###########################
# Helps & Assistants
###########################
-->

<x-complain-component />
<x-appeal-component />
<x-share-component />
<x-help-component />

<!--
########### Go Up Button
-->

<div class="goUpButton fa-solid fa-arrow-up" data-go-to="#startPage"></div>

@auth('admin')
    <script>
        // Alert if admin session is over in runtime
        document.addEventListener(`DOMContentLoaded`, () => pulse(`admin`));
    </script>
@endauth

<!--
###########################
# endPage Stack
###########################
-->

@stack('endPage')

@if(isset($js) && !empty($js))

<!--
###########################
# Material content JS
###########################
-->

{{ $js }}
@endif
</body>
</html>
