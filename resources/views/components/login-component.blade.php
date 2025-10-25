@props([
    'login_heading',
    'login_fields',
    'recovery_fields',
    'login_action',
    'recovery_action'
])

<!--- Login Component --->
<form id="loginForm">
    {{ $login_heading }}

    {{ $login_fields }}

    <label class="UI_checkbox">
        <input type="checkbox" name="remember" value="1">
        <span class="UI_checkmark"></span>
        {!! __('auth.login.page.remember_label') !!}
    </label>

    <div class="UI_fieldset">
        <button type="submit" class="UI_button UI_arrow-right">
            {!! __('base.Login') !!}
        </button>
    </div>
</form>

<form id="recoveryLoginForm" class="hidden">
    <h1>{!! __('auth.login.page.recovery_access_header') !!}</h1>
    <p class="two-column_left-content-dim">{!! __('auth.login.page.recovery_access_desc') !!}</p>

    {{ $recovery_fields }}

    <div class="UI_fieldset">
        <button type="submit" class="UI_button">{!! __('base.Send') !!}</button>
        <button type="button" class="UI_button UI_contour recoveryLoginForm_cancel toggleRecoveryLoginForm">
            {!! __('base.Cancel') !!}
        </button>
    </div>
</form>

@pushOnce('startPage')

    <!--
    ########### Login Component
    -->

    <style>
        #loginForm.hidden,
        #recoveryLoginForm.hidden {
            display: none;
        }

        #loginForm, .UI_fieldset,
        #recoveryLoginForm, .UI_fieldset {
            justify-content: right;
        }

        .two-column_right {
            background-image: url('/images/login_bg.svg');
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Login Component
    -->

    <script>
        {
            const leftLayoutContainer = document.querySelector(`.two-column_left-content`);
            const loginForm = leftLayoutContainer.querySelector(`:scope #loginForm`);
            const loginBtn = loginForm.querySelector(`:scope [type=submit]`);
            const recoveryLoginForm = leftLayoutContainer.querySelector(`:scope #recoveryLoginForm`);
            const recoveryCancelBtn = recoveryLoginForm.querySelector(`:scope .recoveryLoginForm_cancel`);
            const recoveryBtn = recoveryLoginForm.querySelector(`:scope [type=submit]`);

            // Login request
            loginForm.onsubmit = async event => {
                event.preventDefault();
                loginBtn.classList.add(UI.css.process);

                try {
                    const data = await fetchActionData(
                        `{{ $login_action }}`,
                        new FormData(loginForm)
                    );

                    if (! data) return;

                    if (data.message) return UI.Alert(data.message);

                    if (data.redirect) return redirect(data.redirect);

                } catch (err) {
                    UI.ErrNotice(err);
                } finally {
                    loginBtn.classList.remove(UI.css.process);
                }
            }

            // Recovery login data request
            recoveryLoginForm.onsubmit = async event => {
                event.preventDefault();
                recoveryBtn.classList.add(UI.css.process);
                recoveryCancelBtn.classList.add(UI.css.disabled);

                try {
                    const data = await fetchActionData(
                        `{{ $recovery_action }}`,
                        new FormData(recoveryLoginForm)
                    );

                    if (! data) return;

                    if (data.message) UI.Alert(data.message);

                    if (data.ok) {
                        recoveryLoginForm.reset();
                        loginForm.reset();
                        loginForm.focus();
                        toggleForm();
                    }
                } catch (err) {
                    UI.ErrNotice(err);
                } finally {
                    recoveryBtn.classList.remove(UI.css.process);
                    recoveryCancelBtn.classList.remove(UI.css.disabled);
                }
            }

            // Toggle form
            const toggleForm = () => {
                loginForm.classList.toggle(`hidden`);
                recoveryLoginForm.classList.toggle(`hidden`);
            }
            document.addEventListener(`click`, event => {
                if (event.target.matches(`.toggleRecoveryLoginForm`)) toggleForm();
            });

            // Recovery first if #recovery in query
            if (document.location.hash === `#recovery`) toggleForm();
        }
    </script>
@endPushOnce
