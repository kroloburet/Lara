<x-layouts.base
    :title="__('verify.email.abort_page.meta_title')"
    :description="__('verify.email.deny_page.meta_desc')"
>

    <x-layouts.two-column>
        <x-slot:left>
            <h1>{!! __('verify.email.abort_page.page_title') !!}</h1>
            <section class="two-column_left-content-dim">
                {!! __('verify.email.abort_page.subtitle') !!}
            </section>
        </x-slot:left>

        <x-slot:right></x-slot:right>
    </x-layouts.two-column>

@pushOnce('startPage')

    <!--
    ########### Abort Verify Email Page Styles
    -->

    <style>
        .two-column_right {
            background-image: url('/images/email/email_verify_bg.svg');
        }
    </style>
@endPushOnce
</x-layouts.base>
