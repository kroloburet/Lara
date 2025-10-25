@php
    $uniqId = uniqid('appealFormComponent_');
@endphp

<!--- Appeal Form Component --->
<form id="{{ $uniqId }}" class="appealFormComponent">
    <div class="fieldWrapper">
        <x-form.email-or-phone-component />
    </div>

    <div class="fieldWrapper">
        <span class="form_field-label">{!! __('form.appeal.theme.label') !!}</span>
        <select class="UI_Select" data-select-placeholder="" name="appeal[theme]" required>
            <option value="appeal">
                {{ __('form.appeal.theme.appeal') }}
            </option>
            <option value="important">
                {{ __('form.appeal.theme.important') }}
            </option>
            <option value="collaboration">
                {{ __('form.appeal.theme.collaboration') }}
            </option>
            <option value="complaint">
                {{ __('form.appeal.theme.complaint') }}
            </option>
            <option value="bug">
                {{ __('form.appeal.theme.bug') }}
            </option>
            <option value="donate">
                {{ __('form.appeal.theme.donate') }}
            </option>
            <option value="question">
                {{ __('form.appeal.theme.question') }}
            </option>
            <option value="other">
                {{ __('form.appeal.theme.other') }}
            </option>
        </select>
    </div>

    <div class="fieldWrapper">
        <span class="form_field-label">{!! __('form.appeal.message.label') !!}</span>
        <textarea name="appeal[message]" class="UI_textarea" data-lim="this, 500" required></textarea>
    </div>

    <div class="UI_fieldset UI_align-r" style="margin-bottom: 0;">
        <button type="submit" class="UI_button">
            {!! __('base.Send') !!}
        </button>
        <button type="reset" class="UI_button UI_contour">
            {!! __('base.Cancel') !!}
        </button>
    </div>
</form>

<!--
########### Appeal Form Component
-->

<style>
    .appealFormComponent {
        margin-bottom: var(--layout-gap-l);
    }
</style>

<script>
    {
        // Determinate component Form & Popup
        const form = document.getElementById(`{{ $uniqId }}`);
        const pop = form.closest(`.UI_Popup`);

        // Add onsend listener
        fetchFormData(
            [ ...form.querySelectorAll(`.fieldWrapper`) ],
            `{{ route('xhr.appeal.send') }}`,
            form
        );

        // Reset Form after successful request
        form.addEventListener(`fetchFormDataOk`, () => form.reset());

        // Hide Popup if exists
        form.addEventListener(`reset`, () => {
            pop?.UI?.Builder?.hide();
        });
    }
</script>
