@props(['code'])

<x-layouts.base
    :title='__("errors.{$code}.meta_title")'
    :description='__("errors.{$code}.meta_desc")'
>

    <x-layouts.two-column>
        <x-slot:left>
            <h1>{{ __("errors.{$code}.page_title") }}</h1>

            {!! __("errors.{$code}.page_desc") !!}

            <div class="UI_fieldset UI_align-r">
                <a onclick="history.back()" class="UI_arrow-left UI_button UI_contour">
                    {{ __('base.Back') }}
                </a>
            </div>
        </x-slot:left>

        <x-slot:right>
        </x-slot:right>
    </x-layouts.two-column>

    @pushOnce('startPage')

        <!--
        ########### Error Page Styles
        -->

        <style>
            .two-column_right {
                background-image: url('/images/error_bg.svg');
                min-height: var(--layout-min-width);
            }

            .error-page-code {
                text-align: center;
                margin: auto;
                font-size: 3vw;
                color: var(--tertiary-color);
            }
        </style>
    @endPushOnce
</x-layouts.base>
