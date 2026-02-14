@php
    $contacts = materialBuilder('contact')->first();

    if (
        !$contacts ||
        (empty($contacts->phones) &&
        empty($contacts->emails) &&
        empty($contacts->social_networks) &&
        empty($contacts->links) &&
        empty($contacts->location))
    ) {
        return;
    }

    $uniqId = uniqid('contactsViewComponent_');
@endphp

<h2>{!! __('component.contacts_view.title') !!}</h2>
<section {{ $attributes->class(['contactsViewComponent', 'dashboard-panel']) }} id="{{ $uniqId }}">

    @if($contacts->phones)
        <div class="dashboard-panel-item">
            <h4>{!! __('component.contacts_view.Phones') !!}</h4>
            <x-phones-view-component :phones="$contacts->phones" />
        </div>
    @endif

    @if($contacts->emails)
        <div class="dashboard-panel-item">
            <h4>{!! __('component.contacts_view.Emails') !!}</h4>
            <x-emails-view-component :emails="$contacts->emails" />
        </div>
    @endif

    @if ($contacts->social_networks)
        <div class="dashboard-panel-item">
            <h4>{!! __('component.contacts_view.Social_networks') !!}</h4>
            <x-social-networks-view-component :social_networks="$contacts->social_networks" />
        </div>
    @endif

    @if ($contacts->links)
        <div class="dashboard-panel-item">
            <h4>{!! __('component.contacts_view.Links') !!}</h4>
            <x-links-view-component :links="$contacts->links" />
        </div>
    @endif

    @if ($contacts->location)
        <div class="dashboard-panel-item">
            <h4>{!! __('component.contacts_view.Location') !!}</h4>
            <span>
                <i class="fa-solid fa-location-dot"></i>&nbsp;
                <a class="viewLocation" data-location="{{ json_encode($contacts->location) }}">
                    {!! __('component.contacts_view.View_map') !!}
                </a>
            </span>
        </div>
    @endif
</section>
