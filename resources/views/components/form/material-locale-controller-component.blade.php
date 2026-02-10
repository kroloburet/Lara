@props(['type', 'content_locale', 'material' => null])

@php
    $_locale = $content_locale ?? app()->getLocale();
    $isStatic = config("app.materials.types.{$type}.static");
@endphp

<!--- Material Locale Controller Component --->
<div id="localeControllerComponent" {{ $attributes }}>
    <input name="type" type="hidden" value="{{ $type }}" required>

    <label class="UI_form-component">
        <select name="locale" class="UI_Select @if(! $isStatic) UI_inline-form @endif"
                data-select-placeholder="">
            @foreach(config('app.available_locales', []) as $name => $locale)
                <option value="{{ $locale }}" @selected($locale === $_locale)>{{ $name }}</option>
            @endforeach
        </select>

        @if(! $isStatic)
            <input name="alias"
                   @if(! $material?->alias) data-lim="this, 250" @endif
                   placeholder="alias-of-material"
                   data-model="{{ $type }}"
                   value="{{ $material?->alias }}"
                   @required(! $material?->alias)
                @readonly($material?->alias)>
        @endif
    </label>
</div>

@pushOnce('startPage')

    <!--
    ########### Material Locale Controller Component
    -->

    <style>
        #localeControllerComponent [readonly] {
            filter: grayscale(1);
            pointer-events: none;
            cursor: not-allowed;
            color: var(--UI_form-placeholder-color);
        }
    </style>
@endPushOnce

@if(! $isStatic)
    @pushOnce('endPage')

        <!--
        ########### Material Locale Controller Component
        -->

        <script>
            {
                document.addEventListener('DOMContentLoaded', () => {
                    const component = document.querySelector(`#localeControllerComponent`);
                    const aliasField = component.querySelector(`:scope [name="alias"]`);
                    const autoFormat = () => {
                        aliasField.value = aliasField.value
                            .replace(/[\s]+/g, '-')
                            .replace(/[^a-zA-Z0-9\-]/g, '')
                            .replace(/-{2,}/g, '-')
                            .toLowerCase()
                            .trim();
                    }

                    // Uniqueness check
                    const debouncedFetch = debounce(async () =>
                        await isUniqueValueRequest(aliasField, component));

                    aliasField.addEventListener(`keyup`, () => {
                        autoFormat();
                        debouncedFetch();
                    });
                });
            }
        </script>
    @endPushOnce
@endif

