@props(['consumerType'])

@php
    $currentTimezone = consumerSettings($consumerType, 'timezone');
@endphp

<!--- Set Consumer Timezone Component --->
<div id="setConsumerTimezoneComponent" class="dashboard-panel-item">
    <h4>{!! __('settings.timezone.title') !!}</h4>

    <span class="form_field-label">{!! __('settings.timezone.label') !!}</span>
    <i class="base_hint-icon" data-hint="this"></i>
    <span class="UI_Hint">{!! __('settings.timezone.hint') !!}</span>

    <div class="UI_size-s" id="setConsumerTimezoneComponent_clock"></div>

    <div class="UI_form-component">
        <select id="timezoneSettings" class="UI_Select"
                data-with-search="true"
                data-search-placeholder="{{ __('base.Search_on_list') }}">
            @foreach (timezone_identifiers_list() as $timezone)
                <option value="{{ $timezone }}" @selected($currentTimezone === $timezone)>
                    {{ $timezone }}
                </option>
            @endforeach
        </select>
        <i class="fa-solid fa-location-crosshairs UI_form-component-control
        setConsumerTimezoneComponent_get-current-btn"
           title="{{ __('settings.timezone.get_current') }}"></i>
    </div>
</div>

@pushOnce('startPage')

    <!--
    ########### Set Consumer Timezone Component
    -->

    <style>
        .setConsumerTimezoneComponent_get-current-btn.process::before {
            content: "\f110" !important;
            animation: fa-spin 2s linear infinite;
        }

        .setConsumerTimezoneComponent_get-current-btn.done::before {
            content: "\f00c" !important;
            color: var(--green-success);
            animation: none;
        }

        #setConsumerTimezoneComponent_clock {
            color: var(--tertiary-color);
        }

        #setConsumerTimezoneComponent_clock time {
            color: var(--UI_base-font-color);
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Set Consumer Timezone Component
    -->

    <script>
        {
            let timezone = null;
            let optionIndex = null;
            const component = document.getElementById(`setConsumerTimezoneComponent`);
            const timezoneSettings = document.getElementById(`timezoneSettings`);
            const clock = document.getElementById(`setConsumerTimezoneComponent_clock`);
            const getCurrentBtn = document.querySelector(`.setConsumerTimezoneComponent_get-current-btn`);

            const getOptionIndex = (selectElement, optionValue) => {
                for (let i = 0; i < selectElement.options.length; i++) {
                    if (selectElement.options[i].value === optionValue) {
                        return i;
                    }
                }
                return null;
            }

            /**
             * Update user clock
             */
            const updateClock = () => {
                const selectedTimezone = timezoneSettings.value;
                const now = new Date();
                const options = {
                    timeZone: selectedTimezone,
                    hour: `2-digit`,
                    minute: `2-digit`,
                    second: '2-digit',
                    hour12: false
                };
                const timeString = new Intl.DateTimeFormat(`en-US`, options).format(now);
                clock.innerHTML = `{{ __('base.Your_current_time') }} <time>${timeString}</time>`;
            }

            const getCurrentTimezone = async () => {
                try {
                    getCurrentBtn.classList.add(`process`, UI.css.disabled);

                    const info = await IPinfo();
                    timezone = info?.timezone;

                    if (timezone && (optionIndex = getOptionIndex(timezoneSettings, timezone))) {
                        timezoneSettings.UI.Builder.selected(true, [optionIndex]);
                        getCurrentBtn.classList.replace(`process`, `done`);
                        updateClock();
                    } else {
                        UI.Alert(`{!! __('settings.timezone.err_auto_detect') !!}`);
                    }
                } catch (e) {
                    console.error(`Error in determining the time zone:`, e);
                    UI.Alert(`{!! __('settings.timezone.err_auto_detect') !!}`);
                } finally {
                    getCurrentBtn.classList.remove(`process`, `done`, UI.css.disabled);
                }
            }

            // Update clock when changing time zone
            timezoneSettings.addEventListener(`UI.selected`, async () => {
                await setConsumerSettings(
                    `{{ $consumerType }}`,
                    `timezone`,
                    timezoneSettings.value,
                    component,
                );
                updateClock();
            });

            // Running a clock
            updateClock();
            setInterval(updateClock, 1000);

            getCurrentBtn.addEventListener(`click`, () => getCurrentTimezone());
        }
    </script>
@endPushOnce
