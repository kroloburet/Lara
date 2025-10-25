<x-layouts.base
    :title="__('auth.login.page.admin.meta_title')"
    :description="__('auth.login.page.admin.meta_desc')"
>

    <x-layouts.two-column>
        <x-slot:left>
            <x-login-component
                :login_action="route('xhr.admin.login')"
                :recovery_action="route('xhr.admin.login.recovery')">
                <x-slot:login_heading>
                    <h1>{!! __('auth.login.page.admin.page_title') !!}</h1>
                    <p class="two-column_left-content-dim">
                        {!! __('auth.login.page.admin.dim') !!}
                    </p>
                </x-slot:login_heading>

                <x-slot:login_fields>
                    <span class="form_field-label">{!! __('form.email.label') !!}</span>
                    <x-form.email-component required />

                    <span class="form_field-label">{!! __('form.password.label') !!}</span>
                    <x-form.password-component :confirmation="false" />
                </x-slot:login_fields>

                <x-slot:recovery_fields>
                    <span class="form_field-label">{!! __('form.email.label') !!}</span>
                    <x-form.email-component required />
                </x-slot:recovery_fields>
            </x-login-component>
        </x-slot:left>

        <x-slot:right></x-slot:right>
    </x-layouts.two-column>
</x-layouts.base>
