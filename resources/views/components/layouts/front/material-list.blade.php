@props(['materials'])

<!--- Material List --->
@forelse($materials as $item)
    <x-layouts.front.material-card-component :$item/>
@empty
    <div class="Paginator_no-result">
        {!! __('base.no_results') !!}
    </div>
@endforelse
