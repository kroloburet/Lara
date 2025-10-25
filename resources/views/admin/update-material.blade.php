<x-layouts.base
        :title='__("admin.{$type}.edit.meta_title")'
        :description='__("admin.{$type}.edit.meta_desc")'
>
    <x-layouts.admin.base>

        <h1>{!! __("admin.{$type}.edit.page_title") !!}</h1>

        <form id="fetchFormData">
            <dl class="UI_Tabs">
                <dt>{!! __('admin.material.Content') !!}</dt>
                <dd>
                    <p>{!! __('admin.material.Content_desc') !!}</p>

                    <span class="form_field-label">{!! __("admin.material.Lang_and_id") !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __("admin.material.Lang_and_id_hint") !!}</span>
                    <x-form.material-locale-controller-component :$type :$material :$content_locale />

                    <span class="form_field-label">{!! __("admin.material.category") !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __("admin.material.category_hint") !!}</span>
                    <x-form.category-component :$material/>

                    <span class="form_field-label">{!! __("admin.material.title") !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __("admin.material.title_hint") !!}</span>
                    <x-form.title-component/>

                    <span class="form_field-label">{!! __("admin.material.description") !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __("admin.material.description_hint") !!}</span>
                    <x-form.description-component/>

                    <span class="form_field-label">{!! __("admin.material.content") !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __("admin.material.content_hint") !!}</span>
                    <x-form.content-component/>

                    <x-admin.media-manager-component path="general" :$material />
                </dd>

                <dt>{!! __('admin.material.Layout') !!}</dt>
                <dd>
                    <p>{!! __('admin.material.Layout_desc') !!}</p>

                    <x-admin.bg-image-component :materialType="$type" :$material/>

                    <x-form.layout-component :materialType="$type" :$material />
                </dd>

                <dt>{!! __('admin.material.Additional') !!}</dt>
                <dd>
                    <p>{!! __('admin.material.Additional_desc') !!}</p>

                    <span class="form_field-label">{!! __('admin.material.robots') !!}</span>
                    <x-form.robots-component :robots="$material->robots"/>

                    <span class="form_field-label">{!! __('admin.material.css') !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __('admin.material.css_hint') !!}</span>
                    <x-form.css-component :css="$material->css"/>

                    <span class="form_field-label">{!! __('admin.material.js') !!}</span>
                    <i class="base_hint-icon" data-hint="this"></i>
                    <span class="UI_Hint">{!! __('admin.material.js_hint') !!}</span>
                    <x-form.js-component :js="$material->js"/>
                </dd>
            </dl>

            <div class="UI_fieldset UI_align-r">
                <button type="submit" class="UI_button">{!! __("base.Save") !!}</button>
                <a href="{{ route('admin.materials', ['type' => $type]) }}"
                   class="UI_button UI_contour">
                    {!! __('base.To_list') !!}
                </a>
            </div>
        </form>
    </x-layouts.admin.base>

    @pushOnce('endPage')
        @vite('resources/js/admin/material.js')

        <!--
        ########### Content Controller
        -->

        <script>
            document.addEventListener(`DOMContentLoaded`, () => {
                const form = document.querySelector(`#fetchFormData`);

                new Material({
                    action: `update`,
                    type: `{{ $type }}`,
                    actionURL: `{{ route('xhr.admin.update.material') }}`,
                    confirmText: `{!! __('admin.material.data_changed_confirm') !!}`,
                    editorLocale: `{{ app()->getLocale() }}`,
                    collection: [
                        form.querySelector(`:scope #localeControllerComponent`),
                        form.querySelector(`:scope #categoryComponent`),
                        form.querySelector(`:scope #titleComponent`),
                        form.querySelector(`:scope #descriptionComponent`),
                        form.querySelector(`:scope #contentComponent`),
                        form.querySelector(`:scope #layoutComponent`),
                        form.querySelector(`:scope #robotsComponent`),
                        form.querySelector(`:scope #cssComponent`),
                        form.querySelector(`:scope #jsComponent`),
                    ],
                    listenCollection: [
                        form.querySelector(`:scope [name="title"]`),
                        form.querySelector(`:scope [name="description"]`),
                        form.querySelector(`:scope [name="content"]`),
                    ],
                });
            });
        </script>
    @endPushOnce
</x-layouts.base>
