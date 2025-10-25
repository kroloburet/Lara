@props(['description' => '', 'required' => true])

<!--- Description Component --->
<label id="descriptionComponent" {{ $attributes }}>
    <textarea name="description" class="UI_textarea" data-lim="this, 250" @required($required)>{!! $description !!}</textarea>
</label>
