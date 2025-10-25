@props(['css' => ''])

<!--- Css Component --->
<label id="cssComponent" {{ $attributes }}>
    <textarea name="css" class="UI_textarea" rows="10" placeholder="<style>...</style>">{!! $css !!}</textarea>
</label>
