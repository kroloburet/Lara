<x-layouts.base
        :title="__('admin.menu.meta_title')"
        :description="__('admin.menu.meta_desc')"
>
    <x-layouts.admin.base>
        <h1>{{ __('admin.menu.page_title') }}</h1>

        <!-- Locale -->
        <h3>{{ __('admin.menu.Lang_version') }}</h3>
        <select id="menuLocaleField" class="UI_Select" data-select-placeholder="">
            @foreach(config('app.available_locales', []) as $confName => $confLocale)
                <option value="{{ $confLocale }}" @selected($confLocale === $locale)>{{ $confName }}</option>
            @endforeach
        </select>

        @can('permits', ['menu', 'c'])
            <!-- Create Item Form -->
            <form id="menuCreateItemForm">
                <h3>{{ __('admin.menu.Create_item') }}</h3>

                <div class="menuFormInnerContainer"></div>

                <div class="UI_fieldset UI_align-r">
                    <button type="submit" class="UI_button">{!! __('base.Create') !!}</button>
                </div>
            </form>
        @endcan

        @can('permits', ['menu', 'u'])
            <!--- Update Item Form --->
            <form id="menuUpdateItemForm" class="UI_Popup popup-full popup-l">
                <h3>{{ __('admin.menu.Update_item') }}</h3>

                <div class="menuFormInnerContainer"></div>

                <div class="UI_fieldset UI_align-r">
                    <button type="submit" class="UI_button">{!! __('base.Save') !!}</button>
                    <button type="reset" class="UI_button UI_contour cancel">{!! __('base.Cancel') !!}</button>
                </div>
            </form>
        @endcan

        <!--- Menu Tree --->
        <h3>{{ __('base.List') }}</h3>
        <ul id="menuTree" class="dashboard-panel-item"></ul>

        @pushonce('startPage')

            <!--
            ########### Menu
            -->

            <style>
                #menuTree {
                    list-style: none;
                    margin: 0;
                }

                #menuTree li {
                    display: flex;
                    flex-direction: column;
                    gap: .3rem;
                }

                #menuTree li:hover .menuItem {
                    background-color: var(--primary-bg-color);
                }

                #menuTree .menuItem {
                    display: grid;
                    grid-template-columns: auto max-content;
                    align-items: center;
                    gap: 1rem;
                    border-radius: var(--UI_base-border-radius-l);
                    padding: .5rem 1rem;
                }
            </style>
        @endpushonce

        @pushOnce('endPage')
            @vite('resources/js/admin/menu.js')

            <!--
            ########### Menu
            -->

            <script>
                document.addEventListener('DOMContentLoaded', () => new MenuManager({
                    langVersion: `{{ $locale }}`,
                    deleteConfirmMsg: `{!! __('admin.menu.del_confirm') !!}`
                }));
            </script>
        @endPushOnce

    </x-layouts.admin.base>
</x-layouts.base>
