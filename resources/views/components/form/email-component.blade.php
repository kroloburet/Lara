@props(['email' => '', 'required' => false])

<!--- Email Component --->
<label id="emailComponent" {{ $attributes }}>
    <input class="UI_input" autocomplete="email" type="email" placeholder="{{ __('form.email.placeholder') }}"
           name="email" value="{!! $email !!}" @required($required)>
</label>
