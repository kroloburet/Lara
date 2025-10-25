<x-layouts.base
        :title="__('admin.moderator.add.meta_title')"
        :description="__('admin.moderator.add.meta_desc')"
>
    <x-layouts.admin.base>

        <h1>{{ __('admin.moderator.add.page_title') }}</h1>

        <form id="fetchFormData">
            <h3>{!! __('admin.security.Auth') !!}</h3>

            <span class="form_field-label">{!! __('form.email.label') !!}</span>
            <i class="base_hint-icon" data-hint="this"></i>
            <span class="UI_Hint">{!! __('form.email.hint') !!}</span>
            <x-form.email-component required />

            <span class="form_field-label">{!! __('form.password.label') !!}</span>
            <i class="base_hint-icon" data-hint="this"></i>
            <span class="UI_Hint">{!! __('form.password.hint') !!}</span>
            <x-form.password-component />

            <h3>{!! __('admin.security.Permissions') !!}</h3>

            <x-form.permissions-component />

            <div class="UI_fieldset UI_align-r">
                <button type="submit" class="UI_button">{!! __('admin.moderator.add.Add') !!}</button>
                <a href="{{ route('admin.moderators') }}" class="UI_button UI_contour">
                    {!! __('base.To_list') !!}
                </a>
            </div>
        </form>

        @pushOnce('endPage')

            <!--
            ########### Moderator Data Store
            -->

            <script>
                document.addEventListener(`DOMContentLoaded`, () => {
                    const form = document.getElementById(`fetchFormData`);
                    const collection = [
                        form.querySelector(`:scope #emailComponent`),
                        form.querySelector(`:scope #passwordComponent`),
                        form.querySelector(`:scope #permissionsComponent`),
                    ];
                    fetchFormData(collection, `{{ route('xhr.admin.create.moderator') }}`);
                });
            </script>
        @endPushOnce

    </x-layouts.admin.base>
</x-layouts.base>
