@php
    $currentConsumer = auth()->user();
    $email = $currentConsumer->email;
    $email = str($email)->mask('*', 3, strpos($email, '@') - 3);
@endphp

<x-layouts.base
    :title="__('verify.email.deny_page.meta_title')"
    :description="__('verify.email.deny_page.meta_desc')"
>

    <x-layouts.two-column>
        <x-slot:left>
            <h1>{!! __('verify.email.deny_page.page_title') !!}</h1>
            <p class="two-column_left-content-dim">
                {!! __('verify.email.deny_page.subtitle', compact('email')) !!}
                {!! __('verify.email.deny_page.lost_notice') !!}<br>
                <a id="resendVerifyNotice">{!! __('verify.email.deny_page.resend') !!}</a>
            </p>
        </x-slot:left>

        <x-slot:right></x-slot:right>
    </x-layouts.two-column>

@pushOnce('startPage')

    <!--
    ########### Verify Email Page Styles
    -->

    <style>
        .two-column_right {
            background-image: url('/images/email/email_verify_bg.svg');
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Resend Email Verify Notice
    -->

    <script>
        {
            const activateLink = document.getElementById(`resendVerifyNotice`);
            activateLink.onclick = async event => {
                event.preventDefault();
                activateLink.classList.add(UI.css.process);
                try {
                    const formData = new FormData();
                    const data = await fetchActionData(
                        `{{ route('verify.email.resend') }}`,
                        formData
                    );

                    if (! data) return;

                    UI.Alert(data.message);
                } catch (err) {
                    UI.ErrNotice(err);
                } finally {
                    activateLink.classList.remove(UI.css.process);
                }
            }
        }
    </script>
@endPushOnce
</x-layouts.base>
