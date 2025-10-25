@props(['title' => '', 'required' => true])

<!--- Title Component --->
<label id="titleComponent" {{ $attributes }}>
    <input class="UI_input" type="text" name="title" data-lim="this, 250" value="{!! $title !!}" @required($required)>
</label>
