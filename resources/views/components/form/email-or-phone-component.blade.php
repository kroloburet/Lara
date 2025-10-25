@props([])

<!--- Email Or Phone Component --->
<span class="form_field-label">{!! __('form.email_or_phone.label') !!}</span>
<i class="base_hint-icon" data-hint="this"></i>
<span class="UI_Hint">{!! __('form.email_or_phone.hint') !!}</span>

<div id="emailOrPhoneComponent" {{ $attributes }}>
    <x-form.email-component />
    <x-form.phone-component />
</div>

<style>
    #emailOrPhoneComponent {
        --UI_form-focus-shadow: none;

        margin-top: var(--UI_form-gap-top);
        margin-bottom: var(--UI_form-gap-bottom);
    }

    #emailOrPhoneComponent #emailComponent .UI_input {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    #emailOrPhoneComponent .UI_input,
    #emailOrPhoneComponent .UI_form-component {
        margin: 0;
    }

    #emailOrPhoneComponent .UI_form-component > :not(.UI_Select-dropdown) {
        border-top: none;
        border-radius: 0;
    }

    #emailOrPhoneComponent .UI_form-component > :first-child {
        border-bottom-left-radius: var(--UI_form-border-radius);
    }

    #emailOrPhoneComponent .UI_form-component > :last-child:not(.UI_form-component-control) {
        border-bottom-right-radius: var(--UI_form-border-radius);
    }
</style>
