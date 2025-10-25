@props([
    'subject' => '',
    'body' => '',
    'lang' => app()->getLocale(),
])

<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif">
<!-- Container -->
<table class="container" align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="margin: 0; max-width: 600px; width: 100%;">

    <!-- Header -->
    <tr>
        <td style="padding: 20px;">
            <table class="header-table" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <!-- Logo -->
                    <td class="header-logo" style="vertical-align: middle; text-align: left; width: auto">
                        <img src="{{ url('/meta/favicon-32x32.png') }}" alt="Logo {{ env("APP_NAME") }}" height="50" style="display: block; font-size: 10px;">
                    </td>
                    <!-- Link & Title -->
                    <td class="header-text" style="padding-left: 20px; vertical-align: middle; text-align: left;">
                        <h2 style="margin: 0; font-size: 20px; line-height: 1.2;">
                            <a href="{{ route('home') }}" style="text-decoration: none;">{{ env("APP_NAME") }}</a>
                        </h2>
                        <p style="margin: 5px 0 0; font-size: 14px; color: #757575;">{!! __('email.title', [], $lang) !!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Separator -->
    <tr>
        <td style="height: 20px;"></td>
    </tr>

    <!-- Body -->
    <tr>
        <td class="content" style="padding: 20px; text-align: left;">
            <h1 style="margin: 0 0 15px; font-size: 24px;">{{ $subject }}</h1>
            <div style="font-size: 16px; line-height: 1.5; margin: 0;">

                {!! $body !!}

            </div>
        </td>
    </tr>

    <!-- Separator -->
    <tr>
        <td style="height: 20px;"></td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="padding: 20px; text-align: left; font-size: 12px; color: #757575; font-style: italic;">
            {!! __('email.footer', [], $lang) !!}
        </td>
    </tr>
</table>
</body>
</html>
