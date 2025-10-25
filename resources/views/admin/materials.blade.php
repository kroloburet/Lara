<x-layouts.base
        :title='__("admin.{$type}.list.meta_title")'
        :description='__("admin.{$type}.list.meta_desc")'
>
    <x-layouts.admin.base>

        <h1>{!! __("admin.{$type}.list.page_title") !!}</h1>

        <x-admin.material-list-filter-component :$type/>
    </x-layouts.admin.base>
</x-layouts.base>
