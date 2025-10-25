@props(['consumerPermissions' => []])

@php
    $currentConsumer = auth('admin')->user();

    if(! $currentConsumer) return;

    $currentConsumerType = $currentConsumer->type;
    $currentConsumerPermissions = $currentConsumer->permissions;
    $consumerPermissions = is_array($consumerPermissions) && !empty($consumerPermissions)
        ? $consumerPermissions
        : $currentConsumerPermissions;
@endphp

<!--- Permissions Component --->
<div id="permissionsComponent" {{ $attributes }}>
    @foreach($currentConsumerPermissions as $scope => $abilities)
        <span class="form_field-label">{!! __("form.permissions.$scope.label") !!}</span>
        <i class="base_hint-icon" data-hint="this"></i>
        <span class="UI_Hint">{!! __("form.permissions.$scope.hint") !!}</span>
        <select class="UI_Select"
                data-select-placeholder="{{ __("form.permissions.All_deny") }}"
                data-permissions-scope="{{ $scope }}" multiple>
            @foreach(str_split($abilities) as $ability)
                <option value="{{ $ability }}"
                    @selected(str_contains($consumerPermissions[$scope], $ability))>
                    {!! __("form.permissions.$ability") !!}
                </option>
            @endforeach
        </select>
    @endforeach

    <textarea name="permissions" type="hidden" required></textarea>
</div>

@pushOnce('endPage')

    <!--
    ########### Permissions Component
    -->

    <script>
        document.addEventListener(`DOMContentLoaded`, () => {
            const component = document.querySelector(`#permissionsComponent`);
            const scopes = component.querySelectorAll(`:scope select`);
            const dataField = component.querySelector(`:scope [name="permissions"]`);
            const setData = () => {
                const data = {};
                scopes.forEach(scope =>
                    data[scope.dataset.permissionsScope] = scope.UI.value.join(``) ?? ``
                );
                dataField.value = JSON.stringify(data);
            };

            scopes.forEach(scope => scope.addEventListener(`change`, setData));
            setData();
        });
    </script>
@endPushOnce
