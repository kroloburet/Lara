@props(['query' => '', 'required' => false])

<!--- Search Query Component --->
<input id="searchQueryComponent" {{ $attributes }} name="query"
       class="UI_input" type="text" placeholder="{{ __('base.Search_query') }}"
       value="{{ $query }}" @required($required)>
