@props(['name', 'value' => '', 'required' => false])

<!--- Public Editor Component --->
<label class="publicEditorComponent">
    <textarea name="{{ $name }}" {{ $attributes }} @required($required)>{!! $value !!}</textarea>
</label>

@pushOnce('endPage')

    <!--
    ########### Public Editor Component
    -->

    <script>
        document.addEventListener(`DOMContentLoaded`, async () => {
            const editors = await Editors({
                selector: `.publicEditorComponent textarea`,
                language: `{{ app()->getLocale() }}`,
            });

            editors.forEach(editor => {
                editor.formElement.addEventListener(`beforeFetchFormData`, () => {
                    editor.targetElm.value = editor.getFilteredContent();
                });
            });
        });
    </script>
@endPushOnce
