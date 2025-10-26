@php
    $availableLocales = config('app.available_locales', []);

    if (count($availableLocales) < 2) return;
@endphp

<!--- Lang Switcher Component --->
<select class="langSwitcherComponent UI_Select UI_inline-form" data-select-placeholder="">
    @foreach($availableLocales as $name => $locale)
        <option value="{{ $locale }}" @selected(request()->segment(1) === $locale)>{{ $name }}</option>
    @endforeach
</select>

@pushOnce('startPage')

    <!--
    ########### Lang Switcher Component
    -->

    <style>
        .langSwitcherComponent {
            margin: 0;
        }

        .langSwitcherComponent .UI_Select-control,
        .langSwitcherComponent .UI_form-component-control{
            border: none;
            box-shadow: none;
            align-items: center;
        }

        .langSwitcherComponent .UI_Select-control,
        .langSwitcherComponent .UI_form-component-control {
            padding: 0 .3em;
        }

        .langSwitcherComponent .UI_form-component-control {
            padding-right: 0;
        }

        .langSwitcherComponent .UI_Select-control .UI_Select-control-item {
            padding: 0;
        }

        .langSwitcherComponent .UI_form-component-control i {
            color: var(--UI_base-font-color);
            font-size: .6em;
        }

        .langSwitcherComponent .UI_Select-dropdown .UI_Select-dropdown-item,
        .langSwitcherComponent .UI_Select-dropdown .UI_disabled {
            padding: var(--UI_form-field-paddingY);
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Lang Switcher Component
    -->

    <script>
        {
            const redirect = (switcher) => {
                switcher.addEventListener('change', () => {
                    let uri = `${location.pathname}${location.search}${location.hash}`;
                    let re = new RegExp(`^\/{{ request()->segment(1) }}`);
                    location = uri.replace(re, `/${switcher.value}`);
                });
            };
            document.querySelectorAll(`.langSwitcherComponent`).forEach(redirect);
        }
    </script>
@endPushOnce
