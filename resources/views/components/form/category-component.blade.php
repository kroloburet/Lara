@props(['optionsTree' => null, 'material' => null])

<!--- CategoryComponent Component --->
<div id="categoryComponent">
    <select name="category_id" class="UI_Select"
            data-with-search="true"
            data-select-placeholder=""
            data-search-placeholder="{{ __('base.Search_on_list') }}">
        <option value="">{!! __('base.None') !!}</option>
        {!! $optionsTree !!}
    </select>
</div>
