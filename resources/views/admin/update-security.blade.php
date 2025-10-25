<x-layouts.base
        :title="__('admin.security.meta_title')"
        :description="__('admin.security.meta_desc')"
>
    <x-layouts.admin.base>

        <h1>{{ __('admin.security.page_title') }}</h1>

        <form id="fetchFormData">
            <h3>{!! __('admin.security.Auth') !!}</h3>

            <span class="form_field-label">{!! __('form.email.label') !!}</span>
            <i class="base_hint-icon" data-hint="this"></i>
            <span class="UI_Hint">{!! __('form.email.hint') !!}</span>
            <x-form.email-component :email="$admin->email" required />

            <span class="form_field-label">{!! __('form.password.new_label') !!}</span>
            <i class="base_hint-icon" data-hint="this"></i>
            <span class="UI_Hint">{!! __('form.password.hint') !!}</span>
            <x-form.password-component :required="false"/>

            <div class="UI_fieldset UI_align-r">
                <button type="submit" class="UI_button">{!! __('base.Save') !!}</button>
            </div>
        </form>

        @pushOnce('endPage')

            <!--
            ########### Security Data Store
            -->

            <script>
                document.addEventListener(`DOMContentLoaded`, () => {
                    const form = document.getElementById(`fetchFormData`);
                    const collection = [
                        form.querySelector(`:scope #emailComponent`),
                        form.querySelector(`:scope #passwordComponent`),
                    ];
                    fetchFormData(collection, `{{ route('xhr.admin.update.security') }}`);
                });
            </script>
        @endPushOnce

    </x-layouts.admin.base>
</x-layouts.base>
