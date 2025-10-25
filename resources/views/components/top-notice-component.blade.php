@php
    $mode = appSettings('access.mode');
    $isAdmin = isAdminCheck();
@endphp

<!--- Top Notice Component --->
<div class="top-notice-wrapper">
    @if(session('error'))
        <div class="top-notice UI_notice-error">
            <div class="top-notice-content">
                {!! session('error') !!}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="top-notice UI_notice-success">
            <div class="top-notice-content">
                {!! session('success') !!}
            </div>
        </div>
    @endif

    @if($isAdmin && $mode !== 'allowed')
        <div class="top-notice UI_notice-warning">
            <div class="top-notice-content">
                {!! __("base.access_notice.{$mode}", ['mode' => __("settings.access.{$mode}")]) !!}
            </div>
        </div>
    @endif

    @if($isAdmin && ! Route::is('admin*'))
        <div class="top-notice UI_notice-warning">
            <div class="top-notice-content">
                {!! __("base.access_notice.admin", ['mode' => 'Admin']) !!}
            </div>
        </div>
    @endif
</div>
