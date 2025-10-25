<x-layouts.base
    :title="__('admin.dashboard.meta_title')"
    :description="__('admin.dashboard.meta_desc')"
>
    <x-layouts.admin.base>

        <h1>{{ __('admin.dashboard.page_title') }}</h1>

        @can('superAdmin')
            <p>{!! __('admin.dashboard.desc_if_admin') !!}</p>
        @else
            <p>{!! __('admin.dashboard.desc_if_moderator') !!}</p>
        @endcan

        <x-admin.bug-reports-list-component />

        <h2>{!! __('base.Settings') !!}</h2>
        <div class="dashboard-panel">
            @can('superAdmin')
                <x-admin.app-access-component/>
                <x-admin.sitemap-generator-component/>

                <div class="dashboard-panel-item">
                    <h4>{!! __('settings.moderators.title') !!}</h4>
                    {!! __('settings.moderators.Count') !!} <b>{{ $moderators_count }}</b>
                    <a href="{{ route('admin.moderators') }}" class="UI_arrow-right">
                        {!! __('settings.moderators.Manage') !!}
                    </a>
                </div>
            @endcan
            <x-admin.set-consumer-timezone-component consumerType="admin"/>
        </div>

        @can('superAdmin')
            <x-admin.set-layout-component />

            <h2>{!! __('component.log_view.title') !!}</h2>
            <p>{!! __('component.log_view.system_desc') !!}</p>
            <x-admin.log-view-component class="layout-box-list" :log="$systemLog" />
        @endcan

    </x-layouts.admin.base>
</x-layouts.base>
