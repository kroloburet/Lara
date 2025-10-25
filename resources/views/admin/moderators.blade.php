<x-layouts.base
        :title="__('admin.moderator.list.meta_title')"
        :description="__('admin.moderator.list.meta_desc')"
>
    <x-layouts.admin.base>

        <h1>{{ __('admin.moderator.list.page_title') }}</h1>

        <x-admin.moderators-list-filter-component />

    </x-layouts.admin.base>
</x-layouts.base>
