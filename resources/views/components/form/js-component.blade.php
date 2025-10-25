@props(['js' => ''])

<!--- Js Component --->
<label id="jsComponent" {{ $attributes }}>
    <textarea name="js" class="UI_textarea" rows="10" placeholder="<script>...</script>">{!! $js !!}</textarea>
</label>
