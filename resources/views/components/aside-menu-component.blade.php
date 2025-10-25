<!--- Aside Menu Component --->
<div {{ $attributes->class(['aside-menu', 'markMenuItem']) }}>
    <a href="{{ route('admin.dashboard') }}">
        <span class="ellipsis-overflow">
            {{ __('admin.nav.dashboard') }}
        </span>
    </a>

    @can('permits', ['moderator', 'r'])
        <a href="{{ route('admin.moderators') }}">
            <span class="ellipsis-overflow">
                {{ __('admin.nav.moderators') }}
            </span>
        </a>
    @endcan

    <a href="{{ route('admin.update.security') }}">
        <span class="ellipsis-overflow">
            {{ __('admin.nav.security') }}
        </span>
    </a>

    <a data-file-selector="">
        <span class="ellipsis-overflow">
            {{ __('admin.nav.file_manager') }}
        </span>
    </a>

    @can('permits', ['material', 'r'])
        <a href="{{ route('admin.update.static-material', ['type' => 'home', 'content_locale' => app()->getLocale()]) }}">
            <span class="ellipsis-overflow">
                {{ __('admin.nav.home') }}
            </span>
        </a>

        <a href="{{ route('admin.update.static-material', ['type' => 'contact', 'content_locale' => app()->getLocale()]) }}">
            <span class="ellipsis-overflow">
                {{ __('admin.nav.contact') }}
            </span>
        </a>

        <a href="{{ route('admin.materials', ['type' => 'category']) }}">
            <span class="ellipsis-overflow">
                {{ __('admin.nav.categories') }}
            </span>
        </a>

        <a href="{{ route('admin.materials', ['type' => 'page']) }}">
            <span class="ellipsis-overflow">
                {{ __('admin.nav.pages') }}
            </span>
        </a>
    @endcan

    @can('permits', ['menu', 'r'])
        <a href="{{ route('admin.menu') }}">
            <span class="ellipsis-overflow">
                {{ __('admin.nav.menu') }}
            </span>
        </a>
    @endcan

    <a href="{{ route('admin.help') }}">
        <span class="ellipsis-overflow">
            {{ __('admin.nav.help') }}
        </span>
    </a>
</div>
