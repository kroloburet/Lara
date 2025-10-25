@props(['content' => ''])

<!--- Content Component --->
<label id="contentComponent" class="UI_disabled-node" {{ $attributes }}>
    <textarea name="content" class="UI_textarea">{!! $content !!}</textarea>
</label>
