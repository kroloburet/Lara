<x-layouts.base
    :title="__('verify.email.success_page.meta_title')"
    :description="__('verify.email.success_page.meta_desc')"
>

    <x-layouts.two-column>
        <x-slot:left>
            <h1>{!! __('verify.email.success_page.page_title') !!}</h1>
            <p>
                {!! __('verify.email.success_page.subtitle') !!}
            </p>
        </x-slot:left>

        <x-slot:right></x-slot:right>
    </x-layouts.two-column>

    @pushOnce('startPage')

        <!--
        ########### Verify Success Page Styles
        -->

        <style>
            .two-column_right {
                background-image: url('/images/email/email_verified_bg.svg');
            }
        </style>
    @endPushOnce
</x-layouts.base>
