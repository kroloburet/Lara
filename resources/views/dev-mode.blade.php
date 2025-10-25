<x-layouts.base
    :title="__('dev-mode.meta_title')"
    :description="__('dev-mode.meta_desc')"
>

    <x-layouts.two-column withoutMenu>
        <x-slot:left>
            <h1>{!! __('dev-mode.page_title') !!}</h1>
            <p>{!! __('dev-mode.page_desc') !!}</p>
        </x-slot:left>

        <x-slot:right></x-slot:right>
    </x-layouts.two-column>

    @pushOnce('startPage')

        <!--
        ########### Dev Mode Page Styles
        -->

        <style>
            .two-column {
                margin-bottom: auto !important;
            }

            .two-column_right {
                background-image: url('/images/dev_mode_bg.svg');
            }
        </style>
    @endPushOnce
</x-layouts.base>
