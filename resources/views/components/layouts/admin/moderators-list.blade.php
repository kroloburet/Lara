@props(['moderators'])

@php
    $currentConsumer = auth()->user();
@endphp

<!--- Moderators List --->
@forelse($moderators as $moderator)
    <div class="Paginator_item">
        <div class="Paginator_item-prev fa-solid fa-user-astronaut"></div>

        <div class="Paginator_item-attr">
            {{ $moderator->email }}
        </div>

        <div class="Paginator_item-attr" title="Permissions level">
            <i class="fa-solid fa-pepper-hot"></i>&nbsp;
            {{ $moderator->permissionsLevel() }}
        </div>

        @if($currentConsumer->id !== $moderator->id)
            <div class="Paginator_item-control">
                <x-consumer-activity-indicator-component :consumer="$moderator" />

                <a class="fa-solid fa-info-circle"
                   data-hint="this"></a>
                <span class="UI_Hint">
                    id: {{ $moderator->id }}<br>
                    created: {{ consumerDateTimeFormat($moderator->created_at, 'admin') }}<br>
                    updated: {{ consumerDateTimeFormat($moderator->updated_at, 'admin') }}<br>
                </span>

                <span class="Paginator_item-control-separator"></span>

                @can('permits', ['moderator', 'u'])
                    <a href="{{ route('admin.update.moderator', ['id' => $moderator->id]) }}"
                       class="fa-solid fa-pen-to-square"
                       title="{{ __("admin.moderator.list.edit") }}"></a>
                @endcan

                @can('permits', ['moderator', 'd'])
                    <a class="toggleBlockModerator fa-solid {{ $moderator->trashed() ? 'fa-lock red-text' : 'fa-unlock' }}"
                       data-id="{{ $moderator->id }}"
                       title="{{ __("base.Toggle_block") }}"></a>

                    <a class="fa-solid fa-trash-can delModerator"
                       data-id="{{ $moderator->id }}"
                       title="{{ __("base.Delete") }}"></a>
                @endcan
            </div>
        @endif

        <div class="paginatorItemControlToggle"></div>
    </div>
@empty
    <div class="Paginator_no-result">
        {!! __('base.no_results') !!}
    </div>
@endforelse
