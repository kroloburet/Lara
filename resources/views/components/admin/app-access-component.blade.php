<!--- App Access Component --->
<div id="appAccessComponent" class="dashboard-panel-item">
    <h4>{!! __('settings.access.title') !!}</h4>

    <span class="form_field-label">{!! __('settings.access.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('settings.access.hint') !!}</span>
    <select id="accessModeSetting" class="UI_Select">
        <option value="allowed"
            @selected(appSettings('access.mode') === 'allowed')>
            {{ __('settings.access.allowed') }}
        </option>
        <option value="dev"
            @selected(appSettings('access.mode') === 'dev')>
            {{ __('settings.access.dev') }}
        </option>
    </select>
</div>

@pushOnce('endPage')

    <!--
    ########### App Access Component
    -->

    <script>
        {
            const component = document.getElementById(`appAccessComponent`)
            const accessModeSetting = component.querySelector(`#accessModeSetting`);

            accessModeSetting.addEventListener(`change`, async () =>
                await setAppSettings(`access.mode`, accessModeSetting.value, component)
            );
        }
    </script>
@endPushOnce
